<?php
class VV_Articles_ArticleDB
{
    protected $_iblockId;

    protected $_dbResult;

    protected $_cacheTime = 0;
    protected $_cacheDir = '';

    protected $_commonSelect = array(
        'ID', 'CODE', 'IBLOCK_ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PROPERTY_PHOTOGALLERY',
        'PROPERTY_KEYWORDS', 'PROPERTY_DESCRIPTION', 'PROPERTY_CANONICAL',
        'PROPERTY_GOOGLEADWORDS', 'PROPERTY_INDEX_FOLLOW', "DATE_ACTIVE_FROM" 
    );


    public function __construct() 
    {
        $config = \VV\FrontSite::o()->getConfig('articles');
        $this->_iblockId = $config['iblock'];

        $this->_cacheTime = 7*24*60*60;
        $this->_cacheDir = 'cosmo/tags';
    }



    public function fetch($filter, $params = array())
    { 
        // ����������� ������
        ksort($filter);
        foreach($filter as &$f) {
            if (is_array($f)) {
                sort($f);
            }
        }

        // ��������� � id ���� ��� ��������� ���������
        $cacheId = serialize($filter);
        if (isset($params['count'])) {
            $cacheId .= '+cnt'.$params['count'];
        }

        if (isset($params['pageSize'])) {
            $cacheId .= '+ps'.$params['pageSize'].'-'.CDBResult::navStringForCache($params['pageSize']);
        }

        if (isset($params['sort'])) {
            $cacheId .= '+s'.  serialize($params['sort']);
        } else {
            $params['sort'] = array('id'=> 'desc');
        }

        // ��������� ���
        $cache = new CPhpCache;
        if (false && $cache->initCache($this->_cacheTime, $cacheId, $this->_cacheDir)) {

            $vars = $cache->getVars();

            // ��� ����, ����� ������������ ����������� ���������, ��������� CDBResult
            $this->_dbResult = new CDBResult;
            if (isset($params['pageSize'])) {
                $this->_dbResult->InitFromArray(
                    (int)$vars['total'] > 0 ? array_fill(0, $vars['total'], 0) : array()
                );
                if (isset($params['pageSize'])) {
                    $this->_dbResult->navStart($params['pageSize'], true, $vars['page']);
                }
            }
            return $vars['items'];
        }

        // � ���� ���
        CMOdule::includeModule('iblock');

        $filter['IBLOCK_ID'] = isset($filter['IBLOCK_ID']) ? $filter['IBLOCK_ID'] : $this->_iblockId;
        $filter['ACTIVE'] = 'Y';

        $this->_dbResult = CIBlockElement::getList(
            $params['sort'],
            $filter,
            false,
            isset($params['count']) ? array('nTopCount' => $params['count']) : false,
            $this->_commonSelect
        );

        if (isset($params['pageSize'])) {
            $this->_dbResult->navStart($params['pageSize']);
        }

        $result = array();
        while ($row = $this->_dbResult->fetch()) {
            $result[$row['ID']] = $row;
        }

        if($cache->startDataCache()) {
            $cache->endDataCache(
                array(
                    'items' => $result,
                    'total' => $this->_dbResult->NavRecordCount,
                    'page'  => $this->_dbResult->NavPageNomer
                )
            );
        }

        return $result;
    }

    public function getDBResource()
    {
        return $this->_dbResult;
    }

}

