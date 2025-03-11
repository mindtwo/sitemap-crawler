<?php declare(strict_types=1);

namespace App\Models\DTOs;

use Psr\Http\Message\UriInterface;

class Sitemap
{
    public function __construct(
        public UriInterface $uri,
        public string $path,
    ) {}
}
