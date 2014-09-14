<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Находим список инфоблоков
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
			"NAME" => "Тип инфоблока",
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "DATA_SOURSE",
			"NAME" => "Код инфоблока",
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),
		*/
		"FIND_NAMES" => array(
			"PARENT" => "OVERALL",
			"NAME" => "Прочитать названия из .section.php (Напряжно =])",
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),		
		"URL_PREFIX" => array(
			"PARENT" => "DATA_SOURSE",
			"NAME" => "В какой папке искать СП относительно корня [/sp]",
			"TYPE" => "STRING",
			"DEFAULT" => "/sp",
		),
		/**/

);
?>