<?php

namespace common\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;

class SidebarV2 extends Widget
{
    public $items = [];
    public $logoUrl;
    public $logoText;
    public $logo2Url;
    public $logo2Text;
    protected $baseUrl;

    public function init()
    {
        parent::init();
        $this->baseUrl = Yii::$app->request->baseUrl;

        // Set default logo URLs if not provided
        if ($this->logoUrl === null && $this->logoText === null) {
            $this->logoUrl = Yii::getAlias('@web') . '/iiumLogo.svg';
        }
        if ($this->logo2Url === null && $this->logo2Text === null) {
            $this->logo2Url = Yii::getAlias('@web') . '/iiumLogo2.svg';
        }
    }

    public function run()
    {
        return Html::tag('div',
            $this->renderNavBar(),
            ['class' => 'l-navbar shadow-lg', 'id' => 'nav-bar']
        );
    }

    protected function renderNavBar()
    {
        return Html::tag('nav',
            Html::tag('div',
                $this->renderLogo() . $this->renderNavList(),
                []
            ) . $this->renderLogoutLink(),
            ['class' => 'nav']
        );
    }

    protected function renderLogo()
    {
        $logoContent = '';
        if ($this->logoUrl !== null) {
            $logoContent .= Html::img($this->logoUrl, ['class' => 'ti ti-letter-t fs-7 nav__logo-icon']);
        } elseif ($this->logoText !== null) {
            $logoContent .= Html::tag('span', $this->logoText, ['class' => 'ti ti-letter-t fs-7 nav__logo-icon']);
        }

        $logo2Content = '';
        if ($this->logo2Url !== null) {
            $logo2Content .= Html::img($this->logo2Url, ['class' => 'nav__logo-text nav__name']);
        } elseif ($this->logo2Text !== null) {
            $logo2Content .= Html::tag('span', $this->logo2Text, ['class' => 'nav__logo-text ']);
        }

        return Html::a($logoContent . $logo2Content, '#', ['class' => 'nav__logo']);
    }

    protected function renderNavList()
    {
        $items = '';
        foreach ($this->items as $item) {
            $items .= $this->renderNavItem($item);
        }

        return Html::tag('div', $items, ['class' => 'nav__list']);
    }

    /**
     * @throws InvalidConfigException
     */

    protected function renderNavItem($item)
    {
        $url = isset($item['url']) ? $this->baseUrl . '/' . ltrim($item['url'], '/') : '#';
        $iconClass = isset($item['icon']) ? $item['icon'] : 'ti ti-icons fs-7';
        $optionTitle = isset($item['optionTitle']) ? $item['optionTitle'] : '';

        $linkOptions = ['class' => 'nav__link text-decoration-none'];
        $sanitizedOptionTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $optionTitle);

        if (self::isItemActive($url)) {
            Html::addCssClass($linkOptions, 'active');
        }

        if (isset($item['items'])) {
            $sub = '';
            foreach ($item['items'] as $subItem) {
                $sub .= $this->renderSubItem($subItem);
            }

            $output = Html::a(
                Html::tag('i', '', ['class' => $iconClass]) . ' ' .  Html::tag('span', $optionTitle, ['class' => 'nav__name']) . Html::tag('i', '', ['class' => 'ti ti-chevron-down icon fs-7']),
                '#',
                [
                    'id' => 'trigger-' . $sanitizedOptionTitle,
                    'class' => 'collapse-trigger nav__link text-decoration-none',
                    'data-toggle' => 'collapse',
                    'data-target' => '#' . $sanitizedOptionTitle . '-collapse',
                ]
            );

            // Create the collapse container
            $output .= Html::tag('div', $sub, [
                'class' => 'collapse-container', // Ensure 'collapse' class is applied
                'id' => $sanitizedOptionTitle.'-collapse', // Unique collapse ID based on $optionTitle
            ]);

            return $output;
        } else {
            return Html::a(
                Html::tag('i', '', ['class' => $iconClass]) . Html::tag('span', $optionTitle, ['class' => 'nav__name']),
                $url,
                $linkOptions
            );
        }
    }

    protected function renderSubItem($subItem)
    {
        $url = isset($subItem['url']) ? $this->baseUrl . '/' . ltrim($subItem['url'], '/') : '#';
        $optionTitle = isset($subItem['optionTitle']) ? $subItem['optionTitle'] : '';
        $linkOptions = ['class' => 'nav__link text-decoration-none'];

        if (self::isItemActive($url)) {
            Html::addCssClass($linkOptions, 'sub-active');
        }

        return Html::a(
            Html::tag('i', '', ['class' => 'ti ti-point fs-7']) . Html::tag('span', $optionTitle, ['class' => 'nav__name']),
            $url,
            $linkOptions
        );
    }

    protected function renderLogoutLink()
    {
        $logoutForm = Html::beginForm(['/site/logout'], 'post', [
                'id' => 'logout-form',
                'style' => 'display: none;',
            ]) . Html::endForm();

        $logoutLink = Html::a(
            '<i class="ti ti-logout fs-7 nav__icon"></i><span class="nav__name">Log Out</span>',
            '#',
            [
                'class' => 'nav__link text-decoration-none',
                'onclick' => 'document.getElementById("logout-form").submit(); return false;',
            ]
        );

        return Html::tag('div', $logoutLink . $logoutForm, ['class' => 'nav__logout-link']);
    }


    /**
     * @throws InvalidConfigException
     */
    public static function isItemActive($url): bool
    {
        $currentUrlPath = $_SERVER['REQUEST_URI'];
        return $currentUrlPath === $url;
    }
}
