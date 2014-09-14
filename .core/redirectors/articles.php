<?php
/**
 * Description of MH_Redirect_Articles
 *
 * @author dmitriy
 */
class MH_Redirect_Articles
{
    protected $_url;

    protected $_newUrl;

    public function __construct($url) {
        $this->_url = $url;
    }

    public function isMatch()
    {
        if ( ($pos = strpos($this->_url, '?')) ) {
            $url = substr($this->_url, 0, $pos);
        } else {
            $url = $this->_url;
        }

        if (!preg_match('~^/(?:[a-z_]+/)?(?:[a-z_]+/)?(\d{3,})/?$~', $url, $m)) {
            return false;
        }
        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
        $id = (int)$m[1];

        $articleMapper = new MH_Articles_ArticleMapper;

        // пробуем найти по id
        $article = $articleMapper->getByIds($id, array('getNonPublished' => true));

        if ($article->isExists()) {
            $this->_newUrl = $article->url;
            return true;
        } else {
            return false;
        }
    }

    public function getUrl()
    {
        if (!$this->isMatch()) {
            return false;
        }
        return $this->_newUrl;
    }

}