<?php
namespace VV\Image;
use VV\Image as NSImage;

/**
 * Базовый класс изображения
 * 
 * @method string GetURL() Получить Url изображения
 * @property-read string $Width Ширина изображения
 * @property-read string $Height Высота изображения
 */
class Image implements NSImage\Interfaces\IImage
{
    protected $_imageFileId;
    protected  $_imageOperation;
    
    protected $_width = null;    
    protected $_height = null;
    protected $_src = null;

    public function __construct($imageId) 
    {
        $this->_imageFileId = (int)$imageId;
        $this->_src =  \CFile::GetPath($this->_imageFileId);
        $this->_imageOperation = new NSImage\ImageOperation($this->_imageFileId);
    }
    
    public function __get($name)
    {
        switch($name)
        {            
            default:
                if (isset($this->{'_'.strtolower($name)}))                
                    return $this->{'_'.strtolower($name)};
                else 
                    return null;
                break;
        }
    }
    
    /**
     * Возвращает Url изображения
     * 
     * @return string
     */
    public function GetUrl()
    {
        return $this->_src;
    }
    
    /**
     * Рисайзинг изображения
     * 
     * @param int $width Ширина рисайзинга
     * @param int $height Высота рисайзинга
     * @return $this
     */
    public function Crop($width, $height)
    {
        $cropedResult = $this->_imageOperation->crop($width, $height);
        
        $this->_src = $cropedResult["src"];
        $this->_width = $cropedResult["width"];
        $this->_height = $cropedResult["height"];
        
        return $this;
    }
}
