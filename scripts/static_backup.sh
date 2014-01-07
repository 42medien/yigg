#!/bin/bash
DATE=`date +"%Y%m%d"`

rsync  --exclude-from=/srv/www/vhosts/yigg.de/scripts/rsync_exclude.txt -avz /srv/www/vhosts/ yigg@yigg.strongspace.com:/strongspace/yigg/yigg_backup