<?php declare(strict_types=1);

namespace App\Services;

use Psr\Http\Message\UriInterface;
use Spatie\Robots\RobotsTxt;

class Robots
{
    protected array $cache = [];

    /**
     * Check if the given URI is allowed to be crawled.
     */
    public function isAllowed(UriInterface $uri): bool
    {
        if (! isset($this->cache[$uri->getHost()])) {
            $this->cache[$uri->getHost()] = RobotsTxt::create((string) $uri->withPath('/robots.txt'));
        }

        return $this->cache[$uri->getHost()]->allows((string) $uri, config('site.user_agent'));
    }
}
