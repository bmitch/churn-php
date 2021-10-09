FROM php:8.0-cli

# Requirements for running phpunit
RUN apt-get update && apt-get install -y git zip
RUN pecl install xdebug-3.0.2 && docker-php-ext-enable xdebug
ENV XDEBUG_MODE=coverage
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Build a fossil project
RUN apt-get install -y fossil

ENV FOSSIL_USER=john_doe

RUN mkdir -p /tmp/test \
 && cd /tmp/test \
 && fossil init test.fossil \
 && fossil open -f test.fossil \
 && touch Foo.php \
 && fossil add Foo.php \
 && fossil commit -m "First commit" \
 && echo '<?php class Foo {}' > Foo.php \
 && echo '<?php class Bar {}' > Bar.php \
 && fossil add Bar.php \
 && fossil commit -m "2nd commit"

COPY churn.yml /tmp/test/
