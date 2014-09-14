<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//pr(array($arResult,$arParams));

$TITLE = "В папке {$arResult['URL_PREFIX']} ";
if($COUNT = count($arResult["SP"]["ALL"])){
	$TITLE .= "найдено СП: ".$COUNT;	
} else {
	$TITLE .= "СП не найдены";
}
$APPLICATION->SetTitle($TITLE);
$APPLICATION->SetPageProperty("keywords", "Спецпроекты, СП, конкурсы");
$APPLICATION->SetPageProperty("description", "Полный перечень СП");

// result modifier nocache
/*
$APPLICATION->SetTitle($arResult["NAME"]);


if($GLOBALS["APPLICATION"]->GetShowIncludeAreas())
{
	if (CModule::IncludeModule("iblock"))
	{
		$this->AddIncludeAreaIcons(
			CIBlock::ShowPanel($arResult["IBLOCK_ID"], 
			$arResult["ID"], 
			$arResult["IBLOCK_SECTION_ID"], 
			$arParams["IBLOCK_TYPE"], true)
		);
	}
}
	
return $arResult["ID"];
*/
?>