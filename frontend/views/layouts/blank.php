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

    <?= $content ?>
<div class="footer" id="footer">
    <div class="logo">
        <?= Html::a(Html::img(Yii::getAlias('@web') . '/iiumLogo.svg', ['class' => 'logoface']), 'index', ['class' => 'logo']) ?>
        <a href="http://applicant.iium/" target="_blank"><h2 class="sitename reversed">Memorandum Program</h2></a>
    </div>

    <div class="footer-cols" id="contact">
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
        <div class="footer-col">
            <a href="/site/index" class="menu-item active">Home</a>
            <a href="/site/public-index" class="menu-item">Agreements</a>
            <a href="/site/faq" class="menu-item">FAQ</a>
            <a href="#contact" class="menu-item">Contact</a>
            <a href="/site/cas-login" class="menu-item">Sign In</a>
        </div>
        <div class="footer-col">

        </div>
    </div>
    <p class="sub">&copy; 2024 IIUM. All rights reserved.</p>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();


