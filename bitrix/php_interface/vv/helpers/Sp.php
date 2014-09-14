<?php

require_once 'Abstract.php';
require_once 'Debug.php';
/**
 *
 * @author dmitriy
 */
class MH_HelperSp extends MH_HelperAbstract
{
    private $_isSP = null;
    
    
    public function isSP()
    {
        if (is_null($this->_isSP)) {
            $this->_isSP = strpos($GLOBALS['APPLICATION']->getCurDir(), '/sp/') === 0;
        }
        return $this->_isSP;
    }
    
    public function getMenu()
    {
        global $APPLICATION;
        $sMenu = '';
        $includeFile = $_SERVER['DOCUMENT_ROOT'] . $APPLICATION->GetFileRecursive(".section.php");
        if (file_exists($includeFile) && !is_dir($includeFile)) {
            include($includeFile);
            if (!empty($sSectionName)) { // Из файла с описанием секции
                $APPLICATION->SetTitle($sSectionName);
                $sMenu = '<h1 class="title">'.$sSectionName.'</h1>';
                if (!empty($sSectionUrl)) {
                    $sMenu = "<a title='{$sMenu}' href='{$sSectionUrl}'>{$sMenu}</a>";
                }
            }
        }
        $sMenu = $sMenu.$APPLICATION->GetMenuHtmlEx('bottom'); // Подменю (если есть)
        return $sMenu;
    }
}
