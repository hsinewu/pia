#!/bin/sh

# Caution
# =================================================
# This script assume your environment is "Centos 6"
# =================================================

bashDir="$( cd "$( dirname "$0" )" && pwd )"

# isntall web server
yum localinstall -y $bashDir/remi-release-6.rpm
yum -y install nginx
yum -y --enablerepo=remi,remi-php56 install php php-common php-pecl-apcu php-cli php-pear php-pdo php-mysqlnd php-gd php-mbstring php-mcrypt php-xml php-fpm php-sqlite
yum -y install mysql-server
curl -sS https://getcomposer.org/installer | /usr/bin/php
mv composer.phar /usr/local/bin/composer

# config for web server
sed -i -E "s/^ *;? *user *=.*$/user = vagrant/g" /etc/php-fpm.d/www.conf
sed -i -E "s/^ *;? *group *=.*$/group = vagrant/g" /etc/php-fpm.d/www.conf
sed -i -E "s/^ *;? *access.log *=.*$/access.log = \/root\/server.log/g" /etc/php-fpm.d/www.conf
sed -i -E "s/^ *;? *listen *=.*$/listen = \/var\/run\/php5-fpm.sock/g" /etc/php-fpm.d/www.conf

sed -i -E "s/^ *;? *date.timezone *=.*$/date\.timezone = Asia\/Taipei/g" /etc/php5/fpm/php.ini

rm -Rvf /etc/nginx
cp -Rv $bashDir/nginx /etc/

service nginx start
service php-fpm start
service mysqld start
chkconfig nginx on
chkconfig php-fpm on
chkconfig mysqld on
