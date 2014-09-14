<?php
/*  use Imagick  */
class VV_Image_Transform
{
    const ALIGN_MIDDLE = 1;
    const ALIGN_TOP = 2;

    private $_width;
    private $_height;
    private $_resource;

    function __construct( $file )
    { 
        if ( !class_exists( 'Imagick' ) ) {
            throw new RuntimeException( 'Imagick not installed' );
        }

        if ( !file_exists( $file ) ) {
            throw new InvalidArgumentException( sprintf( 'File %s doesn\'t exist', $file ) );
        }

        $MB = min( 256, ceil( filesize( $file ) / 1000000 ) + 128 );

        try {
            $this->_resource = new Imagick();
            $this->_resource->setResourceLimit( Imagick::RESOURCETYPE_MEMORY, $MB );
            $this->_resource->readImage( $file );
        } catch ( ImagickException $e ) {
            throw new RuntimeException(
                sprintf( 'Could not open path "%s"', $file ), $e->getCode(), $e
            );
        }

        $this->_width  = $this->_resource->getImageWidth();
        $this->_height = $this->_resource->getImageHeight();
    }

    function __destruct()
    {
        if ( $this->_resource ) {
            $this->_resource->clear();
            $this->_resource->destroy();
        }
    }

    function getWidth()
    {
        return $this->_width;
    }

    function getHeight()
    {
        return $this->_height;
    }

    function getResource()
    {
        return $this->_resource;
    }

