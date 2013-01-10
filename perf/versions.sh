#!/bin/bash

OPTS='-d xdebug.profiler_enable=off'

module unload php
module load php/5.4

php $OPTS runtest.php linkedlist >v5.4.txt

module switch php/5.3

php $OPTS runtest.php linkedlist >v5.3.txt


