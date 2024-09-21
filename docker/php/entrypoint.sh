# shellcheck disable=SC2129
echo 'pm.max_children = 151' >> /usr/local/etc/php-fpm.d/www.conf
echo 'pm.max_requests = 1000' >> /usr/local/etc/php-fpm.d/www.conf
echo 'pm.start_servers = 151' >> /usr/local/etc/php-fpm.d/www.conf
echo 'pm.min_spare_servers = 151' >> /usr/local/etc/php-fpm.d/www.conf
echo 'pm.max_spare_servers = 151' >> /usr/local/etc/php-fpm.d/www.conf

docker-php-entrypoint php-fpm
