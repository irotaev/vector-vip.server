<?php

$config = array(
    array(
        'title'         => 'Главная',
        'url'           => '/',
        'route'         => 'index',
        'params'        => array('class' => 'm-home'),
    ),
    array(
        'title'         => 'Форма',
        'route'         => 'articles_section',
        'route_params'  => array('SECTION' => 'form'),
        'params'        => array(
            'class'             => 'm-form', 
            'article-section'   => 'form',
            'tags'              => array(
                'nabor-myshechnoj-massy','sportivnoe-pitanie', 'zvezdnye-trenirovki', 
                'lishnij-ves', 's-sobstvennym-vesom'
            ),
            'blog'              => 'Dimakrat',
            'columns'           => array('Dimakrat'),
            'experts'           => array('fitness'),
            'video'             => true,
        ),
        'childs'        => array(
            array(
                'title'         => 'Фитнес',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'form', 'SUBSECTION' => 'fitness'),
            ),
            array(
                'title'         => 'Спорт',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'form', 'SUBSECTION' => 'sport'),
            ),
            array(
                'title'         => 'Самооборона',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'form', 'SUBSECTION' => 'samooborona'),
            ),
        ),
    ),
    array(
        'title'         => 'М+Ж',
        'route'         => 'articles_section',
        'route_params'  => array('SECTION' => 'sex'),
        'params'        => array(
            'class'             => 'm-mzj',
            'article-section'   => 'sex',
            'tags'              => array('znakomstva','svidanie', 'sex-praktika'),
            'blog'              => 'avintovkina',
            'columns'           => array('avintovkina'),
            'experts'           => array('sex'),
            'video'             => true,
        ),
        'childs'        => array(
            array(
                'title'         => 'Отношения',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'sex', 'SUBSECTION' => 'otnosheniya'),
            ),
            array(
                'title'         => 'Секс',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'sex', 'SUBSECTION' => 'sex'),
            ),
            array(
                'title'         => 'Объект желания',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'sex', 'SUBSECTION' => 'obekt-zhelaniya'),
            ),
        ),
    ),
    array(
        'title'         => 'Здоровье',
        'route'         => 'articles_section',
        'route_params'  => array('SECTION' => 'health'),
        'params'        => array(
            'class'             => 'm-health', 
            'article-section'   => 'health',
            'tags'              => array('vred-kureniya', 'diagnoz', 'stress'),
            'blog'              => 'o-zdorove',
            'columns'           => array('o-zdorove', 'grajdanskaya-samooborona'),
            'experts'           => array('health'),
            'video'             => true,
        ),
        'childs'        => array(
            array(
                'title'         => 'Организм',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'health', 'SUBSECTION' => 'organizm'),
            ),
            array(
                'title'         => 'Психология',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'health', 'SUBSECTION' => 'mind'),
            ),
        ),
    ),
    array(
        'title'         => 'Еда',
        'route'         => 'articles_section',
        'route_params'  => array('SECTION' => 'diet'),
        'params'        => array(
            'class'             => 'm-food',
            'article-section'   => 'diet',
            'tags'              => array(
                'alkogol', 'buterbrody', 'mjaso', 'orehi', 'zdorovoe-pitanie', 
                'shashlyk'
            ),
            'blog'              => 'eda',
            'columns'           => array('eda'),
            'experts'           => array(),
            'video'             => true,
        ),
        'childs'        => array(
            array(
                'title'         => 'Кухня',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'diet', 'SUBSECTION' => 'kuhnya'),
            ),
            array(
                'title'         => 'Рецепты',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'diet', 'SUBSECTION' => 'videorecipes'),
            ),
            array(
                'title'         => 'Рацион',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'diet', 'SUBSECTION' => 'ration'),
            ),
        ),
    ),
    array(
        'title'         => 'Жизнь',
        'route'         => 'articles_section',
        'route_params'  => array('SECTION' => 'life'),
        'params'        => array(
            'class'             => 'm-life',
            'article-section'   => 'life',
            'tags'              => array(
                'deti', 'instructions', 'vyzhivanie', 'priklyucheniya', 'experiment'
            ),
            'blog'              => 'zapiski-advokata',
            'columns'           => array('zapiski-advokata', 'Otcy'),
            'experts'           => array(),
            'video'             => true,
        ),
        'childs'        => array(
            array(
                'title'         => 'Отдых',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'life', 'SUBSECTION' => 'leisure'),
            ),
            array(
                'title'         => 'ЖЗЛ',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'life', 'SUBSECTION' => 'career'),
            ),
            array(
                'title'         => 'Знания',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'life', 'SUBSECTION' => 'knowledge'),
            ),
            array(
                'title'         => 'Карьера',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'life', 'SUBSECTION' => 'karera'),
            ),
        ),
    ),
    array(
        'title'         => 'Стиль',
        'route'         => 'articles_section',
        'route_params'  => array('SECTION' => 'fashion'),
        'params'        => array(
            'class'             => 'm-style',
            'article-section'   => 'fashion',
            'tags'              => array(
                'dzhinsy-muzhskie', 'dress-kod', 'muzhskie-kostjumy', 'obuv', 'parfum'
            ),
            'blog'              => 'egarderob',
            'columns'           => array('egarderob', 'great_looks'),
            'experts'           => array('style', 'cosmetics'),
            'video'             => true,
        ),
        'childs'        => array(
            array(
                'title'         => 'Для пользы тела',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'fashion', 'SUBSECTION' => 'grooming'),
            ),
            array(
                'title'         => 'Гардероб',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'fashion', 'SUBSECTION' => 'guide'),
            ),
        ),
    ),
    array(
        'title'         => 'Железо',
        'route'         => 'articles_section',
        'route_params'  => array('SECTION' => 'technics'),
        'params'        => array(
            'class'             => 'm-iron p-r',
            'article-section'   => 'technics',
            'tags'              => array('gid-tehnika', 'test-drive'),
            'blog'              => 'prikljuchenija-jelektroniki',
            'columns'           => array('prikljuchenija-jelektroniki'),
            'experts'           => array(),
            'video'             => true,
        ),
        'childs'        => array(
            array(
                'title'         => 'Гаджеты',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'technics', 'SUBSECTION' => 'technogid'),
            ),
            array(
                'title'         => 'Гараж',
                'route'         => 'articles_subsection',
                'route_params'  => array('SECTION' => 'technics', 'SUBSECTION' => 'garage'),
            ),
        ),
    ),
    array(
        'title'         => 'Приключения',
        'url'           => 'http://www.mhadventure.ru/',
        'params'        => array('class' => 'm-adventure p-r','target' => '_blank'),
        'childs'        => array(
            array(
                'title'     => 'Новости',
                'url'       => 'http://www.mhadventure.ru/novosti/',
                'params'        => array('target' => '_blank'),
            ),
            array(
                'title'     => 'Туры',
                'url'       => 'http://www.mhadventure.ru/priklyucheniya/',
                'params'        => array('target' => '_blank'),
            ),
        ),
    ),
);