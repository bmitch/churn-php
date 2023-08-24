FROM php:8.1-cli

# Requirements for running phpunit
RUN apt-get update && apt-get install -y git zip
RUN pecl install xdebug && docker-php-ext-enable xdebug
ENV XDEBUG_MODE=coverage
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Build a subversion project
RUN apt-get install -y subversion

# See: https://subversion.apache.org/quick-start#setting-up-a-local-repo
RUN mkdir -p $HOME/.svnrepos/ \
 && svnadmin create ~/.svnrepos/repo \
 && svn mkdir -m "Create structure." file://$HOME/.svnrepos/repo/trunk file://$HOME/.svnrepos/repo/branches file://$HOME/.svnrepos/repo/tags \
 && mkdir -p /tmp/test \
 && cd /tmp/test \
 && svn checkout file://$HOME/.svnrepos/repo/trunk ./ \
 && svn add --force ./ \
 && svn commit -m "Initial import." \
 && touch Foo.php \
 && svn add Foo.php \
 && svn commit -m "First commit" \
 && echo '<?php class Foo {}' > Foo.php \
 && echo '<?php class Bar {}' > Bar.php \
 && svn add Bar.php \
 && svn commit -m "2nd commit"

COPY churn.yml /tmp/test/
