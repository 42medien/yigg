#!/bin/bash

echo "Deploying YiGG:"
echo "1: Fixing Perms"
sudo chown -R yigg.yigg /srv/www/vhosts/upcoming.yigg.de
sudo php5 /srv/www/vhosts/upcoming.yigg.de/symfony fix-perms

echo "2: Generating API-Stuff"
sudo php5 /srv/www/vhosts/upcoming.yigg.de/symfony webservice:generate-wsdl frontend api http://www.yigg.de/api

echo "2.1 Fixing borked wsdl"
sed "s_http://www.yigg.de/api/api.php_http://www.yigg.de/api_" /srv/www/vhosts/upcoming.yigg.de/htdocs/api.wsdl > /tmp/api.wsdl; sudo mv /tmp/api.wsdl /srv/www/vhosts/upcoming.yigg.de/htdocs/api.wsdl;

echo "2.2 Generating models / filters"
sudo php5 /srv/www/vhosts/upcoming.yigg.de/symfony doctrine:build-model
sudo php5 /srv/www/vhosts/upcoming.yigg.de/symfony doctrine:build-filters
sudo php5 /srv/www/vhosts/upcoming.yigg.de/symfony doctrine:build-forms

echo "3. Deploy?"
read -p "Press any key and deployment finally starts."

echo "3.1 Backup"
php5 /srv/www/vhosts/www.yigg.de/symfony project:deploy backup --go

echo "3.2 Deploy to production"
php5 /srv/www/vhosts/www.yigg.de/symfony project:disable frontend
php5 /srv/www/vhosts/upcoming.yigg.de/symfony project:deploy prod --go

echo "4. Remount Mem-cache"
sudo unmount -f /srv/memory_cache/
sudo mount -t tmpfs -o size=512M,nr_inodes=2k,mode=777 tmpfs /srv/memory_cache/
sudo chown root.root /srv/www/vhosts/www.yigg.de/config/system/voyager_crons
php5 /srv/www/vhosts/www.yigg.de/symfony project:optimize frontend prod
sudo /etc/init.d/cron restart
sudo /etc/init.d/memcached restart

echo "5. Log Rotate"
php5 /srv/www/vhosts/www.yigg.de/symfony log:rotate frontend prod
php5 /srv/www/vhosts/www.yigg.de/symfony log:rotate frontend prod cc
php5 /srv/www/vhosts/www.yigg.de/symfony project:enable frontend
echo "6 Finished"