<?php
require_once 'Abstract.php';

/**
 * Description of MH_HelperMeta
 *
 * @author dmitriy
 */
class MH_HelperMeta extends MH_HelperAbstract
{
    const FIRST_TITLE = 'Men\'s Health Россия';
    const DEFAULT_KEYWORDS = '';

    const TITLE_DELIMITER = ' | ';
    const KEYWORDS_DELIMITER = ',';

    const DESCRIPTION_MAX_LENGTH = 250;
    const TITLE_MAX_LENGTH = 65;

    protected $_title = array();
    protected $_video = '';
    protected $_keywords = '';
    protected $_description = '';
    protected $_picture = '';
    protected $_caconicalUrl = '';

    protected $_urlParts = array();

    protected $_excludeParams = array();

    public function __construct($config) {
        parent::__construct($config);

        $this->addTitle(self::FIRST_TITLE);

        $this->_urlParts = array();
        foreach(explode('/', $_SERVER['REQUEST_URI']) as $p) {
            if ('' != $p && '?' != $p[0]) {
                $this->_urlParts[] = $p;
            }
        }
    }

    public function getTitle()
    {
        $titleChain = array_reverse($this->_title);

        // решения для случая, когда установлен тайтл битриксовым способом
        global $APPLICATION;
        if (1 == count($this->_title) && '' != $APPLICATION->getTitle()) {
            array_unshift($titleChain, $APPLICATION->getTitle());
        }

        $title = implode(self::TITLE_DELIMITER, $titleChain);

        // если с длинной все хорошо, возвращаем
        if (strlen($title) <= self::TITLE_MAX_LENGTH) {
            return $title;
        }

        // если это один такой большой заголовок, режем его
        if (1 == count($titleChain)) {
            return $this->_cut($title, self::TITLE_MAX_LENGTH);
        }

        // пробуем отрезать разделы и подразделы
        // т.е. последовательно вырезаем все элементы, кроме первого и последнего
        while (count($titleChain) > 2) {
            array_splice($titleChain, count($titleChain)-2, 1);
            $title = implode(self::TITLE_DELIMITER, $titleChain);

            if (strlen($title) <= self::TITLE_MAX_LENGTH) {
                return $title;
            }
        }

        // остается только основной заголовок
        $title = $titleChain[0];
        return strlen($title) > self::TITLE_MAX_LENGTH
            ? $this->_cut($title, self::TITLE_MAX_LENGTH)
            : $title;
    }

    public function getDescription()
    {
        // есть ли установленный?
        if (!empty($this->_description)) {
            return $this->_description;
        }

        // может есть битриксовый дескрипшн?
        global $APPLICATION;
        if ('' != $APPLICATION->GetProperty('description')) {
            return $APPLICATION->GetProperty('description');
        }

        // ничего не ставили принудительно, берем из конфига
        // ищем от полного пути до корня
        for($i = count($this->_urlParts); $i > 0; $i--) {
            $key = implode('_', array_slice($this->_urlParts, 0, $i));
            if (!empty($this->_config['description'][$key])) {
                return $this->_config['description'][$key];
            }
        }
        // иначе возвращем для корневого
        return empty($this->_config['description']['index'])
            ? ''
            : $this->_config['description']['index'];
    }


    public function getKeywords()
    {
        // есть ли установленные?
        if (!empty($this->_keywords)) {
            return $this->_keywords;
        }

        // может есть битриксовые ключевые слова?
        global $APPLICATION;
        if ('' != $APPLICATION->GetProperty('keywords')) {
            return $APPLICATION->GetProperty('keywords');
        }

        // ничего не ставили принудительно, берем из конфига
        // ищем от полного пути до корня
        for($i = count($this->_urlParts); $i > 0; $i--) {
            $key = implode('_', array_slice($this->_urlParts, 0, $i));
            if (!empty($this->_config['keywords'][$key])) {
                return $this->_config['keywords'][$key];
            }
        }
        // иначе возвращем для корневого
        return empty($this->_config['keywords']['index'])
            ? ''
            : $this->_config['keywords']['index'];
        }

