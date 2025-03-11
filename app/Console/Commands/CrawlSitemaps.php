<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\CrawlSitemap;
use App\Services\Crawler;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CrawlSitemaps extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:crawl {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl the given sitemap and store all URLs.';

    /**
     * Execute the console command.
     */
    public function handle(Crawler $crawler): int
    {
        $urls = config('site.sitemaps');
        $url = $this->argument('url');

        if ($url !== null) {
            $urls = [(string) $url];
        }

        foreach ($urls as $url) {
            $this->comment('Crawling sitemap: '.$url);
            $this->dispatch(new CrawlSitemap($url));
        }

        return self::SUCCESS;
    }
}
