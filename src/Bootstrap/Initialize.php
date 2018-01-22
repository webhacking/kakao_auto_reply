<?php

use AutoReply\Lib;

$config_file = json_decode(file_get_contents(__DIR__."/../../config.json"), true);

foreach ( $config_file as $config_key => $config_item ) {
    define(strtoupper($config_key), $config_item);
}

if ( DEBUG ) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}

define("MESSAGE_PATH", __DIR__ .'/../../resource/msg');
require __DIR__ . '/Router.php';