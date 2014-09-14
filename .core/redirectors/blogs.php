<?php

class MH_Redirect_Blogs
{
    protected $_url;
    
    protected $_newUrl;
    
    public function __construct($url) {
        $this->_url = $url;        
    }
    
    public function isMatch()
    {
        
        $url = $this->_url;        
        
        if (!preg_match('~^/blog/redjul/(.+)?~', $url, $m)) {
            return false;
        }
       
        $this->_newUrl = 'http://www.mhadventure.ru/blog/redjul/'.$m[1];
        return true;
        
    }
    
    public function getUrl()
    {
        if (!$this->isMatch()) {
            return false;
        }
        return $this->_newUrl;
    }
    
}