    public function addTitle($title)
    {
        $title = trim(strip_tags($title));
        if (!empty($title)) {
            $this->_title[] = $title;
        }
        return $this;
    }

    public function setKeywords($keywords)
    {
        $keywords = trim(strip_tags($keywords));
        if (!empty($keywords)) {
            $this->_keywords = $keywords;
        }
        return $this;
    }

    public function setTitle($title)
    {
        $title = trim(strip_tags($title));
        $this->_title = array($title);
        return $this;
    }


    public function setVideo($video)
    {
        $this->_video = $video;
        return $this;
    }

    public function setDescription($descr)
    {
        $descr = $this->_cut($descr, self::DESCRIPTION_MAX_LENGTH);
        if (!empty($descr)) {
            $this->_description = $descr;
        }
        return $this;
    }

    public function setPicture($picture)
    {
        $this->_picture = $picture;
        return $this;
    }

    protected function _cut($text, $maxLength)
    {
        $text = trim(strip_tags($text));
        if (strlen($text) <= $maxLength) {
            return $text;
        }
        // находим все границы слов
        if (preg_match_all('/\b/', $text, $m, PREG_OFFSET_CAPTURE)) {
            // перебираем все границы слов в обратном порядке, находим первое
            // подходящее под длинне
            foreach(array_reverse($m[0]) as $key=>$pos) {
                if ($pos[1]+1 <= $maxLength) {
                    return trim(substr($text, 0, $pos[1]));
                }
            }
        }
        return '';
    }

    public function setUrl($url, $excludeParams = array())
    {
        $url = trim($url);
        if (empty($url)) {
            return;
        }
        $this->_excludeParams = $excludeParams;
        $this->_caconicalUrl = $url;
        return $this;
    }

    public function setupBitrixMeta()
    {
        global $APPLICATION;

        if ( ($title = $this->getTitle()) ) {
            $APPLICATION->SetTitle($title);
            // фейсбук
            $APPLICATION->AddHeadString( '<meta property="og:title" content="'
                . (count($this->_title) > 1 ? htmlspecialchars($this->_title[count($this->_title)-1]) : $APPLICATION->getTitle())
                .'" />' );
        }

        if ( ($descr = $this->getDescription()) ) {
            $APPLICATION->SetPageProperty('description', $descr);
            // фейсбук
            $APPLICATION->AddHeadString( '<meta property="og:description" content="'.htmlspecialchars($descr).'" />' );
        }

        if ($this->_picture) {
            // фейсбук
            $APPLICATION->AddHeadString( '<meta property="og:image" content="'.htmlspecialchars($this->_picture).'" />' );
        }

        if ($this->_video) {
            // фейсбук
            $APPLICATION->AddHeadString( '<meta property="og:video" content="'.htmlspecialchars($this->_video).'" />' );
            $APPLICATION->AddHeadString( '<meta property="og:video:type" content="application/x-shockwave-flash" />' );
        }

        if ( ($keywords = $this->getKeywords()) ) {
            $APPLICATION->SetPageProperty('keywords', $keywords);
        }

        if (!empty($_SERVER['QUERY_STRING'])
            && !empty ($this->_caconicalUrl)
            && $_SERVER['REQUEST_URI'] != $this->_caconicalUrl
            && count(array_diff(array_keys($_GET), $this->_excludeParams)) > 0
        ) {
            // если это utm-метка - пропускаем
            $isUtm = true;
            foreach($_GET as $key=>$value) {
                if (strpos($key, 'utm_') !== 0 && 'PAGEN_1' != $key) {
                    $isUtm = false;
                    break;
                }
            }
            if (!$isUtm) {
                $APPLICATION->addHeadString(
                    '<link rel="canonical" href="http://'.SITE_SERVER_NAME.$this->_caconicalUrl . '" />
                    <meta property="og:url" content="http://'.SITE_SERVER_NAME.htmlspecialchars($this->_caconicalUrl).'" />'
                );
            }
       }
    }

    public function setNoIndexNoFollow()
    {
        global $APPLICATION;

        $APPLICATION->AddHeadString('<meta name="robots" content="noindex, follow" />');
    }

}
