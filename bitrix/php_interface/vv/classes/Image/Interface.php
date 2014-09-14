<?php

interface VV_Image_Interface
{
    public function quality($quality = null);
    public function crop($width, $height);
    public function getUrl();
}