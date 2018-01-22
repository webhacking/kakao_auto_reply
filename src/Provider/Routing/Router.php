<?php

namespace AutoReply\Provider\Routing;

/**
 * Class Routing
 * @package AutoReply\Provider
 */
abstract class Router
{
    /**
     * @param $num
     * @return mixed
     */
    public static function segment($num)
    {
        if ( !array_key_exists($num, explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) ) {
            return "";
        }

        return explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))[$num];
    }
}