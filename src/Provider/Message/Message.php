<?php

namespace AutoReply\Provider;

use AutoReply\Base;
use AutoReply\Message\Packet;
use AutoReply\Provider\Keyboard;
use AutoReply\Lib;

/**
 * Msg Provider.
 * This Provider is for showing response about /message
 *
 * @package kakao
 * @author JJH
 */
class Message extends Base
{
    /**
     * @var Packet
     */
    private $message;

    /**
     * Keyboard type
     *
     * @var Keyboard
     */
    private $keyboard;

    /**
     * Is default keyboard or not.
     *
     * @var bool
     */
    private $use_default_keyboard = FALSE;

    /**
     * Constructor for Msg Provider.
     *
     * keyboard : null -> Subjective.
     * keyboard : TRUE -> Use default keyboard.
     * keyboard : array -> Use keyboard elements.
     *
     * @param \AutoReply\Message\Packet $message
     * @param mixed $keyboard
     */
    public function __construct(Packet $message, $keyboard = NULL)
    {
        $this->message = $message;
        if ( $keyboard !== TRUE ) {
            $this->keyboard = $keyboard;
        }  else {
            include_once __DIR__ . '/../config.php';
            include_once __DIR__ . '/Keyboard.php';

            $this->keyboard = new Keyboard($GLOBALS['DEFAULT_KEYBOARD']);
            $this->use_default_keyboard = TRUE;
        }
    }

    /**
     * Check that it is valid or not.
     *
     * @return bool
     */
    public function is_valid()
    {
        if (!$this->message->is_valid()) {
            $this->invalid_msg = $this->message->invalid_msg;
            return FALSE;
        }
        if (isset($this->keyboard) && !$this->keyboard->is_valid()) {
            $this->invalid_msg = $this->keyboard->invalid_msg;
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Return array version.
     *
     * @return array
     */
    public function toArray()
    {
        $result['message'] = $this->message->toArray();
        if (isset($this->keyboard) && $this->keyboard->is_objective())
            $result['keyboard'] = $this->keyboard->toArray();
        return $result;
    }

    /**
     * Return the all arguments with formatting.
     *
     * @param int $tab_size tab size
     * @return string
     */
    public function get_argument($tab_size = 0)
    {
        $result = Lib::end_line($tab_size) . "new Message(";
        $result .= $this->message->get_argument($tab_size + 1);
        $result .= Lib::end_line($tab_size) . "),";

        if ( $this->use_default_keyboard === TRUE ) {
            $result .= Lib::end_line($tab_size) . "TRUE";
        } elseif ( isset($this->keyboard) ) {
            $result .= $this->keyboard->get_class($tab_size);
        }

        return $result;
    }

    /**
     * Return php version of Provider with formatting.
     *
     * @param int $tab_size tab size
     * @return string
     */
    public function get_class($tab_size = 0)
    {
        return "new Msg(" . $this->get_argument($tab_size + 1) . Lib::end_line($tab_size) . ")";
    }
}

