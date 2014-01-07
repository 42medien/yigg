DATE=`date +"%d.%m.%Y"`
/usr/bin/mysql_slow_log_parser /var/log/mysql/mysql-slow.log > /tmp/slow_log_report
sendEmail -s smtp.gmail.com -xu volcano@yigg.de -xp 83B227 -f curth@yigg.de -cc volcano@yigg.de -t hackers@yigg.de -u "[YiGG System] - Slow Querys of $DATE"  -o message-file=/tmp/slow_log_report tls=auto