<?php
/**
 * Description of VV_Image_Local
 *
 * @property-read $url
 * @property-read $width
 * @property-read $height
 *
 * @author dmitriy
 */
class VV_Image_Local implements VV_Image_Interface
{
    private $_debugImageServer = 'http://images.cosmo.ru';
    private $_imageServer = null;
    private $_watermarkImage   = '/design/i/logo/watermark.png';
    private $_modifiedBasePath = '/upload/cosmo_cache_img/';

    // ��������-��������
    private $_originalFilename;
    private $_isExist = null;

    /////////////////  ��������� ��������   //////////////
    // ������� �����
    private $_cropWidth = null;
    private $_cropHeight = null;

    // �������� � ������
    private $_fitWidth = null;
    private $_fitHeight = null;

    //������� �������� �����
    private $_isWatermarked = false;

    // ������
    private $_quality = null;

    /////////////////  ��������� ��������, ����������� ������   //////////////
    private $_height    = null;
    private $_width     = null;
    private $_fileSize  = null; // ������ ����� � ������
    private $_url       = null;


    public function __construct($image = '')
    {
        $this->_originalFilename = $image;
        if (!$this->_originalFilename) {
            $this->_isExist = false;
        } 
        if ('development' == \VV\FrontSite::o()->debug->environment && $this->_originalFilename) {
            //  �� ������������� �������� ������� ���� ���� � �������
            $this->_debugDownloadFile($this->_originalFilename);
        }
        $config = \VV\FrontSite::o()->getConfig('images');
        $this->_imageServer = $config['image_server'];
    }

    /**
     * ��������� � ��������� ����
     * @param string $filename
     */
    private function _debugDownloadFile($filename)
    {
        if (preg_match('~(.*)/([^/]+)$~', $filename, $m)) {
            $filePath = $m[1];
            $shortFilename = $m[2];
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath . '/' . $shortFilename)) {
                $curl = curl_init();
                curl_setopt(
                    $curl, CURLOPT_URL,
                    $this->_debugImageServer . $filePath . '/' . $shortFilename
                );
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, true);
                curl_setopt($curl, CURLOPT_BINARYTRANSFER,1);
                list($header, $data) = explode("\r\n\r\n", curl_exec($curl), 2);
                curl_close ($curl);

