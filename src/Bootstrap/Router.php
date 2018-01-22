<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */

use AutoReply\Lib;
use AutoReply\Keybord;
use AutoReply\Message;
use AutoReply\Message\Packet;


$uri = isset($_GET['id']) ? $_GET['id'] : '';
$uri = explode('/', $uri);
$method = $_SERVER['REQUEST_METHOD'];


if ( $uri[0] == '' ) {
    Lib::view('login');
}

if ( !Lib::ip_check() ) {
    Lib::show_error(403, "Not allowed ip!!");
}

exit;
switch ( $uri[0] ) {
    case "keyboard":
        if ($method !== "GET")
            exit("INVALID METHOD");
        header("Content-Type: application/json; charset=utf-8");
        echo new Keyboard($DEFAULT_KEYBOARD);

        break;
    case "message":
        if ($method !== "POST")
            exit("INVALID METHOD");
        header("Content-Type: application/json; charset=utf-8");
        $raw_post_data = file_get_contents("php://input");
        $post_data = json_decode($raw_post_data);

        $content = $post_data->content;
        $type = $post_data->type;
        pre_message_receive($post_data);
        if ($type === "text") {
            $file_path = get_message_file($content);
            if (file_exists($file_path))
                include_once $file_path;
            else
                undefined_msg_operation($content);
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
            delete_friend($uri[1]);
        } else {
            exit("INVALID METHOD");
        }
        break;
    case "chat_room":
        if ($method === "DELETE") {
            delete_chat_room($uri[1]);
        } else {
            exit("INVALID METHOD");
        }
        break;
    default:
        exit("UNKNOWN REQUEST");
}