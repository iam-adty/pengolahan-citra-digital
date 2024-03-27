<?php

namespace App\Lib;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;

class PowerTransformation implements ModifierInterface
{
    public function __construct(
        protected $gamma = 1
    )
    {
        
    }

    public function apply(ImageInterface $image): ImageInterface
    {
        for ($w = 0; $w < $image->width(); $w++) {
            for ($h = 0; $h < $image->height(); $h++) {
                $pixelColor = $image->pickColor($w, $h);

                $red = $pixelColor->red()->toInt();
                $green = $pixelColor->green()->toInt();
                $blue = $pixelColor->blue()->toInt();

                $red = pow($red / 255, $this->gamma) * 255;
                $green = pow($green / 255, $this->gamma) * 255;
                $blue = pow($blue / 255, $this->gamma) * 255;

                $red = max(0, min(255, $red));
                $green = max(0, min(255, $green));
                $blue = max(0, min(255, $blue));

                $image->drawPixel($w, $h, 'rgb(' . implode(', ', [$red, $green, $blue]) . ')');
            }
        }

        return $image;
    }
}