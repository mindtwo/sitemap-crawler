<?php declare(strict_types=1);

namespace App\Services;

use Chiiya\Common\Services\FileDownloader;
use Illuminate\Filesystem\Filesystem;

readonly class SitemapDownloader
{
    public function __construct(
        private FileDownloader $downloader,
        private Filesystem $filesystem,
    ) {}

    /**
     * Download the given sitemap and stream it to the storage.
     */
    public function download(string $url): string
    {
        $file = $this->downloader->download($url);
        $domain = parse_url($url, PHP_URL_HOST);
        $this->filesystem->ensureDirectoryExists(storage_path('app/private/sitemaps/'.$domain));
        $filename = basename($url);
        $path = storage_path('app/private/sitemaps/'.$domain.'/'.$filename);
        rename($file->getPath(), $path);

        return $path;
    }
}
