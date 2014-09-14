<?php
require_once 'Abstract.php';


/**
 * @author: Alexander Nikolaev
 * @email: a.nikolaev@imedia.ru
 */
class MH_HelperWebRender implements MH_RichHtml_RenderInterface
{

    private $_pieceDelimeterRegExp = '~(<div class="nobanner"></div>.*?<div class="nobanner"></div>|<script.*?/script>|<table.*?</table>|<.*?>|</.*?>)~is';
    private $_bannerTag = '[BANNER]';
    private $_bannerMinTextLength = 3000;
    private $_bannerMedian = 1800;
    private $_bannerMinOffset = 750;

    private $_relatedTag = '[READ_MORE]';
    private $_relatedMinTextLength = 2000;
    private $_relatedMedian = 1000;
    private $_relatedMinOffset = 750;

    private $_galleryTags = array('[GALLERY_H]','[GALLERY_V]');

    private $_videoTag = '[VIDEO]';

    protected $_videoSizeWidth = 520;

    protected $_videoSizeHeight = 380;

    protected $_viboxFeedSite = 'm2';

    protected $_viboxPlayer = '/bitrix/components/bitrix/player/mediaplayer/player.swf';

    private $_hasBanner = false;

    private $_hasRelated = false;

    private $_hasGallery = false;

    private $_skipBanner = true;

    private $_skipRelated = true;

    private $_skipGallery = true;

    private $_skipVideo = true;

    private $_isAdvert = true;

    private $_galleries = array();

    private $_video = array();

    private $_related = array();

    private $_allowedUrls = array('cosmo.ru', 'whrussia.ru', 'mhealth.ru', 'cosmoshopping.ru');

    public function toggleBanner($flag)
    {
        $this->_skipBanner = !$flag;
    }

    public function toggleAdvert($flag)
    {
        $this->_isAdvert = $flag;
    }

    public function toggleGallery($flag)
    {
        $this->_skipGallery = !$flag;
    }

    public function toggleRelated($flag)
    {
        $this->_skipRelated = !$flag;
    }

    public function toggleVideo($flag)
    {
        $this->_skipVideo = !$flag;
    }

    public function setRelated(array $list)
    {
        $this->_related = $list;
    }

    public function setGalleries(array $galleries)
    {
        $this->_galleries = array_values($galleries);
    }

    public function setVideo(array $video)
    {
        $this->_video = array_values($video);
    }

    public function hasBanner()
    {
        return $this->_hasBanner;
    }

    public function hasRelated()
    {
        return $this->_hasRelated;
    }

    public function hasGallery()
    {
        return $this->_hasGallery;
    }

    public function render($text)
    {
        $this->_hasRelated = false;
        $this->_hasBanner = false;
        $this->_hasGallery = false;

        // баннер и блок связанных статей вставляются совместно
        // баннер делит текст на две части. Блок связанных статей надо вставить
        // в бОльшую, если его место точно не указано
        // определяем место баннера и вырезаем его тег
        list($text, $bannerPos) = $this->_preRenderingBanner($text);
        // определяем место блока связанных и вырезаем его тег
        list($text, $relatedPos) = $this->_preRenderingRelated($text, $bannerPos);

        if (!is_null($bannerPos)) {
            $banner = $this->getBannerHtml();
            $text = substr($text, 0, $bannerPos) . $banner . substr($text, $bannerPos);
            if (!is_null($relatedPos) && $relatedPos > $bannerPos) {
                $relatedPos += strlen($banner);
            }
            $this->_hasBanner = true;
        }

        if (!is_null($relatedPos)) {
            $text = substr($text, 0, $relatedPos) . $this->getRelatedHtml() . substr($text, $relatedPos);
            $this->_hasRelated = true;
        }

        /* галереи */
        $text = $this->_renderGalleries($text);

        /* видео */
        $text = $this->_renderVideo($text);

        /* Вставка кода для увеличивающихся картинок */
        $text = $this->_renderFancyBoxImages($text);

        /* watermarks */
        $text = $this->_renderWatermarks($text);

        /* голосовалки */
        $text = $this->_renderVotes($text);

        /* обработка внешних ссылок */
        $text = $this->_renderExternalLinks($text);

        /* set text width to 550px */
        $text = \MH\FrontSite::o()->html->tableAndImages550Process($text, 550);

        /* Var теги */
        $text = str_replace(array('<var>', '</var>'), '', $text);

        return $text;
    }

