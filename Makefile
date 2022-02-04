.DEFAULT_GOAL := help
SHELL := /bin/bash

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
	test -e build/box.phar || curl -sL https://github.com/box-project/box/releases/download/3.14.0/box.phar -o build/box.phar
	chmod +x build/box.phar

.PHONY: build
build:	## Build churn.phar
build: box
	scp -r src bin composer.json box.json.dist manifest.xml LICENSE.md build/
	$(COMPOSER_BIN) config platform.php 7.1.3 --working-dir=build/
	$(COMPOSER_BIN) update --no-dev --no-interaction --prefer-dist --working-dir=build/
	CHURN_VERSION=$$( $(PHP_BIN) build/bin/churn --version --no-ansi | grep -Po '(?<= )[^@]+' ) ;\
	sed -i -e "s@0.0.0-dev@$${CHURN_VERSION}@g" build/manifest.xml
	$(PHP_BIN) build/box.phar validate build/box.json.dist
	$(PHP_BIN) build/box.phar compile --working-dir=build/

.PHONY: clean
clean:	## Clean the build folder
clean:
	(cd build && rm -rf src bin composer.json box.json.dist manifest.xml LICENSE.md vendor composer.lock box.phar churn.phar)
