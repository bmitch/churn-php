FROM php:8.1-cli

# Requirements for running phpunit
RUN apt-get update && apt-get install -y git zip
RUN pecl install xdebug && docker-php-ext-enable xdebug
ENV XDEBUG_MODE=coverage
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Build a mercurial project
RUN apt-get install -y mercurial

RUN cd /tmp \
 && hg init test \
 && cd test \
 && touch Foo.php \
 && hg add Foo.php \
 && hg --config ui.username="John Doe" commit Foo.php -m "First commit" \
 && echo '<?php class Foo {}' > Foo.php \
 && echo '<?php class Bar {}' > Bar.php \
 && hg add Bar.php \
 && hg --config ui.username="John Doe" commit Foo.php Bar.php -m "2nd commit"

COPY churn.yml /tmp/test/
