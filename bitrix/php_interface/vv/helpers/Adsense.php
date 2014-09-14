<?php
require_once 'Abstract.php';
/**
 * Description of Text
 *
 * @author dmitriy
 */
class MH_HelperAdsense extends MH_HelperAbstract
{
    protected $_isForbidden;
    
    public function __construct($config) {
        parent::__construct($config);
        
        $url = $_SERVER['REQUEST_URI'];
        if ( ($pos = strpos($url, '?')) ) {
            $url = substr($url, 0, $pos);
        }
        
        $this->_isForbidden = false;
        foreach($this->_config['forbidden'] as $rule) {
            if (preg_match($rule, $url)) {
                $this->_isForbidden = true;
                break;
            }
        }
        
    }
    
    function isForbidden ()
    {
        return $this->_isForbidden;
    }
    

}
