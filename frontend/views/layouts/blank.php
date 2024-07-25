<?php

/** @var yii\web\View $this */
/** @var string $content */

use frontend\assets\landing;
use yii\helpers\Html;

landing::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <link rel="shortcut icon" type="image/png" href="https://style.iium.edu.my/images/iium/iium-logo.png">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<header>
    <!--        <a href="" class="logo"><img src="https://shenliktech.com/shen/assets/img/logo.png" alt=""> <span>Shenlik Tech.</span></a>-->
    <?= Html::a(Html::img(Yii::getAlias('@web') . '/iiumLogo.svg', ['class' => 'logo']), 'index', ['class' => 'logo']) ?>

    <ul class="navbarr">
        <li><a href="#home" class="active">Home</a></li>
        <li><a href="/site/public-index"">Agreements</a></li>
        <li><a href="#about">About Us</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>

    <div class="mainn">
        <a href="/site/login" class="signin-btn">Sign In</a>
        <bi class='ti ti-list' id="menu-icon"></bi>
    </div>
</header>

    <?= $content ?>




<script>
    let menu = document.querySelector('#menu-icon');
    let navbar = document.querySelector('.navbarr');

    menu.onclick = () =>
    {
        menu.classList.toggle('bi-x');
        navbar.classList.toggle('open');
    }



    window.addEventListener('scroll', function() {
        let scrollPosition = window.pageYOffset;
        let parallaxElements = document.querySelectorAll('.parallax');

        parallaxElements.forEach(function(element) {
            let speed = element.getAttribute('data-speed');
            element.style.backgroundPositionY = (scrollPosition * speed) + 'px';
        });
    });
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
