<?php declare(strict_types=1);

namespace App\Parsers;

use SimpleXMLElement;

class SitemapIndexParser extends Parser
{
    /**
     * {@inheritDoc}
     */
    protected function getNodeName(): string
    {
        return 'sitemap';
    }

    /**
     * {@inheritDoc}
     */
    protected function processItem(SimpleXMLElement $element): array|string
    {
        return (string) $element->loc;
    }
}
