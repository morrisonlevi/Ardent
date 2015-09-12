<?php

if (!isset($argv)) {
    fprintf(STDERR, "Must be run on command line");
    exit(1);
}

if (!isset($argv[3])) {
    fprintf(STDERR, "USAGE: %s archive_name stubfile source1 [source2...]" . PHP_EOL, $argv[0]);
    exit(2);
}

$phar = new Phar($argv[1]);

foreach (array_slice($argv, 2) as $file) {
    $phar->addFile(__DIR__ . "/$file", $file);
}

$stub = $argv[2];

$phar->addFile(__DIR__ . "/$stub", $stub);
$phar->setStub($phar->createDefaultStub($stub));

