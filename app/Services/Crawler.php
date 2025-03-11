<?php declare(strict_types=1);

namespace App\Services;

use App\Enums\ChangeFrequency;
use App\Enums\LocationStatus;
use App\Exceptions\CrawlingException;
use App\Models\Domain;
use App\Models\DTOs\Sitemap;
use App\Models\Location;
use App\Parsers\SitemapIndexParser;
use App\Parsers\SitemapLocationParser;
use App\Repositories\LocationRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

readonly class Crawler
{
    public function __construct(
        private SitemapDownloader $downloader,
        private SitemapIndexParser $indexParser,
        private SitemapLocationParser $locationParser,
        private LocationRepository $locations,
    ) {}

    /**
     * Crawl sitemaps recursively, by domain.
     *
     * @throws CrawlingException
     */
    public function crawl(string $url): void
    {
        $sitemaps = $this->getSitemaps($url);

        collect($sitemaps)
            ->groupBy(fn (Sitemap $sitemap) => $this->getDomain($sitemap->url))
            ->each(fn (Collection $sitemaps, string $domain) => $this->process($domain, $sitemaps));
    }

    /**
     * Get all sitemap URLs, recursively.
     *
     * @return Sitemap[]
     *
     * @throws CrawlingException
     */
    private function getSitemaps(string $url): array
    {
        $path = $this->downloader->download($url);
        $sitemaps = [new Sitemap(url: $url, path: $path)];
        $nestedSitemaps = $this->indexParser->parse($path);

        foreach ($nestedSitemaps as $sitemap) {
            $sitemaps = [...$sitemaps, ...$this->getSitemaps($sitemap)];
        }

        return $sitemaps;
    }

    /**
     * Process all urls for the given sitemaps.
     *
     * @param Collection<int, Sitemap> $sitemaps
     *
     * @throws CrawlingException
     */
    private function process(string $domain, Collection $sitemaps): void
    {
        $domain = Domain::query()->firstOrCreate(['domain' => $domain]);
        $this->locations->resetLocationsForDomain($domain);
        $urls = [];

        foreach ($sitemaps as $sitemap) {
            $urls = [...$urls, ...$this->locationParser->parse($sitemap->path)];
        }

        foreach ($urls as $url) {
            $checksum = sprintf('%u', crc32($url['loc']));
            Location::query()->updateOrCreate(
                ['domain_id' => $domain->id, 'checksum' => $checksum],
                [
                    'location' => $url['loc'],
                    'last_modified_at' => Carbon::parse($url['lastmod']),
                    'change_frequency' => ChangeFrequency::from($url['changefreq']),
                    'priority' => (float) $url['priority'],
                    'status' => LocationStatus::Active,
                ],
            );
        }

        $this->locations->deletePendingLocations($domain);
    }

    /**
     * Get domain name from URL.
     */
    private function getDomain(string $url): string
    {
        return (string) parse_url($url, PHP_URL_HOST);
    }
}