    private function _preRenderingBanner($text)
    {
        if (($pos = strpos($text, $this->_bannerTag)) !== false) {
            // баннер указан точно
            return array(
                str_replace($this->_bannerTag, '', $text),
                $this->_skipBanner ? null : $pos
            );
        }

        if ($this->_skipBanner
            || $this->_isAdvert
            || strlen(preg_replace('/\s+/', ' ', strip_tags($text))) < $this->_bannerMinTextLength
        ) {
            // для рекламных или слишком коротких - пропускаем
            return array($text, null);
        }

        //баннер клеится к тегам
        $parts = preg_split(
            $this->_pieceDelimeterRegExp, $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE
        );
        $pos = null;
        foreach ($parts as $piece) {
            $pieceOffset = $piece[1];
            $pieceLength = strlen($piece[0]);

            if (($pieceOffset + $pieceLength) > $this->_bannerMedian
                && $pieceOffset > $this->_bannerMinOffset
            ) {
                $pos = ($pieceOffset + $pieceLength - $this->_bannerMedian) > ($this->_bannerMedian - $pieceOffset) ? $pieceOffset : $pieceOffset + $pieceLength;
                break;
            }
        }
        return array($text, $pos);
    }

    public function getBannerHtml()
    {
        if ($this->_skipBanner) {
            return '';
        }
        global $APPLICATION;
        ob_start();
        $APPLICATION->IncludeComponent('im:block', 'block_banners_middle_1');
        return ob_get_clean();
    }

    private function _preRenderingRelated($text, &$bannerPos)
    {
        if (($pos = strpos($text, $this->_relatedTag)) !== false) {
            // место указано точно
            if (!is_null($bannerPos) && $pos < $bannerPos) {
                // баннер находится после этого места, после вырезки тега его местоположение сдвинется
                $bannerPos -= strlen($this->_relatedTag);
            }
            return array(
                str_replace($this->_relatedTag, '', $text),
                $this->_skipRelated ? null : $pos
            );
        }

        // если есть баннер
        if (is_null($bannerPos)) {
            $workText = $text;
            $offset = 0;
        } else {
            // работаем с бОльшей половинкой
            if ($bannerPos > strlen($text) / 2) {
                $workText = substr($text, 0, $bannerPos);
                $offset = 0;
            } else {
                $workText = substr($text, $bannerPos);
                $offset = $bannerPos;
            }
        }

        if ($this->_skipRelated
            || $this->_isAdvert
            || strlen(preg_replace('/\s+/', ' ', strip_tags($workText))) < $this->_relatedMinTextLength
        ) {
            // для рекламных или слишком коротких - пропускаем
            return array($text, null);
        }

        //клеится к тегам
        $parts = preg_split(
            $this->_pieceDelimeterRegExp, $workText, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE
        );
        $pos = null;
        foreach ($parts as $piece) {
            $pieceOffset = $piece[1];
            $pieceLength = strlen($piece[0]);

            if (($pieceOffset + $pieceLength) > $this->_relatedMedian
                && $pieceOffset > $this->_relatedMinOffset
            ) {
                $pos = ($pieceOffset + $pieceLength - $this->_relatedMedian) > ($this->_relatedMedian - $pieceOffset) ? $pieceOffset : $pieceOffset + $pieceLength;
                break;
            }
        }

        // не забываем, что могли искать не в полном тексте, а в половинке
        return array(
            $text,
            is_null($pos) ? null : $pos + $offset
        );
    }

    public function getRelatedHtml()
    {
        if ($this->_skipRelated) {
            return '';
        }
        global $APPLICATION;
        ob_start();
        $APPLICATION->IncludeComponent('im:block', 'block_read_more', array('list' => $this->_related, 'template' => 'inner'));
        return ob_get_clean();
    }


