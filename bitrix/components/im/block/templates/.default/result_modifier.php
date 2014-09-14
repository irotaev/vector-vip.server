<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// component text here

/*
if (!CModule::IncludeModule("iblock")) 
	return false;

if (intval($arParams["IBLOCK_ID"]) <= 0)
{
	ShowMessage("Не указан инфоблок.");
	return;
}

$arFilter = array(
	"IBLOCK_ID" => intval($arParams["IBLOCK_ID"]),
	"ACTIVE" => "Y",
	"ACTIVE_DATE" => "Y",
);
$arOrder = array(
	"SORT" => "ASC",
);
$arSelect = array(
	"ID", 
	"NAME", 
	"IBLOCK_ID",
	"PREVIEW_PICTURE",
	"PROPERTY_PARTNER_TYPE",
	"DETAIL_PAGE_URL",
);

$db_elements = CIblockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
// $db_elements->SetUrlTemplates($arParams["DETAIL_URL"]);

$elements_found = false;
while ($arElement = $db_elements->GetNext())
{
	$elements_found = true;
	$arElement["PREVIEW_PICTURE"] = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);
	$arResult["ITEMS"][] = $arElement;
}

if (!$elements_found)
{	
	$this->__component->AbortResultCache();
	ShowError("Элемент не найден.");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}
*/

// Мы записываем путь шаблона компонента в массив $arResult
// Если этого не сделать - то после того как компонент закешировался нельзя будет узнать какой шаблон был подключен, и соответственно, какой из файлов result_modifier_nocache.php подключать. 
$arResult["__TEMPLATE_FOLDER"] = $this->__folder;

// Мы записываем содержимое $arResult в кеш.
// Если этого не написать - то массив $arResult в кеше не сохранится, и мы не сможем его прочитать из файла component.php
$this->__component->arResult = $arResult; 
?>