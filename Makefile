include config.mk
include sources.mk

.PHONY: clean check phar

phar: Ardent.phar

Ardent.phar: autoload.php $(SOURCES)
	$(PHP) -d phar.readonly=0 make-phar.php $@ $^

clean:
	rm -f Ardent.phar

check:
	$(PHP) $(PHPUNIT) -c phpunit.xml
