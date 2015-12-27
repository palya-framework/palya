#!/usr/bin/env bash

# apt ##########################################################################
add-apt-repository ppa:ondrej/php5-5.6

apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10
echo 'deb http://repo.mongodb.org/apt/ubuntu trusty/mongodb-org/3.0 multiverse' | tee /etc/apt/sources.list.d/mongodb-org-3.0.list

apt-get update -y

# std ##########################################################################
apt-get install -y curl git

# php ##########################################################################
apt-get install -y php5 php5-intl php5-mongo php5-xcache

# mongo ########################################################################
apt-get install -y mongodb-org

# composer #####################################################################
curl -sS https://getcomposer.org/installer | php
mv ./composer.phar /usr/local/bin/composer
