<?php declare(strict_types=1);

namespace App\Models\DTOs;

class Sitemap
{
    public function __construct(
        public string $url,
        public string $path,
    ) {}
}
