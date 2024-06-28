<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://style.iium.edu.my/css/style.css',
        'css/site.css',
        'css/tabler-icons.css',
        'css/styles.css',
    ];
    public $js = [
        'js/email-template.js',
        'js/global.js',
        'https://cdn.jsdelivr.net/npm/sweetalert2@11',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
