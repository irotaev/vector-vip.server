<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// component text here

/*
if (!CModule::IncludeModule("iblock")) 
	return false;

if (intval($arParams["IBLOCK_ID"]) <= 0)
{
	ShowMessage("�� ������ ��������.");
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
	ShowError("������� �� ������.");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}
*/

// �� ���������� ���� ������� ���������� � ������ $arResult
// ���� ����� �� ������� - �� ����� ���� ��� ��������� ������������� ������ ����� ������ ����� ������ ��� ���������, � ��������������, ����� �� ������ result_modifier_nocache.php ����������. 
$arResult["__TEMPLATE_FOLDER"] = $this->__folder;

// �� ���������� ���������� $arResult � ���.
// ���� ����� �� �������� - �� ������ $arResult � ���� �� ����������, � �� �� ������ ��� ��������� �� ����� component.php
$this->__component->arResult = $arResult; 
?>