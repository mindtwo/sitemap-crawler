<?php declare(strict_types=1);

namespace App\Parsers;

use App\Exceptions\ParsingException;
use SimpleXMLElement;
use XMLReader;

abstract class Parser
{
    /**
     * Parse items from a sitemap file.
     *
     * @throws ParsingException
     */
    public function parse(string $path): array
    {
        $xml = XMLReader::open($path);

        if ($xml === false) {
            throw new ParsingException('Could not open sitemap file: '.$path);
        }

        $items = [];

        while ($xml->name !== $this->getNodeName()) {
            $result = $xml->read();

            if ($result === false) {
                return $items;
            }
        }

        do {
            $item = simplexml_load_string($xml->readOuterXml());

            if ($item === false) {
                throw new ParsingException('Could not parse sitemap item: '.$path);
            }

            $items[] = $this->processItem($item);
        } while ($xml->next($this->getNodeName()));

        $xml->close();

        return $items;
    }

    /**
     * Get the name of the node to parse.
     */
    abstract protected function getNodeName(): string;

    /**
     * Process a single item from the sitemap.
     */
    abstract protected function processItem(SimpleXMLElement $element): array|string;
}
