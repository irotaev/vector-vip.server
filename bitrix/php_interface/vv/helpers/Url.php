<?php
require_once 'Abstract.php';
/**
 * Description of url
 *
 * @author dmitriy
 */
class MH_HelperUrl extends MH_HelperAbstract
{
    protected $_cacheDir = '';
    protected $_iblocks = array();
    
    public function __construct() {
        $this->_cacheDir = '';
        
        // ���� � ������� ���� �������� - ���� ������, ����� ��������
        // ���� �������� ����� �� ��������� ������ �������, �� ���� ������������
        $this->_iblocks = array_flip(\MH\FrontSite::o()->iblock->getConfig());
    }
    
    public function getUrlById($id, $module = null)
    {
        if (empty($id)) {
            return '';
        }
        
        // ��������� ����� �����
        $id = (array)$id;
        
        // ��� ����� �������, ������ ��� ���� �������
        $isSingle = (1 == count($id));
        
        if (is_null($module)) {
            // ���� ������ �� �������, ���� �������������� ��� id �� �� �������
            $moduleToIds = $this->_determineModules($id);
        } else {
            // ���� ������� ������, �������, ��� �� ��� ���� id ����������
            $moduleToIds = array((string)$module => $id);
        }
        
        $result = array();
        foreach($moduleToIds as $module => $ids) {
            
            switch ($module) {
                case 'articles':
                    $articleMapper = new MH_Articles_ArticleMapper;
                    foreach($articleMapper->getByIds($ids) as $aid => $article) {
                        $result[$aid] = $article->url;
                    }
                    break;
                case 'authors':
                    $authorMapper = new MH_Authors_AuthorMapper;
                    foreach($authorMapper->getById($ids) as $aid => $author) {
                        $result[$aid] = $author->url;
                    }
                    break;

                default:
                    // �����-�� �����
                    $result = array_merge($result, array_fill_keys($ids, ''));
                    break;
            }
            
        }

        return $isSingle ? array_shift($result) : $result;
    }

    
    protected function _determineModules(array $ids)
    {
        sort($ids);
        
        $cache = new CPhpCache;
        if ($cache->initCache($this->_cacheTime, serialize($ids), $this->_cacheDir)) {
            return $cache->getVars();
        } 
        
        CModule::includeModule('iblock');
        $res = CIBlockElement::getList(
            array(),
            array('ID' => $ids),
            false,
            array('nTopCount' => count($ids)),
            array('ID', 'IBLOCK_ID')
        );
        
        $result = array();
        
        while ($row = $res->fetch()) {
            
            $module = $this->_getModule($row['IBLOCK_ID']);
            if (!array_key_exists($module, $result)) {
                $result[$module] = array();
            }
            $result[$module][] = $row['ID'];
            
        }
        
        if($cache->startDataCache()) {
            $cache->endDataCache($result);
        }
        
        return $result;
    }

    protected function _getModule($iblockId)
    {
        // ���� � ������� ���� �������� - ���� ������, ����� ��������
        return array_key_exists($iblockId, $this->_iblocks) 
            ? $this->_iblocks[$iblockId]
            : '';
    }
    
    public function generateBitrixUrlName(&$arFields)
    {
        if ($arFields["WF_PARENT_ELEMENT_ID"]) { // *** ����������������
            return true;
        }
        
        if ($arFields["ACTIVE"]!="Y") { //������ ��������������
            return true;
        }
        
        $error = false;
        $arNeedSections = array('articles');
        //$arArchiveSections = array('archive');
        $config = \MH\FrontSite::o()->iblock->getConfig();
        $arIblocks = array();
        foreach ($arNeedSections as $section) {
            $id = $config[$section];
            if (is_array($config[$section])) {
                $id = $config[$section]['iblock'];
            }
            $arIblocks[] = $id;
        }
        /*foreach ($arArchiveSections as $section) {
            $id = $config[$section];
            if (is_array($config[$section])) {
                $id = $config[$section]['iblock'];
            }
            $arArchiveIblocks[] = $id;
        }*/
        if (in_array($arFields["IBLOCK_ID"], $arIblocks)) {
            $code = \MH\FrontSite::o()->text->getCodeByName($arFields["NAME"]);
            if (!$code) $error = true;
            /*� ������ �� ����������� - ������������ �� �����*/
            if (!$error) {
                CModule::IncludeModule("iblock");
                $arFilter = array(
                    "IBLOCK_ID" => $arFields["IBLOCK_ID"],
                    "NAME" => $arFields["NAME"]
                );

                $rsRes = CIBlockElement::GetList(array(), $arFilter, false, false , array("ID"));
                if ($arRes = $rsRes->GetNext()) {
                    $error = true;
                }
            }
            /*������������*/
            if (!$error) {
                CModule::IncludeModule("iblock");
                $arFilter = array(
                    "IBLOCK_ID" => $arFields["IBLOCK_ID"],
                    "CODE" => $code
                );

                $rsRes = CIBlockElement::GetList(array(), $arFilter, false, false , array("ID"));
                if ($arRes = $rsRes->GetNext()) {
                    $error = true;
                }
            }
            if(!$error) {
                $arFields["CODE"] = $code;
            } else {
                global $APPLICATION;
                $APPLICATION->throwException("��������� ������������ � ������������ ��������");
                return false;
            }
        } /*elseif (in_array($arFields["IBLOCK_ID"], $arArchiveIblocks)) {
            $code = \CSH\FrontSite::o()->text->getIssueCode($arFields["NAME"]);
            $arFields["CODE"] = $code;
        }*/
        return true;

    }
    
    public function checkBitrixUrlName(&$arFields)
    {
        $arNeedSections = array('articles');
        $config = \MH\FrontSite::o()->iblock->getConfig();
        $arIblocks = array();
        foreach ($arNeedSections as $section) {
            $id = $config[$section];
            if (is_array($config[$section])) {
                $id = $config[$section]['iblock'];
            }
            $arIblocks[] = $id;
        }
        if (in_array($arFields["IBLOCK_ID"], $arIblocks)) {
            global $USER;
            if (isset($arFields["CODE"]) && !$USER->IsAdmin()) {
                unset($arFields["CODE"]);
            }
            if ($arFields["ACTIVE"]!="Y") {
                return true;
            }
            $rsRes = CIBlockElement::GetList(array(), array("ID"=>$arFields["ID"], "CODE"=>false), false, false , array("ID", "CODE"));
            /*���� ��� ����� �������� �������, � ���� ��� ���*/
            if ($arRes = $rsRes->GetNext()) {
                $code = \MH\FrontSite::o()->text->getCodeByName($arFields["NAME"]);
                if (!$code) $error = true;
            
                 /*������������*/
                if (!$error) {
                    CModule::IncludeModule("iblock");
                    $arFilter = array(
                        "IBLOCK_ID" => $arFields["IBLOCK_ID"],
                        "CODE" => $code                    
                    );

                    $rsRes = CIBlockElement::GetList(array(), $arFilter, false, false , array("ID"));
                    if ($arRes = $rsRes->GetNext()) {
                        $error = true;
                    }
                }
                if(!$error) {
                    $arFields["CODE"] = $code;
                } else {
                    global $APPLICATION;
                    $APPLICATION->throwException("��������� ������������ � ������������ ��������");
                    return false;
                }
            }
        }
        return true;
    }


}
