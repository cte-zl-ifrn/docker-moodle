#!/usr/bin/env bash

export TERM=xterm-256color
export COLOR_LIGHT_GREEN='\e[1;32m'
export COLOR_RED='\033[0;31m'
export COLOR_PURPLE='\033[0;35m'
export COLOR_CIAN='\033[0;36m'
export COLOR_YELLOw='\033[0;33m'
export COLOR_NC='\033[0m' # No Color

install_moodle_package() {
    local target_zip="$1"

    if [ ! -f "$target_zip" ]; then
        echo -e "${COLOR_RED}Arquivo '$target_zip' não encontrado.${COLOR_NC}"
        return 1
    fi

    local wwwroot=/var/www/html
    if [ -d "/var/www/html/public" ]; then
        wwwroot=/var/www/html/public
    fi

    # Criar diretório temporário para descompactação
    local STAGE_DIR
    STAGE_DIR=$(mktemp -d /tmp/plugin_stage_XXXXXX)
    trap 'rm -rf "$STAGE_DIR"' RETURN

    unzip -q "$target_zip" -d "$STAGE_DIR"

    # Localizar o arquivo version.php principal do plugin (mais raso na árvore de diretórios)
    local version_file
    version_file=$(find "$STAGE_DIR" -name "version.php" | awk -F'/' '{print NF, $0}' | sort -n | cut -d' ' -f2- | head -n 1)

    local plugin_component=""
    local plugin_version=""

    if [ -n "$version_file" ]; then
        # Executar PHP CLI alterando o diretório de trabalho para o diretório do version.php
        local php_output
        php_output=$(php -r '
            define("MOODLE_INTERNAL", true);
            $CFG = new stdClass();
            $plugin = new stdClass();
            $module = new stdClass();
            $vfile = $argv[1] ?? "";
            if (file_exists($vfile)) {
                @chdir(dirname($vfile));
                @include $vfile;
            }
            $comp = "";
            if (is_object($plugin) && !empty($plugin->component)) { $comp = $plugin->component; }
            else if (is_array($plugin) && !empty($plugin["component"])) { $comp = $plugin["component"]; }
            else if (is_object($module) && !empty($module->component)) { $comp = $module->component; }
            else if (is_array($module) && !empty($module["component"])) { $comp = $module["component"]; }

            $ver = "";
            if (is_object($plugin) && !empty($plugin->version)) { $ver = $plugin->version; }
            else if (is_array($plugin) && !empty($plugin["version"])) { $ver = $plugin["version"]; }
            else if (is_object($module) && !empty($module->version)) { $ver = $module->version; }
            else if (is_array($module) && !empty($module["version"])) { $ver = $module["version"]; }

            echo $comp . ":::" . $ver;
        ' "$version_file" 2>/dev/null)

        if [ -n "$php_output" ]; then
            plugin_component=$(echo "$php_output" | awk -F':::' '{print $1}')
            plugin_version=$(echo "$php_output" | awk -F':::' '{print $2}')
        fi

        # Fallback com grep/sed caso PHP CLI não retorne o componente
        if [ -z "$plugin_component" ]; then
            plugin_component=$(grep -E "\$(plugin|module)(->|\[['\"])component" "$version_file" | head -n 1 | sed -E "s/.*=\s*['\"]([^'\"]+)['\"].*/\1/")
        fi

        # Fallback com grep/sed caso PHP CLI não retorne a versão
        if [ -z "$plugin_version" ]; then
            plugin_version=$(grep -E "\$(plugin|module)(->|\[['\"])version" "$version_file" | head -n 1 | sed -E "s/.*=\s*['\"]?([0-9a-zA-Z._-]+)['\"]?.*/\1/" | tr -cd '0-9.a-zA-Z_-')
        fi
    fi

    # Se a versão não foi obtida do version.php, tenta extrair do nome do arquivo zip
    if [ -z "$plugin_version" ]; then
        local filename
        filename=$(basename "$target_zip")
        local filename_no_ext="${filename%.zip}"
        plugin_version=$(echo "$filename_no_ext" | sed -nE '
            s/.*[_.-]moodle[0-9]*[_.-]([0-9a-zA-Z._-]+)$/\1/p; t
            s/.*[-_]([0-9]{4}[_-][0-9]{2}[_-][0-9]{2}[_-][0-9]+)$/\1/p; t
            s/.*[-_]([0-9]{8,14})$/\1/p; t
            s/.*[-_]([0-9]+\.[0-9]+(\.[0-9]+)?)$/\1/p
        ' | head -n 1)
    fi

    # Se não foi possível obter o componente pelo version.php, faz fallback pelo nome do arquivo
    local plugin_type=""
    local plugin_name=""
    if [ -z "$plugin_component" ]; then
        local filename
        filename=$(basename "$target_zip")
        local filename_no_ext="${filename%.zip}"
        local clean_name
        clean_name=$(echo "$filename_no_ext" | sed -E '
            s/[_.-]moodle[0-9]*[_.-].*//g
            s/[-_][0-9]{4}[_-][0-9]{2}[_-][0-9]{2}[_-][0-9]+$//g
            s/[-_][0-9]{8,14}$//g
            s/[-_][0-9]+\.[0-9]+(\.[0-9]+)?$//g
        ')
        plugin_type=$(echo "$clean_name" | cut -d '_' -f1)
        plugin_name=$(echo "$clean_name" | cut -d '_' -f2-)
        plugin_component="${plugin_type}_${plugin_name}"
    else
        plugin_type=$(echo "$plugin_component" | cut -d '_' -f1)
        plugin_name=$(echo "$plugin_component" | cut -d '_' -f2-)
    fi

    local dir=''
    case $plugin_type in
        'auth')             dir=$wwwroot/auth ;;
        'mod')              dir=$wwwroot/mod ;;
        'block')            dir=$wwwroot/blocks ;;
        'booktool')         dir=$wwwroot/mod/book/tool ;;
        'filter')           dir=$wwwroot/filter ;;
        'atto')             dir=$wwwroot/lib/editor/atto/plugins ;;
        'tiny')             dir=$wwwroot/lib/editor/tiny/plugins ;;
        'enrol')            dir=$wwwroot/enrol ;;
        'tool')             dir=$wwwroot/admin/tool ;;
        'availability')     dir=$wwwroot/availability/condition ;;
        'qformat')          dir=$wwwroot/question/format ;;
        'qtype')            dir=$wwwroot/question/type ;;
        'qbehaviour')        dir=$wwwroot/question/behaviour ;;
        'report')           dir=$wwwroot/report ;;
        'gradereport')      dir=$wwwroot/grade/report ;;
        'gradeexport')      dir=$wwwroot/grade/export ;;
        'gradeimport')      dir=$wwwroot/grade/import ;;
        'format')           dir=$wwwroot/course/format ;;
        'theme')            dir=$wwwroot/theme ;;
        'local')            dir=$wwwroot/local ;;
        'profilefield')     dir=$wwwroot/user/profile/field ;;
        'customfield')      dir=$wwwroot/customfield/field ;;
        'lang')             dir=$wwwroot/lang ;;
        'repository')       dir=$wwwroot/repository ;;
        'portfolio')        dir=$wwwroot/portfolio ;;
        'editor')           dir=$wwwroot/lib/editor ;;
        'contentbank')      dir=$wwwroot/contentbank/provider ;;
        'assignsubmission') dir=$wwwroot/mod/assign/submission ;;
        'assignfeedback')   dir=$wwwroot/mod/assign/feedback ;;
        'quiz')             dir=$wwwroot/mod/quiz/accessrule ;;
        'quizaccess')       dir=$wwwroot/mod/quiz/accessrule ;;
        'cachestore')       dir=$wwwroot/cache/stores ;;
        'cachelock')        dir=$wwwroot/cache/locks ;;
        'antivirus')        dir=$wwwroot/lib/antivirus ;;
        'media')            dir=$wwwroot/media/player ;;
        'factor')           dir=$wwwroot/admin/tool/mfa/factor ;;
        'communication')    dir=$wwwroot/communication/provider ;;
        'customcertelement') dir=$wwwroot/mod/customcert/element ;;
        'logstore')          dir=$wwwroot/admin/tool/log/store ;;
        'datafield')         dir=$wwwroot/mod/data/field ;;
        'datapreset')        dir=$wwwroot/mod/data/preset ;;
        *)
            echo "Tipo de plugin ($plugin_type) não identificado para o componente '$plugin_component'."
            return 1
            ;;
    esac

    local formatted_version
    formatted_version=$(printf "%-11s" "${plugin_version:-N/A}")
    echo -e "${COLOR_RED}${plugin_type}${COLOR_NC}:${COLOR_LIGHT_GREEN}${plugin_name}${COLOR_NC}:${COLOR_CIAN}${formatted_version}${COLOR_NC}/${COLOR_PURPLE}$target_zip${COLOR_NC}>${COLOR_YELLOw}$dir/$plugin_name${COLOR_NC}"


    local src_dir
    if [ -n "$version_file" ]; then
        src_dir=$(dirname "$version_file")
    else
        local subdirs=("$STAGE_DIR"/*/)
        if [ ${#subdirs[@]} -eq 1 ] && [ -d "${subdirs[0]}" ]; then
            src_dir="${subdirs[0]}"
        else
            src_dir="$STAGE_DIR"
        fi
    fi

    mkdir -p "$dir/$plugin_name/"
    cp -rf "$src_dir/." "$dir/$plugin_name/"
}

# Se nenhum argumento for passado, busca todos os arquivos *.zip no diretório atual
if [ $# -eq 0 ]; then
    set -- *.zip
fi

for zip_file in "$@"; do
    if [ -f "$zip_file" ]; then
        install_moodle_package "$zip_file"
    fi
done