    function resizeToHeight( $height )
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize( $width, $height );
    }


    function resizeToWidth( $width )
    {
        $ratio  = $width / $this->getWidth();
        $height = $this->getHeight() * $ratio;
        $this->resize( $width, $height );
    }


    function crop( $width, $height, $align = VV_Image_Transform::ALIGN_MIDDLE )
    {
        if ( $this->_width == $width && $this->_height == $height )
            return $this;

        $d = $height / $width;

        if ( $d < ($this->getHeight() / $this->getWidth()) ) {
            $newHeight = $this->getWidth() * $d;
            $srcY      = ($this->getHeight() - $newHeight) / 2;
            $offsetY   = 0;
            if ( $align == VV_Image_Transform::ALIGN_TOP ) {
                $offsetY = (abs( $this->getHeight() - $newHeight ) / 2) * -1;
            }
            //����� ������ �� ��������������� ������ (������) �������� ������
            try {
                $this->_resource->cropImage( $this->getWidth(), $newHeight, 0, $srcY + $offsetY );
            } catch ( ImagickException $e ) {
                throw new RuntimeException(
                    'Crop operation failed', $e->getCode(), $e
                );
            }
            $this->resize( $width, $height );
        } else {

            $newWidth = $this->getHeight() / $d;
            try {
                $this->_resource->cropImage( $newWidth, $this->getHeight(), ($this->getWidth() - $newWidth) / 2, 0 );
            } catch ( ImagickException $e ) {
                throw new RuntimeException(
                    'Crop operation failed', $e->getCode(), $e
                );
            }
            $this->resize( $width, $height );
        }

        return $this;
    }


    /**
     *
     * @param int $width
     * @param int $height
     * @return \VV_Image_Transform
     */
    function fit( $width, $height )
    {
        if ( $this->_height <= $height && $this->_width <= $width ) {
            return $this;
        }

        if ( $height < $width ) {
            // ������� �� ������
        }

        $newD = $height / $width;
        $oldD = $this->_height / $this->_width;

        if ( $oldD > $newD ) {
            $newHeight = $height;
            $newWidth  = $newHeight / $oldD;
        } else {
            $newWidth  = $width;
            $newHeight = $newWidth * $oldD;
        }

        $this->resize( $newWidth, $newHeight );
        return $this;
    }


    /**
     * Resize image to the new sizes
     *
     * @param int $width
     * @param int $height
     * @return VV_Image_Transform
     */
    function resize( $width, $height )
    {
        if ( $this->_width == $width && $this->_height == $height )
            return $this;

        try {

            $this->_resource->resizeImage( $width, $height, Imagick::FILTER_LANCZOS, 1 );

            if ( $width < 210 && $height < 210 )
                $this->_resource->sharpenImage( 0, 0.65 );
            elseif ( $width < 350 && $height < 350 )
                $this->_resource->sharpenImage( 0, 0.4 );
            elseif ( $this->_width > 2 * $width || $this->_height > 2 * $height )
                $this->_resource->sharpenImage( 0, 0.3 );
            elseif ( $this->_width > 1.5 * $width || $this->_height > 1.5 * $height )
                $this->_resource->sharpenImage( 0, 0.2 );
            else
                $this->_resource->sharpenImage( 0, 0.1 );

            $this->_width  = $this->_resource->getImageWidth();
            $this->_height = $this->_resource->getImageHeight();
            $this->_resource->setImagePage( $this->_width, $this->_height, 0, 0 );
        } catch ( ImagickException $e ) {
            throw new RuntimeException(
                'Resize operation failed', $e->getCode(), $e
            );
        }

        return $this;
    }


    /**
     * ��������� � JPG
     *
     * @param string $filename
     * @param int $quality
     */
    function saveJpeg( $filename, $quality = 86 )
    {
        $quality = max( 0, min( $quality, 100 ) );

        try {
            $this->_resource->setImageFormat( "jpeg" );
            $this->_resource->setImageCompression( Imagick::COMPRESSION_JPEG );
            $this->_resource->setImageCompressionQuality( $quality );
            $this->_resource->setInterlaceScheme( Imagick::INTERLACE_JPEG );
            $this->_resource->stripImage();
            try {
                $this->_resource->optimizeImageLayers();
            }catch(ImagickException $e){}
            $this->_resource->writeImage( $filename );
        } catch ( ImagickException $e ) {
            throw new RuntimeException(
                'saveJpeg operation failed', $e->getCode(), $e
            );
        }
    }


    /**
     * ��������� � PNG
     *
     * @param string $filename
     * @param int $quality
     */
    function savePng( $filename, $quality = 99 )
    {
        $quality = max( 0, min( $quality, 100 ) );

        try {
            $this->_resource->setImageFormat( "png" );
            $this->_resource->setCompression( Imagick::COMPRESSION_ZIP );
            $this->_resource->setInterlaceScheme( Imagick::INTERLACE_PNG );
            $this->_resource->setImageCompressionQuality( $quality );
            $this->_resource->stripImage();
            $this->_resource->optimizeImageLayers();
            $this->_resource->writeImage( $filename );
        } catch ( ImagickException $e ) {
            throw new RuntimeException(
                'savePng operation failed', $e->getCode(), $e
            );
        }
    }


    public function watermarkImage( $watermarkImg = null, $alpha_level = 90, $padding_division = 50)
    {
        if ( is_null( $watermarkImg ) ){
            return $this->getResource();
        }

        $alpha_level = max(0, min($alpha_level, 100));

        $WaterMark	=  new CO_Image_Transform( $watermarkImg );

        $image_width            = $this->getWidth();
        $image_height           = $this->getHeight();
        $watermark_width        = $WaterMark->getWidth();
        $watermark_height       = $WaterMark->getHeight();


        $padding = (int)($image_width/$padding_division);

        //��������� �� ������ ��������� �������� ������ � ������ �����������
        if($watermark_width+$padding>$image_width/2 or $watermark_height+$padding> $image_height/2){
            $WaterMark->fit($image_width/2-$padding, $image_height/2-$padding);
            $watermark_width        = $WaterMark->getWidth();
            $watermark_height       = $WaterMark->getHeight();
        }

        $positions    = array();
        // ������ � ������ ������ ����
        $positions[]  = array($image_width - $watermark_width - $padding, $image_height - $watermark_height - $padding);


         $min      = null;
         $min_colors   = 0;

         try {

            foreach($positions as $position)  {
                $colors = $this->_resource->getImageRegion(
                    $watermark_width,
                    $watermark_height,
                    $position[0],
                    $position[1]
                        )->getImageColors();

                if ($min === null || $colors <= $min_colors)  {
                    $min  = $position;
                    $min_colors = $colors;
                }
            }

            $WaterMark->_resource->evaluateImage(Imagick::EVALUATE_MULTIPLY, $alpha_level/100, Imagick::CHANNEL_ALPHA);
            $this->_resource->compositeImage(
                $WaterMark->_resource,
                Imagick::VVMPOSITE_OVER,
                $min[0],
                $min[1]
                    );
            $WaterMark->_resource->destroy();

        } catch (ImagickException $e) {
            throw new RuntimeException(
                'Make watermark operation failed', $e->getCode(), $e
            );
        }

        return $this;
    }

}
