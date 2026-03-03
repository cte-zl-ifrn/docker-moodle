<?php
define('CLI_SCRIPT', true);
define('CACHE_DISABLE_ALL', true);

$connected = false;
$i = 1;
$original_debug = getenv('CFG_DEBUG');
$original_timeout = getenv('CFG_CONNECTTIMEOUT');
putenv("CFG_DEBUG=false");
putenv("CFG_CONNECTTIMEOUT=1");
while (!$connected) {
    echo "Tentando conectar: $i ... ";
    try {
        require_once('/var/www/html/config.php');
        $DB->get_record_sql('SELECT now()');
        $connected = true;
        echo "CONECTADO.\n";
    } catch (Exception $e) {
        echo "NÃO CONECTADO.\n";
        sleep(3);
        $i++;
    }
}
putenv("CFG_DEBUG=$original_debug");
putenv("CFG_CONNECTTIMEOUT=$original_timeout");
