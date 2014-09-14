<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (isset($arParams["COMPONENT_ENABLE"]) 
    && $arParams["COMPONENT_ENABLE"] === false
) {
    return;
}


//var_dump($this->__path);
$arResult["__TEMPLATE_FOLDER"] = $this->__path;
// Подключается файл result-modifier.php
$this->IncludeComponentTemplate();


if($GLOBALS["APPLICATION"]->GetShowIncludeAreas() && $USER->isAdmin()) {
	// Подключение иконок редактирования файла .parameters.php
	
	$filename = ".parameters.php";
	$parameters_edit = "jsPopup.ShowDialog('/bitrix/admin/public_file_edit_src.php?site=".SITE_ID."&path=".urlencode($arResult["__TEMPLATE_FOLDER"])."%2F".$filename."', {'width':'770', 'height':'570', 'resize':true })";
	
	$this->AddIncludeAreaIcon(
	array(
		'URL'   => "javascript:".$parameters_edit.";",
		'SRC'   => $this->GetPath().'/images/edit.gif',
		'TITLE' => "Редактировать файл .parameters.php"
	));


	// Подключение иконок редактирования файла result_modifier.php

	$filename = "result_modifier.php";
	$result_modifier_edit = "jsPopup.ShowDialog('/bitrix/admin/public_file_edit_src.php?site=".SITE_ID."&path=".urlencode($arResult["__TEMPLATE_FOLDER"])."%2F".$filename."', {'width':'770', 'height':'570', 'resize':true })";

	$this->AddIncludeAreaIcon(
	array(
		'URL'   => "javascript:".$result_modifier_edit.";",
		'SRC'   => $this->GetPath().'/images/edit.gif',
		'TITLE' => "Редактировать файл result_modifier.php"
	));


	// Подключение иконок редактирования файла result_modifier_nocache.php

	$filename = "result_modifier_nocache.php";
	$result_modifier_nocache_edit = "jsPopup.ShowDialog('/bitrix/admin/public_file_edit_src.php?site=".SITE_ID."&path=".urlencode($arResult["__TEMPLATE_FOLDER"])."%2F".$filename."', {'width':'770', 'height':'570', 'resize':true })";

	$this->AddIncludeAreaIcon(
	array(
		'URL'   => "javascript:".$result_modifier_nocache_edit.";",
		'SRC'   => $this->GetPath().'/images/edit.gif',
		'TITLE' => "Редактировать файл result_modifier_nocache.php"
	));

	// Подключение иконок редактирования файла template_nocache.php

	$filename = "template_nocache.php";
	$template_nocache_edit = "jsPopup.ShowDialog('/bitrix/admin/public_file_edit_src.php?site=".SITE_ID."&path=".urlencode($arResult["__TEMPLATE_FOLDER"])."%2F".$filename."', {'width':'770', 'height':'570', 'resize':true })";

	$this->AddIncludeAreaIcon(
	array(
		'URL'   => "javascript:".$template_nocache_edit.";",
		'SRC'   => $this->GetPath().'/images/edit.gif',
		'TITLE' => "Редактировать файл template_nocache.php"
	));
}	




// Возвращаемое значение
if (!empty($arResult["__RETURN_VALUE"]))
	return $arResult["__RETURN_VALUE"];
