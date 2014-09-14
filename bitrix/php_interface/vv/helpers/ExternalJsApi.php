<?php
require_once 'Abstract.php';
/**
 * 
 *
 * @author dmitriy
 */
class MH_HelperExternalJsApi extends MH_HelperAbstract
{
    private $_included = array();
    
    public function turnOn($apiName)
    {
        if (isset($this->_config[$apiName])) {
            if (!in_array($apiName, $this->_included)) {
                $this->_included[] = $apiName;
            }
        } else {
            throw new Exception('Unknown external javascript API name');
        }
        return $this;
    }
    
    public function showHeadStrings()
    {
        $GLOBALS['APPLICATION']->AddBufferContent(array($this, 'getStrings'), 'head');
    }
    
    public function showTopBodyScripts()
    {
        $GLOBALS['APPLICATION']->AddBufferContent(array($this, 'getStrings'), 'top-body');
    }
    
    public function showBottomBodyScripts()
    {
        $GLOBALS['APPLICATION']->AddBufferContent(array($this, 'getStrings'), 'bottom-body');
    }
    
    public function getStrings($pos)
    {
        $str = '';
        foreach($this->_included as $apiName) {
            if (isset($this->_config[$apiName][$pos])) {
                $str .= $this->_config[$apiName][$pos];
            }
        }
        return $str;
    }
}