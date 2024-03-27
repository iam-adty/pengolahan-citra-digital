<?php

namespace App\Page;

use Atk4\Data\Model;
use Atk4\Data\Field;
use Atk4\Ui\Accordion;
use Atk4\Ui\Columns;
use Atk4\Ui\Form;
use Atk4\Ui\Form\Control\UploadImage;
use Atk4\Ui\Layout;
use Atk4\Ui\Table\Column\Link;
use Atk4\Ui\Image;
use Atk4\Ui\Js\JsBlock;
use Atk4\Ui\Js\JsExpression;
use Atk4\Ui\Table\Column\Html;
use Atk4\Ui\Text;
use Atk4\Ui\VirtualPage;
use Empira\App\Page;
use Empira\Component\Table;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class Index extends Page
{
    public static function layout(): string|Layout
    {
        return Layout::class;
    }

    protected function build()
    {
        $this->getApp()->requireJs('/assets/imgAreaSelect/js/jquery.imgareaselect.dev.js');
        $this->getApp()->requireJs('/assets/imgAreaSelect/js/onSelectFunction.js');
        $this->getApp()->requireCss('/assets/imgAreaSelect/css/imgareaselect-default.css');

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

        $table->addDecorator('image', new Link('/?image={$image}'));

        $colKanan = Columns::addTo($colLayout->addColumn(14));

        $formUploadImage = Form::addTo($colKanan->addRow());

        $uploadImageControl = $formUploadImage->addControl('image', [
            UploadImage::class,
            [
                'placeholder' => 'Click here to choose image'
            ]
        ]);

        if ($uploadImageControl instanceof UploadImage) {
            $uploadImageControl->onUpload(function ($image) use ($formUploadImage) {
                if ($image['error'] !== 0) {
                    return $formUploadImage->jsError('image', 'Error upload image');
                } else {
                    if (!move_uploaded_file($image['tmp_name'], __DIR__ . '/../../data/images/' . $image['name'])) {
                        return $formUploadImage->jsError('image', 'Error upload image');
                    } else {
                        return $formUploadImage->getApp()->jsRedirect('/');
                    }
                }
            });
        }

        $image = $this->getApp()->tryGetRequestQueryParam('image');

        if (!is_null($image)) {
            $this->stickyGet('image');
            $this->stickyGet('x1');
            $this->stickyGet('y1');
            $this->stickyGet('x2');
            $this->stickyGet('y2');

            $manager = new ImageManager(new Driver());

            $readImage = $manager->read(__DIR__ . '/../../data/images/' . $image);

            $metadata = [
                [
                    'info' => 'name',
                    'value' => $image
                ],
                [
                    'info' => 'width',
                    'value' => $readImage->width()
                ],
                [
                    'info' => 'height',
                    'value' => $readImage->height()
                ]
            ];

            $tableMetadataImage = Table::addTo($colKanan->addRow());
            $tableMetadataImage->setSource($metadata);

            $imageData = file_get_contents(__DIR__ . '/../../data/images/' . $image);
            $imageType = pathinfo(__DIR__ . '/../../data/images/' . $image, PATHINFO_EXTENSION);

            $showImage = Image::addTo($colKanan->addRow(), [
                'data:image/' . $imageType . ';base64,' . base64_encode($imageData)
            ]);

            /** @var \Atk4\Ui\Js\Jquery */
            $showImageJQuery = $showImage->js();

            $this->js(true, new JsExpression('var ias = []', [
                $showImage->js(true)->imgAreaSelect([
                    'instance' => true,
                    'handles' => true,
                    'onSelectEnd' => new JsExpression('function (img, selection) {
                        window.location.href = "' . $this->getApp()->jsUrl('/', [
                        'image' => $this->getApp()->tryGetRequestQueryParam('image'),
                    ]) . '&x1=" + selection.x1 + "&y1=" + selection.y1 + "&x2=" + selection.x2 + "&y2=" + selection.y2
                    }')
                ])
            ]));

            if (!is_null($this->getApp()->tryGetRequestQueryParam('x1')) && !is_null($this->getApp()->tryGetRequestQueryParam('y1')) && !is_null($this->getApp()->tryGetRequestQueryParam('x2')) && !is_null($this->getApp()->tryGetRequestQueryParam('y2'))) {
                $this->js(true, new JsExpression('ias.setSelection([],[],[],[])', [
                    $this->getApp()->getRequestQueryParam('x1'),
                    $this->getApp()->getRequestQueryParam('y1'),
                    $this->getApp()->getRequestQueryParam('x2'),
                    $this->getApp()->getRequestQueryParam('y2'),
                ]));

                $this->js(true, new JsExpression('ias.setOptions({show: true})'));
                $this->js(true, new JsExpression('ias.update()'));

                Text::addTo($colKanan->addRow(), [
                    'X1 = ' . $this->getApp()->getRequestQueryParam('x1') .
                    ', Y1 = ' . $this->getApp()->getRequestQueryParam('y1') .
                    ', X2 = ' . $this->getApp()->getRequestQueryParam('x2') .
                    ', Y2 = ' . $this->getApp()->getRequestQueryParam('y2')
                ]);

                Text::addTo($colKanan->addRow(), [
                    'W = ' . $this->getApp()->getRequestQueryParam('x2') - $this->getApp()->getRequestQueryParam('x1') . ', H = ' . $this->getApp()->getRequestQueryParam('y2') - $this->getApp()->getRequestQueryParam('y1')
                ]);
            }

            // $formGetPixel = Form::addTo($colKanan->addRow());
            // $pixelY = $formGetPixel->addControl('pixel_y', [
            //     'caption' => 'pixel Y',
            // ]);

            // $getPixelY = $this->getApp()->tryGetRequestQueryParam('pixel_y');

            // if (!is_null($getPixelY)) {
            //     $pixelY->set($getPixelY);
            // }

            // $pixelX = $formGetPixel->addControl('pixel_x', [
            //     'caption' => 'pixel X'
            // ]);

            // $getPixelX = $this->getApp()->tryGetRequestQueryParam('pixel_x');

            // if (!is_null($getPixelX)) {
            //     $pixelX->set($getPixelX);
            // }

            // $rgbColorControl = $formGetPixel->addControl('rgb');
            // $hexColorControl = $formGetPixel->addControl('hex');

            // $rgbColorArg = $this->getApp()->tryGetRequestQueryParam('rgb_color');
            // $hexColorArg = $this->getApp()->tryGetRequestQueryParam('hex_color');

            // if (!is_null($rgbColorArg)) {
            //     $rgbColorControl->set($rgbColorArg);
            // }

            // if (!is_null($hexColorArg)) {
            //     $hexColorControl->set($hexColorArg);
            // }

            // $formGetPixel->buttonSave->set('Get Pixel');

            // $formGetPixel->onSubmit(function (Form $form) use ($readImage) {
            //     $pickColor = $readImage->pickColor(
            //         $this->getApp()->getRequestPostParam('pixel_x'),
            //         $this->getApp()->getRequestPostParam('pixel_y')
            //     );

            //     return new JsBlock([
            //         $form->jsReload([
            //             'hex_color' => $pickColor->toHex(),
            //             'rgb_color' => $pickColor->toString(),
            //             'pixel_x' => $this->getApp()->getRequestPostParam('pixel_x'),
            //             'pixel_y' => $this->getApp()->getRequestPostParam('pixel_y'),
            //         ])
            //     ]);
            // });

            $accordion = Accordion::addTo($colKanan->addRow()->setStyle('overflow', 'auto'));

            $accordion->addSection('Hex Color', function (VirtualPage $virtualPage) use ($readImage) {
                $tableColor = Table::addTo($virtualPage);

                if (!is_null($this->getApp()->tryGetRequestQueryParam('x1')) && !is_null($this->getApp()->tryGetRequestQueryParam('y1')) && !is_null($this->getApp()->tryGetRequestQueryParam('x2')) && !is_null($this->getApp()->tryGetRequestQueryParam('y2'))) {
                    $startX = $this->getApp()->getRequestQueryParam('x1');
                    $endX = $this->getApp()->getRequestQueryParam('x2') + 1;
                    $startY = $this->getApp()->getRequestQueryParam('y1');
                    $endY = $this->getApp()->getRequestQueryParam('y2') + 1;
                } else {
                    $startX = 0;
                    $endX = $readImage->width();
                    $startY = 0;
                    $endY = $readImage->height();
                }

                $colorData = [];

                for ($h = $startY; $h < $endY; $h++) {
                    $innerColorData = [
                        'PIXEL' => 'Y' . $h
                    ];

                    for ($w = $startX; $w < $endX; $w++) {
                        $innerColorData['x' . $w] = '#' . $readImage->pickColor($w, $h)->toHex();
                    }

                    $colorData[] = $innerColorData;
                }

                $tableColor->setSource($colorData);

                for ($w = $startX; $w < $endX; $w++) {
                    $tableColor->addDecorator('x' . $w, new class extends Html {
                        public function getHtmlTags(Model $row, ?Field $field): array
                        {
                            return ['_' . $field->shortName => '<td style="width:70px;height:70px;background-color:' . $row->get($field->shortName) . '">' . $row->get($field->shortName) . '</td>'];
                        }
                    });
                }

                return $virtualPage;
            });

            $accordion->addSection('RGB Color', function (VirtualPage $virtualPage) use ($readImage) {
                $tableColor = Table::addTo($virtualPage);

                if (!is_null($this->getApp()->tryGetRequestQueryParam('x1')) && !is_null($this->getApp()->tryGetRequestQueryParam('y1')) && !is_null($this->getApp()->tryGetRequestQueryParam('x2')) && !is_null($this->getApp()->tryGetRequestQueryParam('y2'))) {
                    $startX = $this->getApp()->getRequestQueryParam('x1');
                    $endX = $this->getApp()->getRequestQueryParam('x2') + 1;
                    $startY = $this->getApp()->getRequestQueryParam('y1');
                    $endY = $this->getApp()->getRequestQueryParam('y2') + 1;
                } else {
                    $startX = 0;
                    $endX = $readImage->width();
                    $startY = 0;
                    $endY = $readImage->height();
                }

                $colorData = [];

                for ($h = $startY; $h < $endY; $h++) {
                    $innerColorData = [
                        'PIXEL' => 'Y' . $h
                    ];

                    for ($w = $startX; $w < $endX; $w++) {
                        $innerColorData['x' . $w] = $readImage->pickColor($w, $h)->toString();
                    }

                    $colorData[] = $innerColorData;
                }

                $tableColor->setSource($colorData);

                for ($w = $startX; $w < $endX; $w++) {
                    $tableColor->addDecorator('x' . $w, new class extends Html {
                        public function getHtmlTags(Model $row, ?Field $field): array
                        {
                            return ['_' . $field->shortName => '<td style="width:70px;height:70px;background-color:' . $row->get($field->shortName) . '">' . $row->get($field->shortName) . '</td>'];
                        }
                    });
                }

                return $virtualPage;
            });

            $accordion->addSection('Negatif', function (VirtualPage $virtualPage) use ($readImage) {
                $tableColor = Table::addTo($virtualPage);

                if (!is_null($this->getApp()->tryGetRequestQueryParam('x1')) && !is_null($this->getApp()->tryGetRequestQueryParam('y1')) && !is_null($this->getApp()->tryGetRequestQueryParam('x2')) && !is_null($this->getApp()->tryGetRequestQueryParam('y2'))) {
                    $startX = $this->getApp()->getRequestQueryParam('x1');
                    $endX = $this->getApp()->getRequestQueryParam('x2') + 1;
                    $startY = $this->getApp()->getRequestQueryParam('y1');
                    $endY = $this->getApp()->getRequestQueryParam('y2') + 1;
                } else {
                    $startX = 0;
                    $endX = $readImage->width();
                    $startY = 0;
                    $endY = $readImage->height();
                }

                $colorData = [];

                for ($h = $startY; $h < $endY; $h++) {
                    $innerColorData = [
                        'PIXEL' => 'Y' . $h
                    ];

                    for ($w = $startX; $w < $endX; $w++) {
                        $pixColor = $readImage->pickColor($w, $h);

                        $red = $pixColor->red()->toInt();
                        $green = $pixColor->green()->toInt();
                        $blue = $pixColor->blue()->toInt();

                        $nRed = 255 - $red;
                        $nGreen = 255 - $green;
                        $nBlue = 255 - $blue;

                        $innerColorData['x' . $w] = "rgb(" . $nRed . ", " . $nGreen . ", " . $nBlue . ")";
                    }

                    $colorData[] = $innerColorData;
                }

                $tableColor->setSource($colorData);

                for ($w = $startX; $w < $endX; $w++) {
                    $tableColor->addDecorator('x' . $w, new class extends Html {
                        public function getHtmlTags(Model $row, ?Field $field): array
                        {
                            return ['_' . $field->shortName => '<td style="width:70px;height:70px;background-color:' . $row->get($field->shortName) . '">' . $row->get($field->shortName) . '</td>'];
                        }
                    });
                }

                return $virtualPage;
            });
        }
    }
}