                $header = explode("\r\n", $header);
                if (strpos($header[0], '200 OK') !== false) {
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath)) {
                        mkdir($_SERVER['DOCUMENT_ROOT'] . $filePath, 0777, true);
                    }
                    file_put_contents($_SERVER['DOCUMENT_ROOT'] . $filePath . '/' . $shortFilename, $data);
                }
            }
        }
    }

    public function __get($name)
    {
        switch($name) {
            case 'url':
                return $this->getUrl();
            case 'size':
                return $this->getFileSize();
            case 'width':
            case 'height':
                $size = $this->getSize();
                return $size[$name];
        }
    }

    public function isExists()
    {
        if (is_null($this->_isExist)) {
            $this->_isExist = file_exists($_SERVER['DOCUMENT_ROOT'] . $this->_originalFilename);
        }
        return $this->_isExist;
    }

    /**
     * ���������� ��� ��������� ��������
     * @return VV_Image
     */
    public function reset()
    {
        // ���������� ���������
        $this->_cropWidth = null;
        $this->_cropHeight = null;
        $this->_fitWidth = null;
        $this->_fitHeight = null;
        $this->_isWatermarked = false;
        $this->_quality = null;
        // ���������� ������ ����������� ���������
        $this->_height = null;
        $this->_width  = null;
        $this->_fileSize = null;
        $this->_url = null;

        return $this;
    }

    /**
     *
     * @param int $width
     * @param int $height
     * @return VV_Image_Local
     */
    public function crop($width, $height)
    { 
        // ������������� ��������� "����������"
        $this->_cropWidth = (int)$width;
        $this->_cropHeight = (int)$height;
        if (0 == $this->_cropWidth || 0 == $this->_cropHeight) {
            $this->_cropWidth = $this->_cropHeight = null;
        }
        $this->_width = $this->_height = $this->_fileSize = $this->_url = null;
        return $this;
    }

    /**
     *
     * @param int $width
     * @param int $height
     * @return VV_Image_Local
     */
    public function fit($width, $height)
    {
        // ������������� ��������� "��������"
        $this->_fitWidth = (int)$width;
        $this->_fitHeight = (int)$height;
        if (0 == $this->_fitWidth || 0 == $this->_fitHeight) {
            $this->_fitWidth = $this->_fitHeight = null;
        }
        $this->_width = $this->_height = $this->_fileSize = $this->_url = null;
        return $this;
    }

    /**
     *
     * @param boolean $watermarkFlag
     * @return \VV_Image_Local
     */
    public function watermark($watermarkFlag = true)
    {
        $this->_isWatermarked = $watermarkFlag;
        $this->_fileSize = $this->_url = null;
        return $this;
    }

    /**
     *
     * @return type
     */
    public function quality($quality = null)
    {
        $this->_quality = $quality;
        $this->_fileSize = $this->_url = null;
        return $this;
    }

    public function getSize()
    {
        if (!$this->isExists()) {
            return array('width' => 0, 'height'=> 0);
        }

        // ���� �������?
        if (!is_null($this->_width)) { // �� �����, ����� �������� ���������
            return array('width' => $this->_width, 'height'=> $this->_height);
        }

        // ����� ���� ��������������?
        $imageFilename = $this->_performOperations();
        // � ��� ��� ���� �������������?
        if (!is_null($this->_width)) {
            return array('width' => $this->_width, 'height'=> $this->_height);
        }

        // �� �������������, ��������� ����
        $image = new VV_Image_Transform($_SERVER['DOCUMENT_ROOT'] . $imageFilename);
        $this->_width = $image->getWidth();
        $this->_height = $image->getHeight();

        return array('width' => $this->_width, 'height'=> $this->_height);
    }

    //��� ��������
    public function getFileSize()
    {
        if (!$this->isExists()) {
            return 0;
        }

        // ���� ������?
        if (!is_null($this->_fileSize)) {
            return $this->_fileSize;
        }

        // ����� ���� ��������������?
        $imageFilename = $this->_performOperations();
        return $this->_fileSize = filesize($_SERVER['DOCUMENT_ROOT'] . $imageFilename);
    }

    public function getUrl()
    {
        if (!$this->isExists()) {
            return '';
        }

        // ���� URL?
        if (!is_null($this->_url)) {
            return $this->_url;
        }

        // ����� ���� ��������������
        $this->_url = $this->_performOperations();
        return $this->_imageServer.$this->_url;
    }

    private function _performOperations()
    {
        if ( !($modifiedFilename = $this->_getModifiedFilename()) ) {
            return $this->_originalFilename;
        }

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $modifiedFilename)) {
            return $modifiedFilename;
        }

        // ��������� ��������
        $image = new VV_Image_Transform($_SERVER['DOCUMENT_ROOT'] . $this->_originalFilename);

        if (!is_null($this->_cropWidth) && !is_null($this->_cropHeight)) {
            $image = $image->crop($this->_cropWidth, $this->_cropHeight);

            $this->_width = $image->getWidth();
            $this->_height = $image->getHeight();
        }

        if (!is_null($this->_fitWidth) && !is_null($this->_fitHeight)) {
            $image = $image->fit($this->_fitWidth, $this->_fitHeight);
        }

        if ( $this->_isWatermarked && $image->getWidth() > 198 && $image->getHeight() > 220 ) {
            $image = $image->watermarkImage($_SERVER['DOCUMENT_ROOT'].$this->_watermarkImage);
        }

        // ������������� ��������� ������� ���������
        $this->_width = $image->getWidth();
        $this->_height = $image->getHeight();

        // ��������� �� ������������� ������� �����
        if (preg_match('~(.*)/([^/]+)$~', $modifiedFilename, $m)) {
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $m[1])) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . $m[1], 0777, true);
            }
        }

        // ���������
        $image->saveJpeg(
            $_SERVER['DOCUMENT_ROOT'].$modifiedFilename,
            is_null($this->_quality) ? 86 : $this->_quality
        );

        return $modifiedFilename;
    }

    private function _getModifiedFilename()
    {
        $postfix = '';

        // ���� ���������
        if (!is_null($this->_cropWidth) && !is_null($this->_cropHeight)) {
            $postfix .= '_cropped_'.$this->_cropWidth.'x'.$this->_cropHeight;
        }

        // ���� ��������
        if (!is_null($this->_fitWidth) && !is_null($this->_fitHeight)) {
            $postfix .= '_fitted_'.$this->_fitWidth.'x'.$this->_fitHeight;
        }

        // ���� � ������ ��������
        if (!is_null($this->_quality)) {
            $postfix .= '_q'.$this->_quality;
        }

        // ����������
        if ( $this->_isWatermarked ){
            $postfix .= '_watermarked';
        }

        // ����������� �� ����
        if (!$postfix){
            return false;
        }

        if (!preg_match('~(.*)/([^/]+)\.[^\.]+$~', $this->_originalFilename, $m)) {
            return false;
        }

        $watermarkSalt = $this->_isWatermarked ? 'HgYte%' : '';
        $shortFilename = md5($this->_originalFilename.$watermarkSalt).$postfix.'.jpg';
        return $this->_modifiedBasePath.substr($shortFilename, 0, 3).'/'.$shortFilename;
    }

}