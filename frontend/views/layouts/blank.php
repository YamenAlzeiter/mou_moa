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

<nav>
    <div class="logo">
        <?= Html::a(Html::img(Yii::getAlias('@web') . '/iiumLogo.svg', ['class' => 'logoface']), 'index', ['class' => 'logo']) ?>
        <a href="http://applicant.iium/" target="_blank"><h2 class="sitename">Memorandum Program</h2></a>
    </div>

    <div class="menu">
        <a href="#home" class="menu-item active">Home</a>
        <a href="/site/public-index" class="menu-item">Agreements</a>
        <a href="#about" class="menu-item">Templates</a>
        <a href="#about" class="menu-item">FAQ</a>
        <a href="#contact" class="menu-item">Contact</a>
        <a href="/site/login" class="primary-button">Sign In</a>
    </div>
</nav>


    <?= $content ?>


<div class="footer" id="footer">
    <div class="logo">
        <?= Html::a(Html::img(Yii::getAlias('@web') . '/iiumLogo.svg', ['class' => 'logoface']), 'index', ['class' => 'logo']) ?>
        <a href="http://applicant.iium/" target="_blank"><h2 class="sitename reversed">Memorandum Program</h2></a>
    </div>
    <div class="footer-cols">


        <div class="footer-col">
         <h4 class="fw-bolder">
             Contact Us:
         </h4>
            <p>
                <span class="fw-bolder">OFFICE FOR STRATEGY AND INSTITUTIONAL CHANGE (OSIC)</span><br>
                <span>Level 3, Muhammad Abdul-rauf Building,</span><br>
                P.O. Box 10,<br>
                50728 Kuala Lumpur <br>
                <span class="fw-bolder">Phone:</span> +6 03 6421 5851 <br>
                <span class="fw-bolder">Email:</span> qaiium@iium.edu.my
            </p>
        </div>
    </div>

</div>


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
