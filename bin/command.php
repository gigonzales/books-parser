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

    $increment = 0;
    foreach ($items as $item) {
        $output = shell_exec('crontab -l');
        $fileName = '/tmp/items-' . $increment .'.txt';
        file_put_contents($fileName, json_encode($item));

        if (!str_contains(
                $output,
                '* * * * * cd ~/projects/books-parser && php bin/command.php '
                . $fileName)
        ) {
            file_put_contents(
                    '/tmp/crontab.txt',
                    $output . '* * * * * cd ~/projects/books-parser && php bin/command.php '
                    . $fileName
                    .' >> /tmp/log.txt 2>&1'
                    . PHP_EOL
            );
            echo exec('crontab /tmp/crontab.txt');
        }
        $increment++;
        print_r($item);
    }
} else {
    $output = shell_exec('crontab -l');
    file_put_contents('/tmp/items', json_encode($books));
    file_put_contents(
        '/tmp/crontab.txt',
        $output . '* * * * * cd ~/projects/books-parser && php bin/command.php '
        . '/tmp/items.txt'
        .' >> /tmp/log.txt 2>&1'
        . PHP_EOL
    );
    echo exec('crontab /tmp/crontab.txt');
}

if (isset($argv[1])) {
    $data = file_get_contents($argv[1]);
    $command->insertOrUpdate(json_decode($data, true));
}
