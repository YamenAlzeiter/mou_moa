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
        'css/site.css',
        'css/tabler-icons.css',
        'css/styles.css',

    ];
    public $js = [
        'https://cdn.jsdelivr.net/npm/sweetalert2@11',
        'js/email-template.js',
        'js/main.js',
        'js/bs-init.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