    private function _renderGalleries($text)
    {
        if ($this->_skipGallery || 0 == count($this->_galleries)) {
            return str_replace($this->_galleryTags, '', $text);
        }

        $galleries = $this->_galleries;
        $hasGallery = false;
        $getHtml = function ($galleries, $direction, $counter) use (&$hasGallery) {
            if (isset($galleries[$counter]) && $galleries[$counter] instanceof MH_ArticleGallery_Gallery) {
                $hasGallery = true;
                global $APPLICATION;
                ob_start();
                $APPLICATION->IncludeComponent('im:block', 'block_gallery', array('gallery' => $galleries[$counter], 'direction' => $direction, 'num' => $counter));
                return ob_get_clean();
            } else {
                return '';
            }
        };

        $tags = '';
        $prefix = '(';
        foreach ($this->_galleryTags as $tag) {
            $tags .= $prefix . preg_quote($tag);
            $prefix = '|';
        }
        $tags .= ')';

        $galleriesHtml = array();
        preg_match_all('/' . $tags . '/is', $text, $m);

        foreach ($m[1] as $key => $val) {
            $galleriesHtml[$key] = $getHtml($galleries, $val, $key);
        }

        if ( count($m[1]) < count($galleries) ){
            for ( $i = count($m[1]); $i < count($galleries); $i++ ){
                $galleriesHtml[$i] = $getHtml($galleries, $this->_galleryTags[0], $i);
            }
        }
        $num = 0;
        $getCurrentGalleryHtml = function () use (&$num, $galleriesHtml) {

            if (isset($galleriesHtml[$num])) {
                return $galleriesHtml[$num++];
            }

            return '';
        };
        $text = preg_replace_callback(
            '/' . $tags . '/i', $getCurrentGalleryHtml, $text
        );
        while ($num < count($galleries)) {
            $text .= $galleriesHtml[$num++];
        }

        $this->_hasGallery = $hasGallery;
        return $text;
    }

    private function _renderVideo($text)
    {
        /* видео, вставленное тегом с id видеофайла, ex. [video]12345[/video] */
        if (stripos($text, '[video]') !== false) {
            $pattern = $this->_prepareVideoHtml('##ID##');
            $text = preg_replace_callback(
                '/\[video\](\d+?)\[\/video\]/si', function ($matches) use ($pattern) {
                    return str_replace('##ID##', $matches[1], $pattern);
                }, $text
            );
        }

        if ($this->_skipVideo || 0 == count($this->_video)) {
            return str_replace($this->_videoTag, '', $text);
        }

        $num = 0;
        $video = $this->_video;
        $getVideoHtml = function () use (&$num, $video) {

            if (isset($video[$num])) {
                return '<div class="videoBlock">' . $video[$num++]->getContent() . '</div>';
            }

            return '';
        };
        $text = preg_replace_callback(
            '/' . preg_quote($this->_videoTag) . '/i', $getVideoHtml, $text
        );

        return $text;
    }

