<?php
require_once 'Abstract.php';
/**
 * Фиксит странные символы при 503
 *
 * @author dmitriy
 */
class MH_HelperPublishingStatus extends MH_HelperAbstract
{
    private $_published = true;

    public function setUnPublished()
    {
        $this->_published = false;
    }

    public function OnEndBufferContentHandler($content)
    {
        if (!$this->_published) {
            header('HTTP/1.1 503 Service Unavailable');
            header('Content-length: '.strlen($content));
        }
    }
}