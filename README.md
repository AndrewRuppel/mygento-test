# Installation guide
1. Run commandline `docker-compose up`
2. After containers start run commandline `docker exec -ti docker-magento2_php-fpm_1 /var/www/setup-magento.sh`
3. Then run `docker inspect docker-magento2_nginx_1 | grep "IPAddress"` to find out the ip address
4. Add to /etc/hosts line `172.25.0.5 mygento-test.my` where '172.25.0.5' is IP address from prev command's result

### Admin pannel
http://mygento-test.my/admin
Log: admin123
Pas: admin123

### Console command
1. Run commandline `bin/magento ymlimport:import`

### Cron
1. Run commandline `docker exec -ti docker-magento2_cron_1 /bin/bash`
2. Then from container run `bin/magento cron:install`
