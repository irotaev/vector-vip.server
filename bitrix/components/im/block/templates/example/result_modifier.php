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
				// ����������� ����� �������� ��
				if($arParams["FIND_NAMES"] == "Y"){
					$sfile = $SP['DIR_PATH'].'/'.'.section.php';
					if(is_file($sfile)){						
						@include_once($sfile);
						$sSectionName = strip_tags($sSectionName);
					}
				}
				$SP['NAME'] = (!empty($sSectionName))?$sSectionName:"�������� �� �� ����������";
				// ������ ��
				$arResult["SP"]["ALL"][] = $SP;
			}
		}
	}
	$d->close();
} else {
	// ����� �����..
	$this->__component->AbortResultCache();
	ShowError("������� �� ������.");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

// �� ���������� ���� ������� ���������� � ������ $arResult
// ���� ����� �� ������� - �� ����� ���� ��� ��������� ������������� ������ ����� ������ ����� ������ ��� ���������, � ��������������, ����� �� ������ result_modifier_nocache.php ����������. 
$arResult["__TEMPLATE_FOLDER"] = $this->__folder;

// �� ���������� ���������� $arResult � ���.
// ���� ����� �� �������� - �� ������ $arResult � ���� �� ����������, � �� �� ������ ��� ��������� �� ����� component.php
$this->__component->arResult = $arResult; 
?>