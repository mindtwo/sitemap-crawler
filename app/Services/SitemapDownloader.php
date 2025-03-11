<?php declare(strict_types=1);

namespace App\Services;

use Chiiya\Common\Services\FileDownloader;
use Illuminate\Filesystem\Filesystem;
use Psr\Http\Message\UriInterface;

readonly class SitemapDownloader
{
    public function __construct(
        private FileDownloader $downloader,
        private Filesystem $filesystem,
    ) {}

    /**
     * Download the given sitemap and stream it to the storage.
     */
    public function download(UriInterface $uri): string
    {
        $file = $this->downloader->download((string) $uri, [
            'headers' => [
                'User-Agent' => config('site.user_agent'),
            ],
        ]);
        $domain = $uri->getHost();
        $filename = basename($uri->getPath());
        $path = storage_path('app/private/sitemaps/'.$domain.'/'.$filename);
        $this->filesystem->ensureDirectoryExists(storage_path('app/private/sitemaps/'.$domain));
        rename($file->getPath(), $path);

        return $path;
    }
}
