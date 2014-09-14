<?php
namespace VV\Image;

/**
 * Отвечает за операции над изображением
 * 
 */
class ImageOperation
{
    protected $_imageFileId;    
    
    public function __construct($imageId) 
    {
        $this->_imageFileId = $imageId;
    }
    
    /**
     * Рисайзинг изображения
     * 
     * @param int $width Ширина рисайзинга
     * @param int $height Высота рисайзинга
     * @return array("width", "height", "src")
     */
    public function crop($width, $height)
    {
        $cropResult = \CFile::ResizeImageGet($this->_imageFileId, array('width' => $width, 'height'=> $height), 
                BX_RESIZE_IMAGE_EXACT, true);
        
        return $cropResult;
    }
}
