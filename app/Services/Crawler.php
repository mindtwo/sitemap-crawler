<?php declare(strict_types=1);

namespace App\Services;

use App\Enums\ChangeFrequency;
use App\Enums\LocationStatus;
use App\Exceptions\ParsingException;
use App\Models\Domain;
use App\Models\DTOs\Sitemap;
use App\Parsers\SitemapIndexParser;
use App\Parsers\SitemapLocationParser;
use App\Repositories\LocationRepository;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

readonly class Crawler
{
    public function __construct(
        private SitemapDownloader $downloader,
        private SitemapIndexParser $indexParser,
        private SitemapLocationParser $locationParser,
        private LocationRepository $locations,
        private Robots $robots,
        private LoggerInterface $logger,
    ) {}

    /**
     * Crawl sitemaps recursively, by domain.
     */
    public function crawl(string|UriInterface $url): void
    {
        if (! $url instanceof UriInterface) {
            $url = new Uri($url);
        }

        if ($url->getScheme() === '') {
            $url = $url->withScheme('https');
        }

        if ($url->getPath() === '') {
            $url = $url->withPath('/sitemap.xml');
        }

        $sitemaps = $this->getSitemaps($url);

        collect($sitemaps)
            ->groupBy(fn (Sitemap $sitemap) => $sitemap->uri->getHost())
            ->each(fn (Collection $sitemaps, string $domain) => $this->process($domain, $sitemaps));
    }

    /**
     * Get all sitemaps, recursively.
     *
     * @return Sitemap[]
     */
    private function getSitemaps(UriInterface $uri): array
    {
        if (! $this->robots->isAllowed($uri)) {
            return [];
        }

        $path = $this->download($uri);

        if ($path === null) {
            return [];
        }

        $sitemaps = [new Sitemap(uri: $uri, path: $path)];

        try {
            $nestedSitemaps = $this->indexParser->parse($path);
        } catch (ParsingException $e) {
            $this->logger->error('Failed to parse sitemap', [
                'uri' => (string) $uri,
                'exception' => $e->getMessage(),
            ]);

            return $sitemaps;
        }

        foreach ($nestedSitemaps as $sitemap) {
            $sitemaps = [...$sitemaps, ...$this->getSitemaps(new Uri($sitemap))];
        }

        return $sitemaps;
    }

    /**
     * Process all urls for the given sitemaps.
     *
     * @param Collection<int, Sitemap> $sitemaps
     */
    private function process(string $domain, Collection $sitemaps): void
    {
        $domain = Domain::query()->firstOrCreate(['domain' => $domain]);

        $this->locations->resetLocationsForDomain($domain);

        foreach ($sitemaps as $sitemap) {
            $this->logger->info('Processing sitemap', ['uri' => (string) $sitemap->uri]);
            $this->importLocations($domain, $sitemap);
        }

        $this->locations->deletePendingLocations($domain);
    }

    /**
     * Attempt to download the sitemap.
     */
    private function download(UriInterface $uri): ?string
    {
        try {
            $this->logger->info('Downloading sitemap', ['uri' => (string) $uri]);

            return $this->downloader->download($uri);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                $this->logger->error('Sitemap not found', ['uri' => (string) $uri]);

                return null;
            }

            throw $e;
        }
    }

    /**
     * Import locations from the given sitemap into database.
     */
    private function importLocations(Domain $domain, Sitemap $sitemap): void
    {
        try {
            $uris = $this->locationParser->parse($sitemap->path);
        } catch (ParsingException $e) {
            $this->logger->error('Failed to parse sitemap', [
                'uri' => (string) $sitemap->uri,
                'exception' => $e->getMessage(),
            ]);

            return;
        }

        $count = 0;
        $locations = [];

        foreach ($uris as $uri) {
            if (! $this->robots->isAllowed(new Uri($uri['loc']))) {
                continue;
            }

            $locations[] = $this->getLocation($domain, $uri);

            if (++$count === 100) {
                DB::transaction(fn () => $this->locations->bulkInsert($locations), 5);
                $locations = [];
                $count = 0;
            }
        }

        if ($count > 0) {
            DB::transaction(fn () => $this->locations->bulkInsert($locations), 5);
        }
    }

    /**
     * Parse location attributes from sitemap.
     */
    private function getLocation(Domain $domain, array $uri): array
    {
        $checksum = sprintf('%u', crc32($uri['loc']));

        return [
            'domain_id' => $domain->id,
            'checksum' => $checksum,
            'location' => $uri['loc'],
            'last_modified_at' => $uri['lastmod']
                ? Carbon::parse($uri['lastmod'])->setTimezone(config('app.timezone'))->toDateTimeString()
                : null,
            'change_frequency' => $uri['changefreq']
                ? ChangeFrequency::from($uri['changefreq'])->value
                : null,
            'priority' => $uri['priority']
                ? (float) $uri['priority']
                : null,
            'status' => LocationStatus::Active->value,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
            'deleted_at' => null,
        ];
    }
}
