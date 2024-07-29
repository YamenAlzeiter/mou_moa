<?php
/**
 * @var \yii\web\View $this
 * @var string $content
 */

use backend\assets\AppAsset;
use common\components\Sidebar;
use common\components\SidebarV2;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Offcanvas;
use yii\web\JqueryAsset;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <link rel="shortcut icon" type="image/png" href="https://style.iium.edu.my/images/iium/iium-logo.png">

    <link href="https://style.iium.edu.my/css/iium.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">

    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body id="body-pd">
<?php $this->beginBody() ?>

<div class="background-image"></div>
<div id="preloader">
    <div class="lds-ripple">
        <div></div>
        <div></div>
    </div>
</div>
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
    $menuItems = [
        ['url' => 'agreement/index', 'icon' => 'ti ti-layout-dashboard fs-7', 'optionTitle' => 'Agreements'],
    ];
    if (Yii::$app->user->identity->is_admin) {
        $menuItems[] = [
            'icon' => 'ti ti-settings-2 fs-7',
            'optionTitle' => 'settings',
            'items' => [
                ['url' => 'setting/email-template', 'optionTitle' => 'Email Template'],
                ['url' => 'setting/kcdio', 'optionTitle' => 'K/C/D/I/O'],
                ['url' => 'setting/status', 'optionTitle' => 'Status'],
                ['url' => 'setting/others', 'optionTitle' => 'Others'],
            ]
        ];
    }
    echo SidebarV2::widget([
        'items' => $menuItems,
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

<?php Modal::begin([
    'title' => '',
    'id' => 'modal',
    'size' => 'modal-xl',
    'bodyOptions' => ['class' =>'modal-inner-padding-body mt-0'],
    'headerOptions' => ['class' => 'modal-inner-padding justify-content-between'],
    'centerVertical' => true,
    'scrollable' => true,
    'footer' =>  '&nbsp;',
]);

echo "<div id='modalContent'></div>";

Modal::end();

Offcanvas::begin([
    'title' => '', 'placement' => 'end', 'bodyOptions' => ['class' => 'modal-inner-padding-body mt-0'],
    'headerOptions' => ['class' => 'modal-inner-padding justify-content-between flex-row-reverse'], 'options' => [
        'id' => 'myOffcanvas',
    ], 'backdrop' => true
]);

echo "<div id='offcanvas-body'></div>";

Offcanvas::end();
?>

<?php $this->endBody() ?>

<script>
    setTimeout(function () {
        $('.alert').fadeOut('slow');
    }, 2500);
</script>

</body>
</html>
<?php $this->endPage();?>
