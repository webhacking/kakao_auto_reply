<?php

use AutoReply\Lib;
use AutoReply\Keybord;
use AutoReply\Provider\Routing\Router;

if ( !Lib::is_installed() && Router::segment(3) !== 'install.php' ) {
    Lib::view('install');
}

if ( !Lib::ip_check() ) {
    Lib::show_error(403, "Not allowed ip!!");
}

if ( !Router::segment(0) ) {
    Lib::view('login');
}

switch ( Router::segment(0) ) {
    case "keyboard":
        if ( $method !== "GET" ) {
            exit("INVALID METHOD");
        }

        header("Content-Type: application/json; charset=utf-8");
        echo new Keyboard($DEFAULT_KEYBOARD);

        break;
    case "message":
        if ( $method !== "POST" ) {
            exit("INVALID METHOD");
        }

        header("Content-Type: application/json; charset=utf-8");
        $raw_post_data = file_get_contents("php://input");
        $post_data = json_decode($raw_post_data);

        $content = $post_data->content;
        $type = $post_data->type;
        pre_message_receive($post_data);

        if ( $type === "text" ) {
            $file_path = get_message_file($content);
            if ( file_exists($file_path) ) {
                include_once $file_path;
            } else {
                undefined_msg_operation($content);
            }
        } else {
            msg_media_upload();
        }

        post_message_receive($post_data);
        break;
    case "friend":
        if ($method === "POST") {
            $post_data = json_decode(file_get_contents("php://input"));
            add_friend($post_data->user_key);
        } else if ($method === "DELETE") {
            delete_friend(Router::segment(1));
        } else {
            exit("INVALID METHOD");
        }
        break;
    case "chat_room":
        if ($method === "DELETE") {
            delete_chat_room(Router::segment(1));
        } else {
            exit("INVALID METHOD");
        }
        break;
    default:
        exit("UNKNOWN REQUEST");
}