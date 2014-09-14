<?php
/**
 * 
 */
class VV_Articles_ArticleMapper
{
    protected $_db;

    public function __construct()
    {
        $this->_db = new VV_Articles_ArticleDB;
    }    
    
    public function getAll($params = array())
    {
        $result = array();
        foreach($this->_db->fetch(array(), $params) as $row) { 
            $result[$row['ID']] = $this->_map($row);
        }
        return $result;
    }
    
    public function getByIblockID($iblockID, $params = array())
    {
        $result = array();
        foreach($this->_db->fetch(array('IBLOCK_ID' => $iblockID), $params) as $row) { 
            $result[$row['ID']] = $this->_map($row);
        }
        return $result;
    }

    public function getPaginatorResource()
    {
        return $this->_db->getDBResource();
    }

    protected function _map($row = array())
    {
        if (empty($row)) {
            return new VV_Articles_Article;
        }
        return new VV_Articles_Article(array(
            'id' => $row['ID'],
            'name' => $row['NAME'],
            'detailText' => $row['DETAIL_TEXT'],
            'dateActiveFrom' => $row["DATE_ACTIVE_FROM"],
            'previewText'       => $row["PREVIEW_TEXT"],
            'code'                 =>  $row["CODE"],
            'photoGallery'     =>  $row['PROPERTY_PHOTOGALLERY']
        ));
    }

}