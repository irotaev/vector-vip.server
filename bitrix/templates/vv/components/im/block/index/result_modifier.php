<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use VV\Articles as NSArticles;

$articleMapper = new  NSArticles\ArticleMapper;

// Статья на главной (о компании)
$article = $articleMapper->getByIblockID(4, array('pageSize' => 1));

if (current($article) instanceof NSArticles\Article) 
{
    $article = current($article);
} else 
{
    $article = new NSArticles\Article;
}

$arResult['article'] = $article; 

// Блок наши работы
//$ourWorksArticles = $articleMapper->getByIblockID(2, array('pageSize' => 15));

$ourWorksArticles = $articleMapper->GetCompanyWorkFromEachSection();

if(!is_array($ourWorksArticles))
{
  $ourWorksArticles = array();  
}

$arResult['ourWorksArticle'] = $ourWorksArticles; 

