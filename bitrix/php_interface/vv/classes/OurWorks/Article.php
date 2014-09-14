<?php

class VV_Articles_Article
{
    private $_id;
    private $_name;
    private $_detailText;
    private $_dateActiveFrom;
    private $_previewText;
    private $_code;
    
    public function __construct(array $init = array()) 
   {
        $this->_id = isset($init['id']) ? $init['id'] : '';
        $this->_name = isset($init['name']) ? $init['name'] : '';
        $this->_detailText = isset($init['detailText']) ? $init['detailText'] : '';
        $this->_dateActiveFrom = isset($init['dateActiveFrom']) ? $init['dateActiveFrom'] : '';
        $this->_previewText = isset($init["previewText"]) ? $init['previewText'] : '';
        $this->_code = isset($init["code"]) ? $init['code'] : '';
    }
    
    public function __get($name)
    {
        switch($name)
        {
            default:
                return $this->{'_'.$name};
                break;
        }
    }
}