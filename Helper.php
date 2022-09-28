<?php

namespace Traits;

trait Helper
{
    public function parse($file): array
    {
        $xml = simplexml_load_file($file);

        $books[] = [];
        foreach ($xml->children() as $row) {
            $book = [];
            $book['author'] = (string) $row->author;
            $book['name'] = (string) $row->name;

            $books[] = $book;
        }

        return $books;
    }

    public function scraper($dir, &$results = []): array
    {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                if (str_contains($path, '.xml')) {
                    $results[] = $path;
                }
            } else if ($value != "." && $value != "..") {
                $this->scraper($path, $results);
                if (str_contains($path, '.xml')) {
                    $results[] = $path;
                }
            }
        }

        return $results;
    }
}
