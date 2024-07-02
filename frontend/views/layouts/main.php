<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\components\SidebarV2;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\web\JqueryAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE>
    <html lang="<?= Yii::$app->language ?>">
    <head>

        <link rel="shortcut icon" type="image/png" href="https://style.iium.edu.my/images/iium/iium-logo.png">

        <link href="https://style.iium.edu.my/css/iium.css" rel="stylesheet">

        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <?php $this->head() ?>


    </head>
    <body id="body-pd">

    <?php $this->beginBody() ?>
    <div class="background-image"></div>
    <!-- Preloader start -->
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!-- Preloader end -->
    <header class="header" id="header">
        <?php if (!Yii::$app->user->isGuest): ?>
        <div class="header__toggle">
            <i class='ti ti-menu' id="header-toggle"></i>
        </div>
        <?php else:?>
            <div class="ms-auto">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block">
                        <a href="/site/login" class="text-decoration-none text-white fs-6 ">Login</a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </header>

    <?php if(!Yii::$app->user->isGuest){
        echo SidebarV2::widget([
            'items' => [
                ['url' => 'agreement/index', 'icon' => 'ti ti-layout-dashboard fs-7', 'optionTitle' => 'Agreements'],
                ['url' => 'calender/index', 'icon' => 'ti ti-calendar fs-7', 'optionTitle' => 'Calender'],
            ]
        ]);
    }
    ?>

    <main role="main" class="mt-4">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>
    <?php
    $this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', ['depends' => [JqueryAsset::class]]);
    $this->registerJs(<<<JS
function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

$(document).ready(function() {
    initializeTooltips();
});

$(document).on('pjax:end', function() {
    initializeTooltips();
});
JS
    );
    ?>

    <?php $this->endBody() ?>

    </body>
    </html>
<?php $this->endPage();
