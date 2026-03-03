<?php
if (!defined('NO_MOODLE_COOKIES')) {
    define('NO_MOODLE_COOKIES', true);
}

// Force stateless probe execution even when client sends Moodle cookies.
$_COOKIE = [];
unset($_SERVER['HTTP_COOKIE']);

require_once '../readenv.php';

class readiness_checker {

    private $accept = 'not_set';
    private $checks = [];

    function needs($sufix) {
        return env_as_bool("CFG_PROBES_$sufix", false);
    }

    function check_status($check_value) {
        if (is_string($check_value)) {
            return $check_value;
        } elseif (is_null($check_value)) {
            return '↪️';
        } else {
            return $check_value ? "✅" : "❌";
        }
    }

    function add_check($label, $check) {
        $this->checks[] = [$label, $check];
    }

    function try_check($label, $callback_or_value) {
        try {
            if (is_callable($callback_or_value)) {
                $this->add_check($label, $callback_or_value());
            } else {
                $this->add_check($label, '$callback_or_value');
            };
        } catch (dml_connection_exception $e) {
            $this->add_check($label, false);
        }
    }

    private function check_ws() {
        if ($this->needs('WEBSERVICES')) {
            try {
                require_once($CFG->libdir . '/adminlib.php');
                $enabled_protocols = array_filter(core_component::get_plugin_list('webservice'), function($plugin) {return get_config('webservice_' . $plugin, 'enabled');});
                $enabled_protocols_str = $enabled_protocols ? '<ol><li>' . implode('</li><li>', array_keys($enabled_protocols)) . '</ol>' : '';

                $this->add_check("Webservices ativos",                                          is_enabled_auth('webservice'));
                $this->add_check("Tem algum protocolo Webservice ativo$enabled_protocols_str",  !empty($enabled_protocols));
            } catch (\Throwable $th) {
                $this->add_check("Webservices ativos",                       null);
                $this->add_check("Tem algum protocolo Webservice ativo",     null);
            }
        } else {
            $this->add_check("Webservices ativos", null);
        }
    }

    private function check_dbsync() {
        if ($this->needs('DBSYNC')) {
            global $DB;
            $dbmanager = $DB->get_manager();
            $schema = $dbmanager->get_install_xml_schema();
            $errors = $dbmanager->check_database_schema($schema);
            $errors_exploteds = '<ol>';
            foreach ($errors as $table => $items) {
                $errors_exploteds .= "<li><b>$table</b><ol>";
                foreach ($items as $item) {
                    $errors_exploteds .= "<li>$item</li>";
                }
                $errors_exploteds .= "</ol></li>";
            }
            $errors_exploteds .= "</ol>";
            $this->add_check("Esquema do banco de dados$errors_exploteds", null);
        } else {
            $this->add_check("Esquema do banco de dados", null);
        }
    }


    public function authorize() {
        $this->accept = explode(',', strtolower(str_replace(   ' ', '', $_SERVER['HTTP_ACCEPT'])))[0];
        header("Content-Type: $this->accept");

        if (env('CFG_PROBES_TOKEN') !== $_GET['token']) {
            echo "Não autorizado!\n" ;
            http_response_code(403);
            exit();
        }
    }

