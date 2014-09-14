<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array( // К ним можно привязывать свойства
		"OVERALL" => array(
			"NAME" => "Общие настройки",
		),
		"DATA_SOURSE" => array(
			"NAME" => "Источник данных",
		),
	),
	"PARAMETERS" => array(
		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),
	),
);
?>
