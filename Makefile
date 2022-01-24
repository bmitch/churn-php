.DEFAULT_GOAL := help

COMPOSER_BIN ?= composer
PHP_BIN ?= php

.PHONY: help
help:
	echo "Run make clean build"

.PHONY: box
box:
	test -e build/box.phar || curl -sL https://github.com/box-project/box/releases/download/3.9.1/box.phar -o build/box.phar

.PHONY: build
build: box
	scp -r src bin composer.json box.json.dist build/
	$(COMPOSER_BIN) config platform.php 7.1.3 --working-dir=build/
	$(COMPOSER_BIN) update --no-dev --no-interaction --prefer-dist --working-dir=build/
	$(PHP_BIN) build/box.phar compile --working-dir=build/

.PHONY: clean
clean:
	rm -rf build/src build/bin build/composer.json build/box.json.dist build/vendor build/composer.lock build/box.phar build/churn.phar

