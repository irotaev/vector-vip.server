<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//pr(array($arResult,$arParams));

$arResult["SP"] = array();
$arResult["SP"]["HOST"] = 'http://'.$_SERVER['HTTP_HOST'];

$arResult["URL_PREFIX"] = str_ireplace('..','',$arParams["URL_PREFIX"]);
$arResult["URL_PREFIX"] = '/'.trim($arResult["URL_PREFIX"],'/');
$arResult["SP"]["ROOT_DIR"] = $_SERVER["DOCUMENT_ROOT"].$arResult["URL_PREFIX"];

$arResult["SP"]["ALL"] = array();
	
if(is_dir($arResult["SP"]["ROOT_DIR"])){
	$d = dir($arResult["SP"]["ROOT_DIR"]);
	while (false !== ($element = $d->read())){
		$SP = array();
		if($element!='.' and $element!='..'){		
			$SP['DIR'] = $element;
			$SP['DIR_PATH'] = $arResult["SP"]["ROOT_DIR"].'/'.$element;		
			//echo $SP['DIR'].'<br>';
			if(is_dir($SP['DIR_PATH'])){
				//$element_href = rawurlencode($element);
				$SP['URL'] = $arResult["URL_PREFIX"].'/'.$SP['DIR'];				
				$sSectionName = '';
				// Попробывать найти название СП
				if($arParams["FIND_NAMES"] == "Y"){
					$sfile = $SP['DIR_PATH'].'/'.'.section.php';
					if(is_file($sfile)){						
						@include_once($sfile);
						$sSectionName = strip_tags($sSectionName);
					}
				}
				$SP['NAME'] = (!empty($sSectionName))?$sSectionName:"Название СП не определено";
				// Список СП
				$arResult["SP"]["ALL"][] = $SP;
			}
		}
	}
	$d->close();
} else {
	// Иначе лесом..
	$this->__component->AbortResultCache();
	ShowError("Элемент не найден.");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

// Мы записываем путь шаблона компонента в массив $arResult
// Если этого не сделать - то после того как компонент закешировался нельзя будет узнать какой шаблон был подключен, и соответственно, какой из файлов result_modifier_nocache.php подключать. 
$arResult["__TEMPLATE_FOLDER"] = $this->__folder;

// Мы записываем содержимое $arResult в кеш.
// Если этого не написать - то массив $arResult в кеше не сохранится, и мы не сможем его прочитать из файла component.php
$this->__component->arResult = $arResult; 
?>