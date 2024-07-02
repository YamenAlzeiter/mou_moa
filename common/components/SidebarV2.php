<?php

namespace common\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;

class SidebarV2 extends Widget
{
    public $items = [];
    protected $baseUrl;

    public function init()
    {
        parent::init();
        $this->baseUrl = Yii::$app->request->baseUrl;
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
        $logoUrl = Yii::getAlias('@web') . '/iiumLogo.svg';
        $logo2Url = Yii::getAlias('@web') . '/iiumLogo2.svg';

        return Html::a(
            Html::img($logoUrl, ['class' => 'ti ti-letter-t fs-7 nav__logo-icon']) .
            Html::tag('span',
                Html::img($logo2Url, ['class' => 'nav__logo-text nav__name']) ), '/agreement/index', ['class' => 'nav__logo']);
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
                Html::tag('i', '', ['class' => $iconClass]) . ' ' . $optionTitle . Html::tag('i', '', ['class' => 'ti ti-chevron-down icon fs-7']),
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
        }


        else{
            return Html::a(
                Html::tag('i', '', ['class' => $iconClass]) . Html::tag('span', $optionTitle, ['class' => 'nav__name']),
                $url,
                $linkOptions
            );
        }

    }
    protected function renderSubItem($subItem){
        $url = isset($subItem['url']) ? $this->baseUrl . '/' . ltrim($subItem['url'], '/') : '#';
        $optionTitle = isset($subItem['optionTitle']) ? $subItem['optionTitle'] : '';
        $linkOptions = ['class' => 'nav__link text-decoration-none'];

        if (self::isItemActive($url)) {
            Html::addCssClass($linkOptions, 'sub-active');
        }

        return Html::a(
            Html::tag('span', $optionTitle, ['class' => 'nav__name sub-icon']),
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
            '<i class="ti ti-logout  fs-7 nav__icon"></i><span class="nav__name">Log Out</span>',
            '#',
            [
                'class' => 'nav__link text-decoration-none',
                'onclick' => 'document.getElementById("logout-form").submit(); return false;',
            ]
        );

        return $logoutLink . $logoutForm;
    }

    /**
     * @throws InvalidConfigException
     */
    /**
     * @throws InvalidConfigException
     */
    public static function isItemActive($url): bool
    {
        $currentUrl = Yii::$app->getRequest()->getUrl();
        $currentUrlPath = parse_url($currentUrl, PHP_URL_PATH);
        $urlPath = parse_url($url, PHP_URL_PATH);

        return $currentUrlPath === $urlPath;
    }

}
?>

