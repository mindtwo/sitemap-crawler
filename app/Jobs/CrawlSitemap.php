<?php declare(strict_types=1);

namespace App\Jobs;

use App\Services\Crawler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CrawlSitemap implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $url,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(Crawler $crawler): void
    {
        $crawler->crawl($this->url);
    }
}
