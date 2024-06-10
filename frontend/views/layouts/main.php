<?php

/** @var \yii\web\View $this */
/** @var string $content */

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
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>

    <link rel="shortcut icon" type="image/png" href="https://style.iium.edu.my/images/iium/iium-logo.png">
    <!-- Style css -->

    <link href="https://style.iium.edu.my/css/iium.css" rel="stylesheet">
    <!-- FONTS -->

    <!-- BOOTSTRAP SELECT -->
    <link href="https://style.iium.edu.my/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<div class="background-image"></div>
<header>
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('https://style.iium.edu.my/images/iium/iium-logo-v2.png', [
            'alt' => Yii::$app->name,
            'class' => 'navbar-brand-logo'
        ]),
        'options' => [
            'class' => 'navbar navbar-expand-md headerpg  header fixed-top shadow p-0 px-5 ',
        ],
    ]);
    if (!Yii::$app->user->isGuest) {
        $menuItems = [['label' => 'Agreements', 'url' => Yii::$app->homeUrl,    'active' => in_array(\Yii::$app->controller->id, ['agreement']),],];
    }else{
            $menuItems[] = '';
    }


    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-link login text-white text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                '' . Yii::$app->user->identity->username . ' , '. Yii::$app->user->identity->email . '<i class="ti ti-door-exit mx-2"></i>',
                ['class' => 'btn  btn-outline-light logout text-decoration-none ']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="px-5 mt-8">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
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

</body>
</html>
<?php $this->endPage();?>

