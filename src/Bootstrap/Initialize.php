<?php

use AutoReply\Lib;
use AutoReply\Provider\Router;


$config_file = json_decode(file_get_contents(__DIR__."/../../config.json"), true);

foreach ( $config_file as $config_key => $config_item ) {
    define(strtoupper($config_key), $config_item);
}

require __DIR__ . '/../Lib.php';

if ( !Lib::is_installed() && Router::segment(3) !== 'install.php' ) {
//    exit('Need to install! <a href="/src/Public/install.php">Install</a>');
}

require __DIR__.'/../router.php';