    protected function _prepareVideoHtml($videoId)
    {
        $containerId = 'videoplayer_' . $videoId;
        return
            '<div align="center" id="' . $containerId . '" style="width:' . $this->_videoSizeWidth . 'px; height:' . $this->_videoSizeHeight . 'px; background: url(http://www.vibox.ru/thumb/bigst/' . $videoId . '.jpg) no-repeat center center;">
    <p style="background-color:#FFF; color:#000;" align="center">
        Для просмотра ролика необходимо установить <a href="http://www.adobe.com/go/getflashplayer" title="Get Flash player">Flash player</a> версии 9 или выше.
    </p>
</div>
<script type="text/javascript"><!--
    if (swfobject.hasFlashPlayerVersion(\'10\')){ var ffhq = 400;} else { var ffhq = 0; }
    var flashvars = {config:\'http://www.vibox.ru/feed_' . $this->_viboxFeedSite . '.php?v=' . $videoId . '-\'+ffhq};
    flashvars.skin = \'/bitrix/components/bitrix/player/mediaplayer/skins/bitrix.swf\';
    var params = {menu:\'false\', allowfullscreen:\'true\', allowscriptaccess:\'always\', wmode:\'opaque\',quality:\'high\'};
    var attributes = {id:\'' . $containerId . '\', name:\'' . $containerId . '\'};
    swfobject.embedSWF(\'' . $this->_viboxPlayer . '\', \'' . $containerId . '\', \'' . $this->_videoSizeWidth . '\', \'' . $this->_videoSizeHeight . '\', \'9\', false, flashvars, params, attributes);
    flashvars = null; params = null; attributes = null;
//-->
</script>';
    }


    private function _renderFancyBoxImages($text)
    {
        $fancyboxImagesFunction = function ($matches) {
            $src = '';

            if (preg_match('/(.*?)src=\"(.*?)\"(.*)/is', $matches[0], $m)) {
                $src = $m[2];
            }

            //Удаляем адрес сайта из пути к картинке
            if (false !== ($hostPosition = strpos($src, $_SERVER["HTTP_HOST"]))) {
                $src = substr($src, $hostPosition + strlen($_SERVER["HTTP_HOST"]));
            }

            $image = new MH_Image($src);

            if (preg_match('/(.*?)width=\"(.*?)\"(.*)/is', $matches[0], $m)) {
                $width = $m[2];
            } else {
                $width = 100;
            }

            if (preg_match('/(.*?)height=\"(.*?)\"(.*)/is', $matches[0], $m)) {
                $height = $m[2];
            } else {
                $height = 100;
            }

            if (preg_match('/(.*?)alt=\"(.*?)\"(.*)/is', $matches[0], $m)) {
                $alt = $m[2];
            } else {
                $alt = 'Нажмите для увеличения';
            }

            $src = $image->watermark()->url;
            $url = $image->crop($width, $height)->url;


            if (empty($url)) {
                $url = $src;
            }

            $imgString = '<a class="fancyboxImage" href="' . $src . '" title="' . $alt . '"><'
                . $matches[1] . 'img src="' . $url . '" width="' . $width . '" '
                . ' height="' . $height . '" alt="' . $alt . '"/><span></span></a>';

            return $imgString;
        };

        return preg_replace_callback('/<([^<]*?)img([^>]*?)class=[\"\']previewImage[\"\'](.*?)>/is', $fancyboxImagesFunction, $text);
    }

    public function _renderWatermarks($text)
    {
        //  watermark
        $waterImagesFunction = function ($matches) {
            //Удаляем адрес сайта из пути к картинке

            if (false !== ($hostPosition = strpos($matches[3], $_SERVER["HTTP_HOST"]))) {
                $matches[3] = substr($matches[3], $hostPosition + strlen($_SERVER["HTTP_HOST"]));
            }

            $image = new MH_Image($matches[3]);

            if (preg_match('/class=[\"\']?watermark[\"\']?/is', $matches[2] . ' ' . $matches[4])) {
                $src = $image->watermark()->url;
            } else {
                $src = $image->url;
            }

            if (empty($src)) {
                $src = $matches[3];
            }

            $imgString = \MH\FrontSite::o()->html->setWidthTo550($matches, $src);

            return $imgString;
        };
        return preg_replace_callback("/<([^<]*?)img(.*?)src=\"(.*?)\"(.*?)>/is", $waterImagesFunction, $text);
    }

    public function _renderVotes($text)
    {
        if (stripos($text, '[vote]') !== false) {
            $text = preg_replace_callback(
                '/\[vote\](\d+?)\[\/vote\]/si', function ($matches) {
                    return '<div class="vote-article poll"><h2>опрос</h2><span class="mini-logo"></span>' . \MH\FrontSite::o()->vote->getById($matches[1], 'article') . '</div>';
                }, $text
            );
        }

        return preg_replace('/\[vote\](\d+?)\[\/vote\]/si', '', $text);
    }

    public function _renderExternalLinks($text)
    {
        $allowedUrls = $this->_allowedUrls;

        // Пройдёмся по всем ссылкам
        $text = preg_replace_callback('~<a([^>]+)href=(?:"|\')([^\'"]+)(?:"|\')([^>]+)*>~i',
            function($matches) use ($allowedUrls) {
                if (!isset($matches[3])) {
                    $matches[3] = '';
                }
                // Проверим, есть ли в атрибуте href ссылка на внешний источник
                if (preg_match('~http(?:s)?://(www\.)?([^/]+)/[\S]*~i', $matches[2], $m)) {

                    // Ок, ссылка есть. Тогда проверим, есть ли этот адрес в списке доверенных
                    if (in_array(strtolower($m[2]), $allowedUrls)) {
                        // Если есть, просто отдаём строку без изменений
                        $text = $matches[0];
                    } else {
                        // Урл не из числа доверенных. Проверим, не установлен ли уже атрибут nofollow
                        if (strpos($matches[3], 'rel="nofollow"') || strpos($matches[1], 'rel="nofollow"')) {
                            // Установлен - опять же, отдаём строку без изменений...
                            $text = $matches[0];
                        }
                        else {
                            // ... либо добавим rel="nofollow" и соберём строку целиком
                            $text = '<a'.$matches[1].'href="'.$matches[2].'" rel="nofollow"'.$matches[3].'>';
                        }
                    }

                    // Возвращаем получившуюся строку
                    return $text;
                }

                // Если эта ссылка не на внешний источник, то всё намного проще:-)
                return $matches[0];
            },
            $text);
        return $text;
    }
}