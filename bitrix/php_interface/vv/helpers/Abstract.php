<?php
/**
 * Description of Abstract
 *
 * @author dmitriy
 */
abstract class MH_HelperAbstract
{
    protected $_config = array();
    
    public function __construct(array $config = array()) {
        $this->_config = $config;
    }
    
    public function getConfig()
    {
        return $this->_config;
    }
}
