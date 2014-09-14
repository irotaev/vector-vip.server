<?php

/**
 * Description of redirector
 *
 * @author dmitriy
 */
class MH_Redirector
{
    protected $_sets = array(
        'articles',

        'blogs',

        'advice',

        'short',

        'promo',

        array('~^/new/~', '/news/'),
        array('~^/blog/iglushkov/(\d+).php~', '/blog/eda/#ID#.php', array('#ID#')),
        array('~^/blog/iglushkov/~', '/blog/eda/'),
        array('~^/blog/mhealth/(\d+).php~', '/blog_redakcii/#ID#.php', array('#ID#')),
        array('~^/blog/mh_travel/~', 'http://www.mhadventure.ru'),
        array('~^/controllers/calendar/~', '/magazine/archive/'),
        array('~^/controllers/archive/~', '/magazine/archive/'),
        array('~^/halogramm/vivacity/~', '/health/organizm/'),
        array('~^/halogramm/force/~', '/form/sport/'),
        array('~^/halogramm/snack/~', '/diet/ration/'),
        array('~^/halogramm/act/~', '/sex/sex/'),
        array('~^/halogramm/country/~', '/life/knowledge/'),
        array('~^/halogramm/skill/~', '/life/knowledge/'),
        array('~^/halogramm/knowledge/~', '/life/knowledge/'),
        array('~^/halogramm/retro/~', '/life/knowledge/'),
        array('~^/halogramm/spicery/~', '/life/knowledge/'),
        array('~^/halogramm/final/~', '/life/knowledge/'),

        array('~^/halogramm/delo/~', '/life/knowledge/'),
        array('~^/halogramm/leisure/~', '/life/leisure/'),

        array('~^/form/videofitness/~', '/form/fitness/'), // позже сделать редирект на /form/video/
        array('~^/form/likepro/~', '/form/sport/'),
        array('~^/form/totalfit/~', '/tag/total-fit-2011/'),
        array('~^/form/weightloss/~', '/tag/ya-hudeiu/'),
        array('~^/foto/~', '/tag/otchet/'),
        array('~^/diet/nutrition/~', '/tag/sportivnoe-pitanie/'),
        array('~^/life/mhadv/~', '/tag/priklyucheniya/'),
        array('~^/life/extreme/~', '/tag/extreme/'),

        array('~^/community/~', '/'),

        array('~^/form/zigtech~', '/form/sport/948893/'),
        array('~^/tag/ganteli/~', '/form/sport/'),
        array('~^/tag/zhim-lezha/~', '/form/'),
        array('~^/tag/plechi/~', '/form/'),

        array('~^/about/~', '/about/contacts.php'),

        array('~^/diet/ration/85361/~', '/diet/videorecipes/685424/'),

        array('~^/birds/~', '/life/knowledge/1289527/'),
    );

    public function getUrl($url)
    {

        foreach($this->_sets as $rule) {

            if (is_array($rule)) {

                if (preg_match($rule[0], $url, $m)) {
                    if(!empty($rule[2]) && count((array)$m)-1 == count((array)$rule[2]))
                        return str_replace($rule[2], array_slice($m,1), $rule[1]);
                    else
                        return $rule[1];

                }

            } else {

                $filename = $_SERVER['DOCUMENT_ROOT'] . '/.core/redirectors/'.$rule.'.php';
                if (!file_exists($filename)) {
                    continue;
                }
                require_once $filename;
                $classname = 'MH_Redirect_' . ucfirst($rule);
                $redirector = new $classname($url);
                if ($redirector->isMatch()) {
                    header("HTTP/1.1 301 Moved Permanently");
                    header('Location: ' . $redirector->getUrl());
                    exit();
                }

            }

        }

    }
}
