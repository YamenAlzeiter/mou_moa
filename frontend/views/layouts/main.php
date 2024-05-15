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
    <link href="https://style.iium.edu.my/css/style.css" rel="stylesheet">
    <link href="https://style.iium.edu.my/css/iium.css" rel="stylesheet">
    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
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
<!--<div id="preloader">-->
<!--    <div class="lds-ripple">-->
<!--        <div></div>-->
<!--        <div></div>-->
<!--    </div>-->
<!--</div>-->
<header>
    <?php
    NavBar::begin([
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md  navbar-dark bg-dark fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Applications', 'url' => Yii::$app->homeUrl],
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-link login text-white text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ' , '. Yii::$app->user->identity->type . ')',
                ['class' => 'btn btn-link logout text-decoration-none text-white']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
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

<script src="https://style.iium.edu.my/js/custom.js"></script>
<script src="https://style.iium.edu.my/js/deznav-init.js"></script>
<script src="https://style.iium.edu.my/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>

</body>
</html>
<?php $this->endPage();?>

<?php
//
///** @var \yii\web\View $this */
///** @var string $content */
//
//use common\widgets\Alert;
//use frontend\assets\AppAsset;
//use yii\bootstrap5\Breadcrumbs;
//use yii\bootstrap5\Html;
//use yii\bootstrap5\Nav;
//use yii\bootstrap5\NavBar;
//use yii\web\JqueryAsset;
//
//
//AppAsset::register($this);
//?>
<?php //$this->beginPage() ?>
<!--    <!DOCTYPE html>-->
<!--    <html lang="--><?php //= Yii::$app->language ?><!--" class="h-100">-->
<!--    <head>-->
<!---->
<!---->
<!--        <!-- FAVICONS ICON -->-->
<!--        <link rel="shortcut icon" type="image/png" href="https://style.iium.edu.my/images/iium/iium-logo.png">-->
<!--        <!-- Style css -->-->
<!--        <link href="https://style.iium.edu.my/css/style.css" rel="stylesheet">-->
<!--        <link href="https://style.iium.edu.my/css/iium.css" rel="stylesheet">-->
<!--        <!-- FONTS -->-->
<!--        <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">-->
<!--        <!-- BOOTSTRAP SELECT -->-->
<!--        <link href="https://style.iium.edu.my/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">-->
<!---->
<!---->
<!--        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>-->
<!--        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>-->
<!--        <meta charset="--><?php //= Yii::$app->charset ?><!--">-->
<!--        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">-->
<!--        --><?php //$this->registerCsrfMetaTags() ?>
<!--        <title>--><?php //= Html::encode($this->title) ?><!--</title>-->
<!--        --><?php //$this->head() ?>
<!--    </head>-->
<!--    <body class="d-flex flex-column h-100">-->
<!--    --><?php //$this->beginBody() ?>
<!--    <div class="background-image"></div>-->
<!--    <!-- Preloader start -->-->
<!--    <div id="preloader">-->
<!--        <div class="lds-ripple">-->
<!--            <div></div>-->
<!--            <div></div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div id="main-wrapper">-->
<!---->
<!--        <div class="nav-header">-->
<!--            <a href="index.html" class="brand-logo">-->
<!--                <img src="https://style.iium.edu.my/images/iium/iium-logo-v2.png" class="user_img" style="max-width: 75%"-->
<!--                     alt="" />-->
<!--            </a>-->
<!--            <div class="nav-control">-->
<!--                <div class="hamburger">-->
<!--                    <span class="line"></span>-->
<!--                    <span class="line"></span>-->
<!--                    <span class="line"></span>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="header">-->
<!--            <div class="header-content">-->
<!--                <nav class="navbar navbar-expand">-->
<!--                    <div class="collapse navbar-collapse justify-content-between">-->
<!--                        <div class="header-left"></div>-->
<!--                        <ul class="navbar-nav header-right">-->
<!--                            <li class="nav-item dropdown notification_dropdown">-->
<!--                                <a class="nav-link bell-link" href="javascript:void(0);">-->
<!--                                    <svg width="20" height="22" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                                        <path-->
<!--                                                d="M16.9026 6.85114L12.4593 10.4642C11.6198 11.1302 10.4387 11.1302 9.59922 10.4642L5.11844 6.85114"-->
<!--                                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />-->
<!--                                        <path fill-rule="evenodd" clip-rule="evenodd"-->
<!--                                              d="M15.9089 19C18.9502 19.0084 21 16.5095 21 13.4384V6.57001C21 3.49883 18.9502 1 15.9089 1H6.09114C3.04979 1 1 3.49883 1 6.57001V13.4384C1 16.5095 3.04979 19.0084 6.09114 19H15.9089Z"-->
<!--                                              stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />-->
<!--                                    </svg>-->
<!--                                </a>-->
<!--                            </li>-->
<!--                            <li class="nav-item ps-3">-->
<!--                                <div class="dropdown header-profile2">-->
<!--                                    <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown"-->
<!--                                       aria-expanded="false">-->
<!--                                        <div class="header-info2 d-flex align-items-center">-->
<!--                                            <div class="header-media">-->
<!--                                                <img src="https://style.iium.edu.my/images/iium/profile.png" alt="" />-->
<!--                                            </div>-->
<!--                                            <div class="header-info">-->
<!--                                                <h6>Ali Bin Rauf</h6>-->
<!--                                                <p>alirauf@live.iium.edu.my</p>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                    <div class="dropdown-menu dropdown-menu-end" style="">-->
<!--                                        <div class="card border-0 mb-0">-->
<!--                                            <div class="card-header py-2">-->
<!--                                                <div class="products">-->
<!--                                                    <img src="https://style.iium.edu.my/images/iium/profile.png" class="avatar avatar-md" alt="" />-->
<!--                                                    <div>-->
<!--                                                        <h6>Ali Bin Rauf</h6>-->
<!--                                                        <span>2312456</span>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div class="card-body px-0 py-2">-->
<!--                                                <a href="#" class="dropdown-item ai-icon">-->
<!--                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"-->
<!--                                                         xmlns="http://www.w3.org/2000/svg">-->
<!--                                                        <path fill-rule="evenodd" clip-rule="evenodd"-->
<!--                                                              d="M11.9848 15.3462C8.11714 15.3462 4.81429 15.931 4.81429 18.2729C4.81429 20.6148 8.09619 21.2205 11.9848 21.2205C15.8524 21.2205 19.1543 20.6348 19.1543 18.2938C19.1543 15.9529 15.8733 15.3462 11.9848 15.3462Z"-->
<!--                                                              stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round"-->
<!--                                                              stroke-linejoin="round" />-->
<!--                                                        <path fill-rule="evenodd" clip-rule="evenodd"-->
<!--                                                              d="M11.9848 12.0059C14.5229 12.0059 16.58 9.94779 16.58 7.40969C16.58 4.8716 14.5229 2.81445 11.9848 2.81445C9.44667 2.81445 7.38857 4.8716 7.38857 7.40969C7.38 9.93922 9.42381 11.9973 11.9524 12.0059H11.9848Z"-->
<!--                                                              stroke="var(--primary)" stroke-width="1.42857" stroke-linecap="round"-->
<!--                                                              stroke-linejoin="round" />-->
<!--                                                    </svg>-->
<!---->
<!--                                                    <span class="ms-2">Profile </span>-->
<!--                                                </a>-->
<!--                                            </div>-->
<!--                                            <div class="card-footer px-0 py-2">-->
<!--                                                <a href="#" class="dropdown-item ai-icon">-->
<!--                                                    <svg class="profle-logout" xmlns="http://www.w3.org/2000/svg" width="18" height="18"-->
<!--                                                         viewBox="0 0 24 24" fill="none" stroke="#ff7979" stroke-width="2" stroke-linecap="round"-->
<!--                                                         stroke-linejoin="round">-->
<!--                                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>-->
<!--                                                        <polyline points="16 17 21 12 16 7"></polyline>-->
<!--                                                        <line x1="21" y1="12" x2="9" y2="12"></line>-->
<!--                                                    </svg>-->
<!--                                                    <span class="ms-2 text-danger">Logout </span>-->
<!--                                                </a>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </nav>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="deznav">-->
<!--            <div class="deznav-scroll">-->
<!--                <ul class="metismenu" id="menu">-->
<!--                    <li class="menu-title" style="font-size: 16px">IIUM Template</li>-->
<!--                    <li>-->
<!--                        <a href="index.html" class="mm-active" aria-expanded="false">-->
<!--                            <div class="menu-icon">-->
<!--                                <img src="https://style.iium.edu.my/images/iconly/light/Home.svg"/>-->
<!--                            </div>-->
<!--                            <span class="nav-text">Home Page</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <main role="main" class="flex-shrink-0">-->
<!--        <div class="content-body">-->
<!--            --><?php //= Breadcrumbs::widget([
//                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
//            ]) ?>
<!--            --><?php //= Alert::widget() ?>
<!--            --><?php //= $content ?>
<!--        </div>-->
<!--    </main>-->
<!---->
<!--    --><?php //$this->endBody() ?>
<!--    --><?php
//    $this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
//        ['depends' => [JqueryAsset::class]]);
//    $this->registerJs(<<<JS
//    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
//    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
//        return new bootstrap.Tooltip(tooltipTriggerEl);
//    });
//JS
//    );
//    ?>
<!--    <script src="https://style.iium.edu.my/vendor/global/global.min.js"></script>-->
<!--    <script src="https://style.iium.edu.my/js/custom.js"></script>-->
<!--    <script src="https://style.iium.edu.my/js/deznav-init.js"></script>-->
<!--    <script src="https://style.iium.edu.my/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>-->
<!--    </body>-->
<!--    </html>-->
<?php //$this->endPage();
