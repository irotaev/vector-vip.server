<?php
/**
 * @author: Alexander Nikolaev
 * @email: a.nikolaev@imedia.ru
 */

$config = array(
    array(
        'title'        => 'äîêàçàíî...',
        'route'        => 'news',
        'route_params' => array(),
        'params'       => array()
    ),
    array(
        'title'        => 'âèäåî',
        'route'        => 'video',
        'route_params' => array(),
        'params'       => array()
    ),
    array(
        'title'        => 'êîëóìíèñòû',
        'route'        => 'blogs',
        'route_params' => array(),
        'params'       => array(
            'child_routes' => array(
                'blogs_blog',
                'blogs_post',
                'blogs_post_page',
            )
        )
    ),
    array(
        'title'  => 'ôîğóì',
        'url'    => 'http://forum.mhealth.ru/',
        'route'  => false,
        'params' => array('target' => '_blank'),
    ),
    array(
        'title'        => 'ÿ õóäåş...',
        'route'        => 'tag_detail',
        'route_params' => array('ELEMENT' => 'ya-hudeiu'),
        'params'       => array('target' => '_blank')
    ),
    array(
        'title'        => 'îáúåêò æåëàíèÿ',
        'route'        => 'articles_subsection',
        'route_params' => array('SECTION' => 'sex', 'SUBSECTION' => 'obekt-zhelaniya'),
        'params'       => array('target' => '_blank')
    ),
    array(
        'title'        => 'çà ñîâåòîì',
        'route'        => 'advice_list',
        'route_params' => array(),
        'params'       => array(
            'child_routes' => array(
                'advice_section',
                'advice_section_answered',
                'advice_section_published',
                'advice_section_question',
            )
        )
    ),
    array(
        'title'        => 'øêîëà òóğíèêà',
        'route'        => 'tag_detail',
        'route_params' => array('ELEMENT' => 'turnikman'),
        'params'       => array()
    ),
    array(
        'title'        => 'workout',
        'route'        => 'articles_section',
        'route_params' => array('SECTION' => 'workout'),
        'params'       => array()
    ),
);