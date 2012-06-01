#!/bin/bash
# Script that dumps the database

DATE=`date +"%Y%m%d"`

mysqldump --host="127.0.0.1" -u root -p --password="c253eh08" --databases yigg_devel appstar_i18n yigg_blog > /backups/sql/backup.$DATE.sql
gzip -9 /backups/sql/backup.$DATE.sql

find /backups/sql -mtime +7 | xargs rm
rsync --delete  -avz /backups/sql yigg@yigg.strongspace.com:/strongspace/yigg/sql_backup