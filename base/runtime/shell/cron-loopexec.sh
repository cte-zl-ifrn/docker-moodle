while true; do 
    sleep 60
    /usr/local/bin/php /var/www/html/admin/cli/cron.php >> /var/log/moodle/cron.log & 
done