    public function checks() {
        global $CFG;
        $this->add_check("Versão de build do Moodle",           env_moodle_image_version());
        $this->add_check("Versão de build do AVA",              env_ava_image_version());
        $this->add_check("Versão do Linux",                     env_linux_version());
        $this->add_check("Limite de tempo de execução",         ini_get('max_execution_time'));
        $this->add_check("Limite de memória",                   ini_get('memory_limit'));
        $this->add_check("Tamanho máximo do post",              ini_get('post_max_size'));
        $this->add_check("Tamanho máximo do arquivo no upload", ini_get('upload_max_filesize'));
        if (file_exists("/var/www/moodledata/climaintenance.html")) {
            $this->add_check("Modo de manutenção inativo", false);
            return;
        } else {
            $this->add_check("Modo de manutenção inativo", true);
        }
        $this->try_check("Config.php lido com sucesso", function() {
            require_once '../config.php';
            if (class_exists('\\core\\session\\manager')) {
                \core\session\manager::write_close();
            }
            return true;
        });
        $this->add_check("Debug inativo",                       $this->needs('DEBUG')            ? !$CFG->debug                  : null);
        $this->add_check("Theme designer mode inativo",         $this->needs('DEBUG')            ? !$CFG->themedesignermode      : null);
        $this->add_check("Cache JS inativo",                    $this->needs('DEBUG')            ? $CFG->cachejs != 0            : null);
        $this->add_check("Debug display inativo",               $this->needs('DEBUG')            ? !$CFG->debugdisplay != 0      : null);
        $this->add_check("Cronjob ativo",                       $this->needs('CRONJOB')          ? $CFG->cron_enabled != 0       : null);
        $this->add_check("Cronjob restrito ao CLI",             $this->needs('CRONJOB')          ? $CFG->cronclionly != 0        : null);
        $this->add_check("Backup automático ativo",             $this->needs('AUTOMATIC_BACKUP') ? $CFG->backup_auto_active != 0 : null);
        $this->add_check("Estatísticas ativas",                 $this->needs('STATISCTICS')      ? $CFG->enablestats != 0        : null);
        $this->add_check("Sessão usando Redis",                 $this->needs('SESSION_REDIS')    ? null                          : null);
        $this->add_check("Cache usando Redis",                  $this->needs('CACHE_REDIS')      ? null                          : null);
        $this->add_check("Tasks sem falha",                     $this->needs('TASKS')            ? null                          : null);
        $this->add_check("Cronjob executando a cada minuto",    $this->needs('CRONJOB')          ? null                          : null);
        $this->try_check("Analíticas ativas",                   function() {global $CFG; if (!$this->needs('ANALYTICS')){return null;}; require_once($CFG->dirroot . '/analytics/classes/manager.php'); return core_analytics\manager::is_analytics_enabled();});
        $this->try_check("Banco de dados conectando",           function() {global $DB; return $DB->count_records_sql('SELECT 1') == 1;});
        $this->check_ws();
        $this->check_dbsync();

    }

    public function render() {
        $tudo_ok = true;
        ob_start();
        if ($this->accept == "text/plain") {
            echo "AVA health checker: $this->accept\n";
            foreach ($this->checks as $check) {
                $tudo_ok = $tudo_ok && $check[1];
                $status = $check[1] ? "OK" : "FAIL";
                echo "$check[0]: $status\n";
            }
            $status = $tudo_ok ? "ALL FINE" : "SOME FAILS";
            echo "Status geral: $status\n";
            http_response_code($tudo_ok ? 200 : 510);
        } else {
            echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">';
            echo '<style>body{padding: 2em;}</style>';
            echo "<div style='max-width: 600px; margin: auto;'>";
            echo "<h1 style='text-align: center; margin-bottom: 1em;'>AVA health checker: $this->accept</h1>";
            echo "<table class='table table-striped table-hover'>";
            echo "<thead><tr><th>Check</th><th>Status</th></tr></thead>";
            echo "<tbody class='table-group-divider'>";
            foreach ($this->checks as $check) {
                $check_status = $this->check_status($check[1]);
                echo "<tr><td>$check[0]</td><td>$check_status</td></tr>";
                $tudo_ok = $tudo_ok && $check_status != '❌';
            }
            $status_geral = $tudo_ok ? "✅" : "❌";
            echo "</tbody>";
            echo "<tfoot class='table-group-divider'><tr><td>Status geral</td><td>$status_geral</td></tr></tfoot>";
            echo "</table>";
            echo "</div>";
            http_response_code($tudo_ok ? 200 : 510);
        }
        ob_flush();
    }

    public function execute() {
        $this->authorize();
        $this->checks();
        $this->render();
    }
}

(new readiness_checker())->execute();