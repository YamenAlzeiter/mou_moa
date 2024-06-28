<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

        'css/site.css',
        'css/styles.css',
        'css/tabler-icons.css',
        'css/FullCalenderCustom.css',
    ];
    public $js = [
        'js/main.js',
        'https://cdn.jsdelivr.net/npm/sweetalert2@11',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
