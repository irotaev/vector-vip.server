<?php
class MH_Redirect_Promo
{
    protected $_url;

    protected $_newUrl;

    public function __construct($url)
    {
        $this->_url = $url;
    }

    public function isMatch()
    {
        $promoSections = array('obrati_vnimanie', 'promotion');
        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

        $this->_url = explode('?', $this->_url);
        $this->_url = $this->_url[0];

        // Разберём адрес
        $urlParts = explode('/', $this->_url);
        // Удалим пустые элементы
        $urlParts = array_values(array_filter($urlParts, function($el){return !empty($el);}));

        if (count($urlParts) != 3) {
            return false;
        }

        $articleCode = $urlParts[2];

        $articleMapper = new MH_Articles_ArticleMapper();

        foreach ($promoSections as $sectionCode) {
            $section = MH_Articles_SectionRepository::o()->getSectionByCode($sectionCode);
            $article = $articleMapper->getByCode($articleCode, $section);
            if (!is_null($article->code)) {
                $this->_newUrl = '/' . $sectionCode . '/' . $article->code . '/';
                return true;
            }
        }

        return false;
    }

    public function getUrl()
    {
        if (!$this->isMatch()) {
            return false;
        }
        return $this->_newUrl;
    }
}