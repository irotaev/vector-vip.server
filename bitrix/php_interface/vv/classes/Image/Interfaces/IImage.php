<?php

namespace VV\Image\Interfaces;

interface IImage
{
    public function Crop($width, $height);    
    public function GetUrl();
}
