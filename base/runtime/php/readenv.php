<?php
function env($name, $default=null) {
  return getenv($name, true) ?: $default;
}
function env_as_int($name, $default=null) {
  return intval(env($name, $default));
}
function env_as_bool($name, $default=null) {
  return in_array(strtoupper(env($name, $default)), ['Y', 'YES', 'T', 'TRUE', '1']);
}

function env_moodle_image_version() {
  return 'moodle_image_not_built';
}

function env_ava_image_version() {
  return 'ava_image_not_built';
}

function env_linux_version() {
  $osInfo = parse_ini_file('/etc/os-release');
  return $osInfo['PRETTY_NAME'];
}
