<?php

namespace AutoReply\Message;

use AutoReply\Base;

/**
 * Message Provider.
 * For more information, visited https://github.com/plusfriend/auto_reply#62-message.
 *
 * @package kakao
 * @author JJH
 */
class Packet extends Base
{
    /**
     * Text component
     *
     * @var string
     */
    private $text;
    /**

     *
     * @var Message\Photo|null
     */
    private $photo;
    /**
     * MessageButton component
     * If there is no message_button, then it is null.
     *
     * @var Message\MessageButton|null
     */
    private $message_button;

    /**
     * Constructor for Message Provider.
     *
     * @param $text
     * @param array|null $photo
     * @param array|null $message_button
     */
    public function __construct($text, $photo = NULL, $message_button = NULL)
    {
        $this->text = str_replace("\r\n", "\n", $text);
        $this->photo = isset($photo) ? new Message\Photo($photo) : NULL;
        $this->message_button = isset($message_button) ? new Message\MessageButton($message_button) : NULL;
    }

    /**
     * Return array version.
     *
     * @return array
     */
    public function toArray()
    {
        $result = array("text" => $this->text);
        if (isset($this->photo))
            $result["photo"] = $this->photo->toArray();
        if (isset($this->message_button))
            $result["message_button"] = $this->message_button->toArray();
        return $result;
    }

    /**
     * Check that it is valid or not.
     *
     * @return bool
     */
    public function is_valid()
    {
        if (mb_strlen($this->text, 'utf-8') > 1000) {
            $this->invalid_msg = "Maximum text length is 1000.";
            return FALSE;
        }
        if (isset($this->photo) && !$this->photo->is_valid()) {
            $this->invalid_msg = $this->photo->invalid_msg;
            return FALSE;
        }
        if (isset($this->message_button) && !$this->message_button->is_valid()) {
            $this->invalid_msg = $this->message_button->invalid_msg;
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Return the all arguments with formatting.
     *
     * @param int $tab_size tab size
     * @return string
     */
    public function get_argument($tab_size = 0)
    {
        $result = Lib::end_line($tab_size) . "\"" . addslashes($this->text) . "\",";
        $result .= Lib::end_line($tab_size);
        if (isset($this->photo))
            $result .= "array(" . $this->photo->get_argument($tab_size + 1) . Lib::end_line($tab_size) . "),";
        else
            $result .= "NULL,";
        $result .= Lib::end_line($tab_size);
        if (isset($this->message_button))
            $result .= "array(" . $this->message_button->get_argument($tab_size + 1) . Lib::end_line($tab_size) . ")";
        else
            $result .= "NULL";
        return $result;
    }
}