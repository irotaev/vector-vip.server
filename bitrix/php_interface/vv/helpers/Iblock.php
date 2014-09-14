<?php
require_once 'Abstract.php';

/**
 * Description of url
 *
 * @author marina
 */
class MH_HelperIblock extends MH_HelperAbstract
{
    public function getObjectByCode($code, $sectionCode = false, $clear_cache = "N")
    {
        return $this::getObject(false, $code, $sectionCode, false, $clear_cache);
    }

    public function getObjectByCodeIblock ($code, $sectionCode = false, $iblock = false, $clear_cache = "N")
    {
        return $this::getObject(false, $code, $sectionCode, $iblock, $clear_cache);
    }

    public function getObjectById($id, $sectionCode = false, $clear_cache = "N")
    {
        return $this::getObject($id, false, $sectionCode, false, $clear_cache);
    }

    public function getObject($id, $code, $sectionCode = false, $iblock = false, $clear_cache = "N")
    {
        $id = (int)$id;
        $cache = new CPHPCache;

        if ($id) {
             $arFilter = array("ID"=>$id);
             $cacheId = "object_".$id.$sectionCode;
             $cachePath = "/".SITE_ID."/object/".substr($id, 0, 3);
        } else {
             $arFilter = array("=CODE"=>$code);
             $cacheId = "object_".$code.$sectionCode;
             $cachePath = "/".SITE_ID."/object/".substr($code, 0, 3);
        }

        if ($sectionCode) {
            $arFilter["SECTION_CODE"] = $sectionCode;
            $arFilter["!SECTION_ID"] = false;
        }

        if ($iblock) {
            $arFilter["IBLOCK_ID"] = $iblock;
        }
        // чистим кеш?
        if ('Y'==$clear_cache) {
            CPHPCache::clean($cacheId, $cachePath);
        }

        $cacheTime = CACHE_OBJECT;
        if ($cache->InitCache($cacheTime, $cacheId, $cachePath)) {
            $vars = $cache->GetVars();
            $obj = $vars["OBJECT"];
            return $obj;
        } else {
            $res = CIBlockElement::GetList(array(), $arFilter);
            if($elem = $res->GetNextElement()) {
                $obj = $elem->GetFields();
                $props = $elem->GetProperties();
                $obj["PROPS"] = $props;
                if ($cache->StartDataCache()) {
                    $cache->EndDataCache(array(
                    "OBJECT"=>$obj
                    ));
                }
                return $obj;
            }
        }
        return null;
    }
}
