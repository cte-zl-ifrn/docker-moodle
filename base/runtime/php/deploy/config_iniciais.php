<?php
// Ative o debug para ver mensagens de erro
// @link https://docs.moodle.org/dev/Debugging#CLI_debugging
error_reporting(E_ALL | E_STRICT); // NOT FOR PRODUCTION SERVERS!
ini_set('display_errors', 'stderr'); // NOT FOR PRODUCTION SERVERS!


define('CLI_SCRIPT', true);
require_once('/var/www/html/config.php');
require_once($CFG->dirroot . '/lib/blocklib.php');
require_once($CFG->dirroot . '/admin/tool/langimport/classes/controller.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/tablelib.php');



function inicial_set_defaults() {
    global $DB;

    $defaults = [
        '|lang'=>'pt_br',
        '|theme'=>'boost',
        '|frontpage'=>'',
        '|frontpageloggedin'=>'',
        '|defaultpreference_maildisplay'=>'0',
        '|defaultpreference_mailformat'=>'0',
        '|defaultpreference_maildigest'=>'2',
        '|defaultpreference_autosubscribe'=>'1',
        '|defaultpreference_trackforums'=>'0',
        '|defaultpreference_core_contentbank_visibility'=>'1',
        '|noreplyaddress'=>'admin@local.host',
        'moodlecourse|visible'=>'1',
    ];
    foreach ($defaults as $key => $default_value) {
        list($plugin, $config) = explode('|', $key, 2);
        $plugin = empty($plugin) ? null : $plugin;
        $venv = strtoupper("INICIAL_CONFIG_{$plugin}_{$config}");
        set_config($config, env($venv, $default_value), $plugin);
    }

    $DB->update_record('user', (object) ['lang' => env('INICIAL_CONFIG_LANG', 'pt_br'), 'id' => 2]);   
}

function inicial_add_default_blocks() {
    try {
        # Example: site_info:content-pre;course_gallery:content-pre
        $blocks = env('INICIAL_ADD_DEFAULT_BLOCKS', '');
        if ($blocks == '') {
            return;
        }

        $context = context_system::instance();
        $page = new moodle_page();
        $page->set_context($context);
        $page->set_pagelayout('frontpage');
        $page->set_pagetype('site-index');
        $page->set_url(new moodle_url('/'));
        $courserenderer = $page->get_renderer('core', 'course');
        foreach (explode(';', $blocks) as $parts) {
            list($block, $region) = explode(':', $parts, 2);
            $page->blocks->is_block_present($block);
            if (!$page->blocks->is_block_present($block)) {
            // if (!in_array($block, $installed_block_names)) {
                $page->blocks->add_block($block, $region, 0, false, 'site-index');
            }
        }
    } catch (\Throwable $th) {
        echo "ERRO: $th";
    }
}

function inicial_add_default_langs() {
    $langs = env('INICIAL_ADD_DEFAULT_LANGS', 'pt_br');
    if ($langs == '') {
        return;
    } 
    $controller = new \tool_langimport\controller();
    $controller->install_languagepacks(explode(',', $langs));
    get_string_manager()->reset_caches();
}


function inicial_create_or_update($tablename, $keys, $allways, $updates = [], $insert = []) {
    global $DB;
    $record = $DB->get_record($tablename, $keys);
    if ($record) {
        foreach (array_merge($keys, $allways, $updates) as $attr => $value) {
            $record->{$attr} = $value;
        }
        $DB->update_record($tablename, $record);
    } else {
        $record = (object)array_merge($keys, $allways, $insert);
        $record->id = $DB->insert_record($tablename, $record);
    }
    return $record;
}


function inicial_oauth2() {
    $defaults = [
        'clientid' => '(from suap)',
        'clientsecret' => '(from suap)',
        'basicauth' => 0,
        'baseurl' => 'https://suap.ifrn.edu.br',
        'image' => 'https://ead.ifrn.edu.br/portal/wp-content/uploads/2020/08/SUAP.png',
        'showonloginpage' => 2,
        'loginpagename' => 'SUAP',
        'loginscopes' => 'identificacao email documentos_pessoais',
        'loginscopesoffline' => 'identificacao email documentos_pessoais',
        'loginparams' => '',
        'loginparamsoffline' => '',
        'alloweddomains' => '',
        'requireconfirmation' => 0,
        'enabled' => 1,
        'sortorder' => 0,
    ];

    foreach ($defaults as $key => $value) {
        $venv = strtoupper("INICIAL_OAUTH2_{$key}");
        $defaults[$key] = env($venv, $value);
    }

    $issuer = inicial_create_or_update(
        'oauth2_issuer',
        ['name' => 'suap'],
        $defaults,
        ['timemodified' => time()],
        ['timecreated'=>time(), 'timemodified'=>time(), 'usermodified' => 2]
    );

    $endpoints = explode('|', env("INICIAL_OAUTH2_ENDPOINTS", 'userinfo_endpoint=https://suap.ifrn.edu.br/api/rh/eu/|token_endpoint=https://suap.ifrn.edu.br/o/token/|authorization_endpoint=https://suap.ifrn.edu.br/o/authorize/'));
    foreach ($endpoints as $endpoint) {
        list($key, $value) = explode('=', $endpoint, 2);
        inicial_create_or_update(
            'oauth2_endpoint', 
            ['issuerid'=> $issuer->id, 'name'=>$key],
            ["url"=>$value],
            ['timemodified'=>time()],
            ['timecreated'=>time(), 'timemodified'=>time(), 'usermodified'=>'2']
        );
    }

    $mappings = explode('|', env("INICIAL_OAUTH2_USERFIELDMAPPINGS", 'identificacao=username|ultimo_nome=lastname|primeiro_nome=firstname|email_preferencial=email'));
    foreach ($mappings as $endpoint) {
        list($key, $value) = explode('=', $endpoint, 2);
        inicial_create_or_update(
            'oauth2_user_field_mapping', 
            ['issuerid'=> $issuer->id, 'externalfield'=>$key],
            ["internalfield"=>$value],
            ['timemodified'=>time()],
            ['timecreated'=>time(), 'timemodified'=>time(), 'usermodified' => 2]
        );
    }
}

if (get_config('moodle', 'inicial_has_setted') != '1') {
    set_config('inicial_has_setted', '1');
    inicial_set_defaults();
    inicial_add_default_langs();
    inicial_add_default_blocks();
    inicial_oauth2();
    echo "Configurações iniciais aplicadas.\n";
} else {
    echo "Configurações iniciais já haviam sido aplicadas.\n";
}
