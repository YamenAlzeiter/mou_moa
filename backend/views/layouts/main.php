<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\web\JqueryAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="https://style.iium.edu.my/images/iium/iium-logo.png">
    <!-- Style css -->
<!--    <link href="https://style.iium.edu.my/css/style.css" rel="stylesheet">-->
    <link href="https://style.iium.edu.my/css/iium.css" rel="stylesheet">
    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <!-- BOOTSTRAP SELECT -->
    <link href="https://style.iium.edu.my/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">

    <script src = "https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<div class="background-image"></div>
<!-- Preloader start -->
<div id="preloader">
    <div class="lds-ripple">
        <div></div>
        <div></div>
    </div>
</div>
<header id="main-wrapper">
    <?php
    NavBar::begin([
//        'brandLabel' => Yii::$app->name,
//        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md bg-light px-5 fixed-top shadow',
        ],
    ]);
    $menuItems = [
        ['label' => 'Agreements', 'url' => ['/agreement/index'],    'active' => in_array(\Yii::$app->controller->id, ['agreement']),],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    }
    if(Yii::$app->user->identity->type == "admin") {
//        $menuItems[] = ['label' => 'Admin', 'url' => ['/admin']];
        $menuItems[] = ['label' => 'Email Template', 'url' => ['/email-template'],    'active' => in_array(\Yii::$app->controller->id, ['email-template']),];
        $menuItems[] = ['label' => 'Status', 'url' => ['/status'],    'active' => in_array(\Yii::$app->controller->id, ['status']),];
        $menuItems[] = ['label' => 'KCDIO', 'url' => ['/kcdio'],    'active' => in_array(\Yii::$app->controller->id, ['kcdio']),];
        $menuItems[] = ['label' => 'Administration Dashboard for OSIC', 'url' => ['/dashboard'],    'active' => in_array(\Yii::$app->controller->id, ['dashboard']),];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,

    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-light login text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ' , '. Yii::$app->user->identity->type .')',
                ['class' => 'btn btn-light logout text-decoration-none']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0 content-body">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
<?php
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
    ['depends' => [JqueryAsset::class]]);
$this->registerJs(<<<JS
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
JS
);
?>
<?php modal::begin([
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

modal::end();

?>
<script>
    setTimeout(function () {
        $('.alert').fadeOut('slow');
    }, 2500);
</script>
<!--<script src="https://style.iium.edu.my/vendor/global/global.min.js"></script>-->
<script src="https://style.iium.edu.my/js/custom.js"></script>
<script src="https://style.iium.edu.my/js/deznav-init.js"></script>
<!--<script src="https://style.iium.edu.my/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>-->
</body>
</html>
<?php $this->endPage();
