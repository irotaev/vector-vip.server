<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// ������� ������ ����������
/*
if(!CModule::IncludeModule("iblock")) return;

$arIBlockType = CIBlockParameters::GetIBlockTypes();
$arIBlock=array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
{
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}
*/


$arTemplateParameters = array(
		
		/*
		"IBLOCK_TYPE" => array(
			"PARENT" => "DATA_SOURSE",
			"NAME" => "��� ���������",
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "DATA_SOURSE",
			"NAME" => "��� ���������",
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),
		"RELOAD_QUANTITY" => array(
			"PARENT" => "OVERALL",
			"NAME" => "������������� ����� ���������",
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"ADD_URL" => array(
			"PARENT" => "OVERALL",
			"NAME" => "�������� URL",
			"TYPE" => "STRING",
			"DEFAULT" => "/sp/",
		),
		*/

);
?>