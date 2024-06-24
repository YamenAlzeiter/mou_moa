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

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <link rel="shortcut icon" type="image/png" href="https://style.iium.edu.my/images/iium/iium-logo.png">

        <link href="https://style.iium.edu.my/css/iium.css" rel="stylesheet">
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body id="body-pd" class="<?= Yii::$app->user->isGuest ? '' : 'body-pd' ?>">

    <?php $this->beginBody() ?>
    <div class="background-image"></div>

    <header class="header body-pd" id="header">
        <div class="header__toggle">
            <?php if (!Yii::$app->user->isGuest): ?>
                <i class='ti ti-menu' id="header-toggle"></i>
                <?php else:?>
                <ul>
                    <li class="nav__link text-decoration-none">Login</li>
                </ul>
            <?php endif; ?>

        </div>
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

    <main role="main" class="flex-shrink-0 mt-4">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>


    <?php $this->endBody() ?>



    </body>
    </html>
<?php $this->endPage();
