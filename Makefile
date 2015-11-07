include config.mk
include sources.mk

.PHONY: clean check phar

phar: Ardent.phar

load.php: $(SOURCES)
	$(PHP) make-loader.php $^ > $@

Ardent.phar: load.php $(SOURCES)
	$(PHP) -d phar.readonly=0 make-phar.php $@ $^

clean:
	rm -f Ardent.phar load.php

check: load.php
	$(PHP) $(PHPUNIT) -c phpunit.xml

