<?php declare(strict_types=1);

namespace App\Parsers;

use SimpleXMLElement;

class SitemapLocationParser extends Parser
{
    /**
     * {@inheritDoc}
     */
    protected function getNodeName(): string
    {
        return 'url';
    }

    /**
     * {@inheritDoc}
     */
    protected function processItem(SimpleXMLElement $element): array|string
    {
        return [
            'loc' => (string) $element->loc,
            'lastmod' => (string) $element->lastmod,
            'changefreq' => (string) $element->changefreq,
            'priority' => (string) $element->priority,
        ];
    }
}
