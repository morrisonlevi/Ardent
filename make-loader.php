<?php

if (!isset($argv)) {
    fprintf(STDERR, "Must be run on command line");
    exit(1);
}

if (!isset($argv[1])) {
    fprintf(STDERR, "USAGE: %s source1 [source2...]" . PHP_EOL, $argv[0]);
    exit(2);
}


fwrite(STDOUT, "<?php" . PHP_EOL);
foreach (array_slice($argv, 1) as $file) {
    fwrite(STDOUT, "require __DIR__ . '/$file';" . PHP_EOL);
}
