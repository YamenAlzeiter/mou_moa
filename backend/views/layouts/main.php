<?php
/**
 * @var \yii\web\View $this
 * @var string $content
 */

use backend\assets\AppAsset;
use common\components\Sidebar;
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
<?php /* $this->beginPage() ?>
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
        'brandLabel' => Html::img('https://style.iium.edu.my/images/iium/iium-logo-v2.png', [
            'alt' => Yii::$app->name,
            'class' => 'navbar-brand-logo'
        ]),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md headerpg header fixed-top shadow p-0 px-5 ',
        ],
    ]);
    $menuItems = [
        ['label' => 'Agreements', 'url' => ['/agreement/index'], 'active' => in_array(\Yii::$app->controller->id, ['agreement']),],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    }
    if (Yii::$app->user->identity->is_admin) {
        $menuItems[] = ['label' => 'Dashboard', 'url' => ['/setting'], 'active' => in_array(\Yii::$app->controller->id, ['setting']),];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div', Html::a('Login', ['/site/login'], ['class' => ['btn btn-light login text-decoration-none']]), ['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ' , '. Yii::$app->user->identity->type .')',
                ['class' => 'btn  btn-outline-light logout text-decoration-none ']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0 content-body">
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
<?php Modal::begin([
    'title' => '',
    'id' => 'modal',
    'size' => 'modal-xl',
    'bodyOptions' => ['class' => 'modal-inner-padding-body mt-0'],
    'headerOptions' => ['class' => 'modal-inner-padding justify-content-between'],
    'centerVertical' => true,
    'scrollable' => true,
    'footer' => '&nbsp;',
]);

echo "<div id='modalContent'></div>";

Modal::end();
?>
<script>
    setTimeout(function () {
        $('.alert').fadeOut('slow');
    }, 2500);
</script>

</body>
</html>
<?php $this->endPage(); */ ?>


<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <link rel="shortcut icon" type="image/png" href="https://style.iium.edu.my/images/iium/iium-logo.png">

        <link href = "https://style.iium.edu.my/css/iium.css" rel = "stylesheet">


        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
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
    <!-- Main wrapper start -->
    <div id="main-wrapper">
        <!-- Navigation Header start -->

        <div class="nav-header">
            <a href='<?= Yii::$app->homeUrl ?>' class="brand-logo">
                <img src="https://style.iium.edu.my/images/iium/iium-logo-v2.png" class="user_img"
                     style="max-width: 75%" alt=""/>
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>
            </div>
        </div>

        <div class="header">
            <div class="header-content">
                <?php NavBar::begin([
                    'renderInnerContainer' => false,
                    'options' => [
                        'class' => 'navbar navbar-expand',
                    ],
                ]);



                echo Nav::widget([
                    'options' => ['class' => 'navbar navbar-expand'],

                ]);

                if (Yii::$app->user->isGuest) {
                    echo Html::tag('div',
                        Html::a('Login', ['/site/login'], ['class' => 'btn btn-link text-white login text-decoration-none']),
                        ['class' => 'd-flex']);
                } else {
                    echo Html::beginForm(['/site/logout'], 'post', ['class' => ''])
                        . Html::submitButton('Logout', ['class' => 'btn text-white btn-link logout text-decoration-none'])
                        . Html::endForm();
                }

                NavBar::end();
                ?>
            </div>
        </div>
        <!-- Navigation Header end -->
        <!-- Header start -->

        <!-- Header end -->
        <!-- Sidebar start -->
        <?php

        // Build the menu items array
        $menuItems = [
            ['title' => 'Home'],
            ['url' => 'agreement/index', 'icon' => 'https://style.iium.edu.my/images/iconly/light/Category.svg', 'optionTitle' => 'Agreements'],
        ];


            if (Yii::$app->user->identity->is_admin) {
                $menuItems[] = ['url' => 'setting', 'icon' => 'https://style.iium.edu.my/images/iconly/light/Setting.svg', 'optionTitle' => 'Setting'];
                $menuItems[] =
                    [
                        'icon' => 'https://style.iium.edu.my/images/iconly/light/Setting.svg',
                        'optionTitle' => 'Settings v2',
                        'items' => [
                            ['url' => 'setting/poc', 'optionTitle' => 'Person in Charge'],
                            ['url' => 'setting/email-template', 'optionTitle' => 'Email Template'],
                            ['url' => 'setting/kcdio', 'optionTitle' => 'K/C/D/I/O'],
                            ['url' => 'setting/status', 'optionTitle' => 'Status'],
                            ['url' => 'setting/others', 'optionTitle' => 'Others'],
                        ]
                ];
            }

        // Render the Sidebar widget with the menu items
        echo Sidebar::widget([
            'items' => $menuItems
        ]);

        ?>



        <!-- Sidebar end -->

        <!-- Begin Page Content -->
        <div class="content-body">
            <!-- Breadcrumb start -->
            <div class="page-titles glass">
                <ol class="breadcrumb">
                    <li>
                        <h5 class="bc-title">
                            <?= Html::encode($this->title) ?>
                        </h5>
                    </li>

                </ol>
            </div>
            <div class="pt-1">
                <?= $content ?>
            </div>
        </div>
        <!-- End of Page Content -->
    </div>

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

    ?>
    <?php
    Offcanvas::begin([
        'title' => '', 'placement' => 'end', 'bodyOptions' => ['class' => 'modal-inner-padding-body mt-0'],
        'headerOptions' => ['class' => 'modal-inner-padding justify-content-between flex-row-reverse'], 'options' => [
            'id' => 'myOffcanvas',
        ], 'backdrop' => true
    ]);

    echo "<div id='offcanvas-body'></div>";

    Offcanvas::end(); ?>



    <?php $this->endBody() ?>
    <!-- SCRIPTS -->
<!--    <script src="https://style.iium.edu.my/vendor/global/global.min.js"></script>-->
    <script src="https://style.iium.edu.my/js/custom.js"></script>
    <script src="https://style.iium.edu.my/js/deznav-init.js"></script>


    <script>
        setTimeout(function () {
            $('.alert').fadeOut('slow');
        }, 2500);
    </script>

    </body>
    </html>
<?php $this->endPage();?>




