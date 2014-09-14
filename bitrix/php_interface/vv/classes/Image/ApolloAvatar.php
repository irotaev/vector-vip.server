<?php
\CO\FrontSite::o()->autoload('CO_Image_Interface');
/**
 * Description of CO_Image_ApolloAvatar
 *
 * @property-read $url
 *
 * @author dmitriy
 */
class CO_Image_ApolloAvatar implements CO_Image_Interface
{
    // картинка-источник
    protected $_baseImage = '';
    
    protected $_suff = '';

    public function __construct($image = '') {
        if (preg_match('~(.*plgo)_b\d+\.jpg$~', $image, $m) || preg_match('~(.*?\.jpg)(_.*\.jpg)?$~', $image, $m)) {
            $this->_baseImage = $m[1];
        }
    }

    public function __get($name) {
        switch($name) {
            case 'url':
                return $this->getUrl();
        }
    }

    public function isExists()
    {
        return $this->_baseImage != '';
    }

    /**
     *
     * @param int $width
     * @param int $height
     * @return CO_Image_ApolloAvatar
     */
    public function crop($width, $height)
    {
        if ($width == $height) {
            $this->_suff = '_b'.$width.'.jpg';
        } else {
            $this->_suff = '_s'.($width > $height ? $height : $width).'.jpg';;
        }
        return $this;
    }

    /**
     *
     * @return CO_Image_ApolloAvatar
     */
    public function quality($quality = null) {
        // не работает
        return $this;
    }

    public function getUrl()
    {
        if (!$this->isExists()) {
            return '';
        }
        return $this->_baseImage . $this->_suff;
    }
}