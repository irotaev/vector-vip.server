<?php
/**
 * @author: Alexander Nikolaev
 * @email: a.nikolaev@imedia.ru
 */
class MH_Redirect_Advice
{
    protected $_url;

    protected $_newUrl;

    protected $_config;

    public function __construct($url)
    {
        $this->_url = $url;
        $this->_config = \MH\FrontSite::o()->getConfig('expert');
    }

    public function isMatch()
    {
        if ( ($pos = strpos($this->_url, '?')) ) {
            $url = substr($this->_url, 0, $pos);
        } else {
            $url = $this->_url;
        }

        foreach ($this->_config as $config) {
            if (isset($config['redirect'])) {
                if (preg_match('%^' . $config['url'] . '.*$%im', $url)) {
                    $this->_newUrl = $config['redirect'];
                    return true;
                }
            }
        }

        return false;
    }

    public function getUrl()
    {
        if (!$this->isMatch()) {
            return false;
        }
        return $this->_newUrl;
    }
}