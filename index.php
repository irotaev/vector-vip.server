<?

require($_SERVER["DOCUMENT_ROOT"]."/.core/dispatcher.php"); die;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "строительная компания \"VECTOR\"");
$APPLICATION->SetPageProperty("keywords", "строительство");
$APPLICATION->SetPageProperty("title", "Строительная компания VECTOR");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Главная страница");
?>

<div style="display: table; height: 100%; width: 100%;">
<div style="display: table-cell; vertical-align: middle; text-align:center;">
<p>Строительная компания "VECTOR"</p>
Генеральный директор: Ротаева Людмила Михайловна
<p><b>Проект находится в разработке</b></p>
</div>
</div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
