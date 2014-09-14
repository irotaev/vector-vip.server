<?php
use VV\Articles as NSArticles;

$articleMapper = new NSArticles\ArticleMapper;

$article = $articleMapper->getByIblockID(4, array('pageSize' => 1));

if (current($article) instanceof NSArticles\Article) 
{
    $article = current($article);
} else 
{
    $article = new NSArticles\Article;
}

$arResult['abouCompany'] = $article; 
