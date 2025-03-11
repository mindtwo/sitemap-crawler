<?php declare(strict_types=1);

namespace App\Parsers;

use App\Exceptions\ParsingException;
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
        if (! isset($element->loc)) {
            throw new ParsingException('Missing required "loc" element in sitemap.');
        }

        return (string) $element->loc;
    }
}
