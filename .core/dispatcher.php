<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/'.$_SERVER['APPLICATION_ENV'].'/FrontSite.php';

$route = \VV\FrontSite::o()->router->setUrl($_SERVER['REQUEST_URI'])->dispatch();

if ($route) { // ���� ������
    
    if ($route['ajax']) {
        
        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
        header('Content-Type:' . $route['ajax']);
        $APPLICATION->IncludeComponent('im:block', $route['module'], $route['params']);
        
    } else {
        // ������ ������ layout
        \VV\FrontSite::o()->setLayout($route['layout']);
        
        require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
        
        $APPLICATION->IncludeComponent(
            'im:block', 
            $route['module'], 
            $route['params']
        ); 
        require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); 
    }
    
    
} else { // �� ������, �������� ��������
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
    include($_SERVER['DOCUMENT_ROOT'] . '/bitrix/urlrewrite.php');
}
