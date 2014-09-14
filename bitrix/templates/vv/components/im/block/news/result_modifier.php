<?php
use VV\Articles as NSArticles;

$articleMapper = new NSArticles\ArticleMapper;

if (isset($arParams["template"]))
{
    $arResult["template"] = $arParams["template"];
}
else 
{
    $arResult["template"] = "news_page";
}

if ($arResult["template"] == "aside_news")
{
    $news = $articleMapper->getByIblockID(3, array('pageSize' => 2));
}
else
{
    $news = $articleMapper->getByIblockID(3, array('pageSize' => 20));
}

$arResult['news'] = $news;
