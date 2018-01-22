<?php

namespace AutoReply\Message;

use AutoReply\Base;

/**
 * MessageButton Provider.
 * For more information, visited https://github.com/plusfriend/auto_reply#621-messagebutton.
 *
 * @package kakao
 * @author JJH
 */
class Button extends Base
{
    /**
     * Label text
     *
     * @var string
     */
    private $label;
    /**
     * URL path to move.
     *
     * @var string
     */
    private $url;

    /**
     * Constructor for MessageButton Provider.
     *
     * @param array $message_button
     */
    public function __construct($message_button)
    {
        $this->label = $message_button[0];
        $this->url = $message_button[1];
        $this->invalid_msg = "Invalid url.";
    }

    /**
     * Check that it is valid or not.
     *
     * @return bool
     */
    public function is_valid()
    {
        return !filter_var($this->url, FILTER_VALIDATE_URL) === FALSE;
    }

    /**
     * Return array version.
     *
     * @return array
     */
    public function toArray()
    {
        return array("label" => $this->label, "url" => $this->url);
    }

    /**
     * Return the all arguments with formatting.
     *
     * @param int $tab_size tab size
     * @return string
     */
    public function get_argument($tab_size = 0)
    {
        $result = Lib::end_line($tab_size) . "\"" . addslashes($this->label) . "\",";
        $result .= Lib::end_line($tab_size) . "\"" . addslashes($this->url) . "\"";
        return $result;
    }
}