<?php

if (!isset($argv[1])) {
    file_put_contents(
        'php://stderr',
        "USAGE: php runtests.php <DIRECTORY>\n"
    );
    exit(1);
}
$path = $argv[1];
$dir = new SplFileInfo($path);
if (!$dir->isDir()) {
    file_put_contents(
        'php://stderr',
        "Argument '{$argv[1]}' is not a directory\n"
    );
    exit(1);
}

$OPTS='-d xdebug.profiler_enable=off';

$exclude = array(
    '.',
    '..',
    'setup.php'
);

$dir = new DirectoryIterator($path);
chdir($path);
foreach ($dir as $file) {
    $fileName = $file->getFilename();
    if (in_array($fileName, $exclude)) {
        continue;
    }
    
    echo "Running $fileName . . .\n";
    echo `php $OPTS $fileName`;
    echo str_repeat('=', 40), "\n";
}
