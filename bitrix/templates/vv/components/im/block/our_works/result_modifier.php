<?php
use VV\Articles as NSArticles;

$articleMapper = new NSArticles\ArticleMapper;

$workSectionId = $arParams["WORK_SECTION_ID"] ? $arParams["WORK_SECTION_ID"] : null;

$ourWorksArticles = $articleMapper->getByIblockID(2, array('pageSize' => 10), $workSectionId);

if (!(current($ourWorksArticles) instanceof NSArticles\Article)) 
{
    $ourWorksArticles = array(new NSArticles\Article);
}

$arResult['ourWorksArticles'] = $ourWorksArticles; 
