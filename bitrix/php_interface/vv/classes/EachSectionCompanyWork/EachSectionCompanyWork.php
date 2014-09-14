<?php
namespace VV\EachSectionCompanyWork; 

/*
 *  Класс работ по каждой секции раздела  "Превью работ компании"
 */

class EachSectionCompanyWork
{
    private $_SectionId;
    private $_CompanyWork;
    
    public function __get($name) 
    {
        switch($name)
        {
            default :
                return $this->{"_".$name};
                break;
        }
    }
    
    public function __set($name, $value) 
    {
        switch($name)
        {
            case "CompanyWork":
                if ($value instanceof \VV\Articles\Article)
                    $this->_CompanyWork = $value;
                break;
            default :
                $this->{"_".$name} = $value;
                break;
        }
    }
}


