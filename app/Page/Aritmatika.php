<?php

namespace App\Page;

use Atk4\Ui\Columns;
use Atk4\Ui\Form;
use Atk4\Ui\Form\Control\Dropdown;
use Atk4\Ui\Image;
use Atk4\Ui\Text;
use Empira\App\Page;
use Empira\Component\Table;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class Aritmatika extends Page
{
    protected function build()
    {
        $colLayout = Columns::addTo($this);

        $data = array_map(function ($image) {
            return [
                'image' => $image
            ];
        }, scandir(__DIR__ . '/../../data/images'));

        unset($data[0]);
        unset($data[1]);

        $table = Table::addTo($colLayout->addColumn(2));
        $table->setSource($data);

        // $table->addDecorator('image', new Link('/?image={$image}'));

        $colKanan = Columns::addTo($colLayout->addColumn(14));

        Text::addTo($colKanan->addRow(), [
            'Aritmatika Image'
        ]);

        $formAritmatikaImage = Form::addTo($colKanan->addRow());

        $formAritmatikaImage->addControl('image1');
        $formAritmatikaImage->addControl('image2');
        $formAritmatikaImage->addControl('operasi', [
            Dropdown::class,
            'values' => ['penjumlahan', 'pengurangan', 'perkalian', 'pembagian']
        ]);

        $formAritmatikaImage->buttonSave->set('Proses');

        $formAritmatikaImage->onSubmit(function(Form $form) {
            return $this->getApp()->jsRedirect($this->getApp()->url(['aritmatika'], [
                'image1' => $this->getApp()->tryGetRequestPostParam('image1'),
                'image2' => $this->getApp()->tryGetRequestPostParam('image2'),
                'operasi' => $this->getApp()->tryGetRequestPostParam('operasi'),
            ]));
        });

        if (!is_null($this->getApp()->tryGetRequestQueryParam('image1')) && !is_null($this->getApp()->tryGetRequestQueryParam('image2')) && !is_null($this->getApp()->tryGetRequestQueryParam('operasi'))) {
            $operasi = $this->getApp()->getRequestQueryParam('operasi');

            $imageManager = new ImageManager(new Driver());

            $image1 = $imageManager->read(__DIR__ . '/../../data/images/' . $this->getApp()->tryGetRequestQueryParam('image1'));
            $image2 = $imageManager->read(__DIR__ . '/../../data/images/' . $this->getApp()->tryGetRequestQueryParam('image2'));

            $images = Columns::addTo($colKanan->addRow());

            Image::addTo($images, [
                $image1->toBitmap()->toDataUri()
            ]);

            Image::addTo($images, [
                $image2->toBitmap()->toDataUri()
            ]);

            $w1 = $image1->width();
            $h1 = $image1->height();
            
            $w2 = $image2->width();
            $h2 = $image2->height();

            $newImage = $image1;

            for ($w = 0; $w < $w1; $w++) {
                for ($h = 0; $h < $h1; $h++) {
                    $color1 = $image1->pickColor($w, $h);
                    $color2 = $image2->pickColor($w, $h);

                    if ($operasi == '0') {
                        $red = max(0, min(255, $color1->red()->toInt() + $color2->red()->toInt()));
                        $green = max(0, min(255, $color1->green()->toInt() + $color2->green()->toInt()));
                        $blue = max(0, min(255, $color1->blue()->toInt() + $color2->blue()->toInt()));
                    } elseif ($operasi == '1') {
                        $red = max(0, min(255, $color1->red()->toInt() - $color2->red()->toInt()));
                        $green = max(0, min(255, $color1->green()->toInt() - $color2->green()->toInt()));
                        $blue = max(0, min(255, $color1->blue()->toInt() - $color2->blue()->toInt()));
                    } else {
                        $red = 0;
                        $green = 0;
                        $blue = 0;
                    }

                    $newImage->drawPixel($w, $h, 'rgb(' . implode(', ', [$red, $green, $blue]) . ')');
                }
            }

            Image::addTo($images, [
                $newImage->toBitmap()->toDataUri()
            ]);
        }
    }
}