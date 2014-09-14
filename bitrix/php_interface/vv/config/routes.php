<?php
$sAdvertisingSections = 'obrati_vnimanie|promotion';

/* rule         - регулярка для определения физического месторасположения скрипта отрубает все после знака ?
 * query_rule   - тоже что rule но с get параметрами.
 * template     - шаблон url
 * module       - шаблон компонента im:block
 * rule_params  - параметры для скрипта, получаемые регуляркой из rule или query_rule
 * fixed_params - дополнительные параметры не меняющиеся для данного модуля.
 * ajax         - тип ответа, если задан этот параметр то подключается prolog_before.php вместо header.php см. dispatcher.php
 *
 * Используется  одно из rule или query_rule
 * rule_params и fixed_params объединяются и передаются в качестве $arParams в шаблон, указанный в module
 */
$routes = array(
    // из-за СУЩЕСТВУЮЩЕЙ папки /services/ пришлось закоментить -d  на правиле в
    // htaccess, из-за этого не вызывался index.php в корне
    'index'         => array(
        //'rule'          => '^/(index_banner(2)?\.php)?$',
        'rule'          => '^/$',
        'module'        => 'index',
    ),

    'about_company'     => array(
        'rule'          => '^/about-company/$',
        'module'    => 'about_company'
    ),
    
    'news'     => array(
        'rule'          => '^/news/$',
        'module'    => 'news',
        'fixed_params'  => array("template" => 'news_page')
    ),
    
    'news_article'     => array(
        'rule'          => '^/news/([0-9]+)/$',
        'module'    => 'news',
        'fixed_params'  => array("template" => 'news_page')
    ),
    
    'our_works'     => array(
        'rule'          => '^/nashi-raboti/([0-9]*)$',
        'module'    => 'our_works',
        'rule_params' => array(1 => 'WORK_SECTION_ID')
    ),
   
    'copyright' => array(
        'rule' => '^/copyright/$',
        'module' => 'copyright'
    )
);