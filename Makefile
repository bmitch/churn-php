.DEFAULT_GOAL := help

COMPOSER_BIN ?= composer
PHP_BIN ?= php

## ----------------------------------------------------------------------
## To generate churn.phar, run: make clean build
## To also set the version, run: COMPOSER_ROOT_VERSION=x.y.z make clean build
## ----------------------------------------------------------------------

.PHONY: help
help:	## Show this help
help:
	@sed -ne '/@sed/!s/## //p' $(MAKEFILE_LIST)

.PHONY: box
box:	## Download box.phar
box:
	test -e build/box.phar || curl -sL https://github.com/box-project/box/releases/download/3.9.1/box.phar -o build/box.phar

.PHONY: build
build:	## Build churn.phar
build: box
	scp -r src bin composer.json box.json.dist build/
	$(COMPOSER_BIN) config platform.php 7.1.3 --working-dir=build/
	$(COMPOSER_BIN) update --no-dev --no-interaction --prefer-dist --working-dir=build/
	$(PHP_BIN) build/box.phar compile --working-dir=build/

.PHONY: clean
clean:	## Clean the build folder
clean:
	rm -rf build/src build/bin build/composer.json build/box.json.dist build/vendor build/composer.lock build/box.phar build/churn.phar

