#!/usr/bin/env php
<?php
require('Command.php');

use Command\Command;

$command = new Command();

$files = $command->scraper(DATA_STORAGE);

$books[] = [];

foreach ($files as $file) {
    $column = $command->parse($file);

    foreach ($column as $book) {
        $books[] = $book;
    }
}

if ($books > DATA_THRESHOLD) {
    $items = array_chunk(array_values(array_filter($books)), DATA_THRESHOLD);

    foreach ($items as $item) {
        $output = shell_exec('crontab -l');
        if (!str_contains(
                $output,
                '* * * * * cd ~/projects/books-parser && php bin/command.php '
                . escapeshellarg(serialize($item)))
        ) {
            file_put_contents(
                    '/tmp/crontab.txt',
                    $output . '* * * * * cd ~/projects/books-parser && php bin/command.php '
                    . escapeshellarg(serialize($item))
                    .' >> ../log.txt 2>&1'
                    . PHP_EOL
            );
            echo exec('crontab /tmp/crontab.txt');
        }

        print_r($item);
    }
}

if (isset($argv[1])) {
    $command->insertOrUpdate(unserialize($argv[1]));
}
