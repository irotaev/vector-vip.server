<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use VV\Articles as NSArticles;

$articleMapper = new NSArticles\ArticleMapper();

$copyrightArticle = current($articleMapper->get(array("IBLOCK_CODE" => "CopyrightArticles", "ACTIVE" => "Y")));

$arResult["CopyrightArticle"] = $copyrightArticle;    


