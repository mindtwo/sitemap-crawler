<?php declare(strict_types=1);

namespace App\Parsers;

use App\Exceptions\ParsingException;
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
     *
     * @throws ParsingException
     */
    protected function processItem(SimpleXMLElement $element): array|string
    {
        if (! isset($element->loc)) {
            throw new ParsingException('Missing required "loc" element in sitemap.');
        }

        return [
            'loc' => (string) $element->loc,
            'lastmod' => (string) $element->lastmod,
            'changefreq' => (string) $element->changefreq,
            'priority' => (string) $element->priority,
        ];
    }
}
