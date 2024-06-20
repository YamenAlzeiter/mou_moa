<?php

namespace common\components;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class Sidebar extends Widget
{
    public $items = [];
    protected $baseUrl;

    public function init()
    {
        parent::init();
        $this->baseUrl = Yii::$app->request->baseUrl;
        ob_start();
        echo '<div class="deznav">';
        echo '<div class="deznav-scroll">';
        echo '<ul class="metismenu" id="menu">';
        echo $this->renderItems($this->items);
    }

    public function run()
    {
        echo '</ul>';
        echo '</div>';
        echo '</div>';
        $content = ob_get_clean();
        echo $content;
    }

    public static function isItemActive($url)
    {
        return Yii::$app->getRequest()->getUrl() == $url;
    }

    protected function renderItems($items)
    {
        $output = '';
        foreach ($items as $item) {
            if (isset($item['title'])) {
                $output .= Html::tag('li', $item['title'], ['class' => 'px-4 text-black text-uppercase fs-5 fw-bold menu-title my-2']);
            }
            else if (isset($item['items'])) {
                $url = isset($item['url']) ? $this->baseUrl . '/' . ltrim($item['url'], '/') : 'javascript:void(0);';
                $options = ['class' => '', 'aria-expanded' => 'false'];
                $linkOptions = ['class' => 'has-arrow'];
                if (isset($item['url']) && self::isItemActive($url)) {
                    Html::addCssClass($options, 'active');
                    Html::addCssClass($linkOptions, 'active');
                }

                $output .= Html::beginTag('li', $options);

                $icon = isset($item['icon']) ? Html::img($item['icon'], ['class' => 'menu-icon mx-2']) : '';
                $optionTitle = isset($item['optionTitle']) ? Html::tag('span', $item['optionTitle'], ['class' => 'nav-text']) : '';
                $output .= Html::a(
                    Html::tag('div', $icon . $optionTitle),
                    $url,
                    $linkOptions
                );

                $output .= Html::beginTag('ul', ['class' => 'left mm-collapse', 'aria-expanded' => 'false']);
                $output .= $this->renderItems($item['items']);
                $output .= Html::endTag('ul');

                $output .= Html::endTag('li');
            }
            else {
                $url = isset($item['url']) ? $this->baseUrl . '/' . ltrim($item['url'], '/') : 'javascript:void(0);';
                $options = ['aria-expanded' => 'false'];
                $linkOptions = [];
                if (isset($item['url']) && self::isItemActive($url)) {
                    Html::addCssClass($options, 'active');
                    Html::addCssClass($linkOptions, 'active');
                }

                $output .= Html::beginTag('li', $options);

                $icon = isset($item['icon']) ? Html::img($item['icon'], ['class' => 'menu-icon mx-2']) : '';
                $optionTitle = isset($item['optionTitle']) ? Html::tag('span', $item['optionTitle'], ['class' => 'nav-text']) : '';
                $output .= Html::a(
                    Html::tag('div', $icon . $optionTitle),
                    $url,
                    $linkOptions
                );

                $output .= Html::endTag('li');
            }
        }
        return $output;
    }
}
