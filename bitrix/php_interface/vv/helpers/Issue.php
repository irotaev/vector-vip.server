<?php
require_once 'Abstract.php';

/**
 * Description of url
 *
 * @author marina
 */
class MH_HelperIssue extends MH_HelperAbstract
{
   function __construct($year = false) 
   {       
        $config = \MH\FrontSite::o()->iblock->getConfig();
        $this->_iblock = $config['archive'];        
    }
    
   public function getLastUrl()
   {
        $cacheTime = 3600;
        $cacheId = "last_issue_url";
        $cacheDir = SITE_ID.'/issues';
        $cache = new CPhpCache;        
        if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) { 
            return $cache->getVars();             
        }
        CModule::IncludeModule("iblock");
        $arFilter = array("IBLOCK_ID"=>$this->_iblock, 'ACTIVE_DATE'=>"Y");        
        $res = CIBlockElement::GetList(
                array("ACTIVE_FROM"=>"DESC"),
                $arFilter,
                false,
                array("nTopCount" => 1),
                array("ID")
        );
        $arIssues = array();
        if($arIssue = $res->GetNext()){   
            $url = \MH\FrontSite::o()->router->makeUrl('issues_issue', array('ID'   => $arIssue['ID']));   
        };
        if($cache->startDataCache()) {
            $cache->endDataCache($url);
        }
        return $url;
   }
}
