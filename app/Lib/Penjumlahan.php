<?php

namespace App\Lib;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class Penjumlahan implements ModifierInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        return $image;
    }
}