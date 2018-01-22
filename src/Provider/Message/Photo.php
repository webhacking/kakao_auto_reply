<?php

namespace AutoReply\Message;

use AutoReply\Base;

/**
 * Photo Provider.
 * For more information, visited https://github.com/plusfriend/auto_reply#63-photo.
 *
 * @package kakao
 * @author JJH
 */
class Photo extends Base
{
    /**
     * Photo url
     *
     * @var string
     */
    private $url;
    /**
     * Photo width
     *
     * @var int
     */
    private $width;
    /**
     * Photo height
     *
     * @var int
     */
    private $height;

    /**
     * Constructor for Photo Provider.
     *
     * @param array $photo
     */
    public function __construct($photo)
    {
        $this->url = $photo[0];
        $this->width = intval($photo[1]);
        $this->height = intval($photo[2]);
        $this->invalid_msg = "Only jpeg, png are supported.";
    }

    /**
     * Check that it is valid or not.
     *
     * @return bool
     */
    public function is_valid()
    {
        if (strpos($this->url, 'http') === 0 && !ini_get('allow_url_fopen')) {
            $ext = strtolower(substr($this->url, strrpos($this->url, '.') + 1));
            return in_array($ext, array("png", "jpg", "jpeg"));
        }
        $size = @getimagesize($this->url);
        if ($size === false) {
            $this->invalid_msg = "Invalid photo url.";
            return false;
        }
        $ALLOWED_MIME = array("image/png", "image/jpeg");
        return in_array($size['mime'], $ALLOWED_MIME);
    }

    /**
     * Return array version.
     *
     * @return array
     */
    public function toArray()
    {
        return array("url" => $this->url, "width" => $this->width, "height" => $this->height);
    }

    /**
     * Return the all arguments with formatting.
     *
     * @param int $tab_size tab size
     * @return string
     */
    public function get_argument($tab_size = 0)
    {
        $result = Lib::end_line($tab_size) . '"' . addslashes($this->url) . "\",";
        $result .= Lib::end_line($tab_size) . $this->width . ',';
        $result .= Lib::end_line($tab_size) . $this->height;
        return $result;
    }
}