<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'IIUM Memorandum';
?>


<nav>
    <div class="logo">
        <?= Html::a(Html::img(Yii::getAlias('@web') . '/iiumLogo.svg', ['class' => 'logoface']), 'index', ['class' => 'logo']) ?>
        <a href="http://applicant.iium/index" target="_blank"><h2 class="sitename">Memorandum Program</h2></a>
    </div>

    <div class="menu">
        <a href="/site/index" class="menu-item active">Home</a>
        <a href="/site/public-index" class="menu-item">Agreements</a>
        <a href="/site/faq" class="menu-item">FAQ</a>
        <a href="#contact" class="menu-item">Contact</a>
        <a href="/site/cas-login" class="primary-button">Sign In</a>
    </div>
</nav>



<div class="hero">
    <div class="hero-text content-container">
        <h1 class="text-white">Memorandum <span class="color-effect">IIUM</span><br>Web Application</h1>
        <p class="subtitle">Lorem ipsum dolor sit amet, consectetur adipisicing elit.?<br>Lorem ipsum dolor sit amet</p>
<!--        <div class="hero-cta">-->
<!--            <a href="#toolbar" onclick="highlightToolbar()" class="primary-button">Sign In</a>-->
<!--            <a href="#how" class="secondary-button">Active Agreements</a>-->
<!--        </div>-->
        <div class="hero-scroll">
            <svg width="23" height="33" viewBox="0 0 23 33" fill="none">
                <rect x="0.767442" y="0.767442" width="20.7209" height="31.4651" rx="10.3605" stroke="var(--secondary)" stroke-width="1.53488"/>
                <rect x="9" y="8" width="4" height="8" rx="2" fill="var(--secondary)"/>
            </svg>
            <p class="sub">Scroll to see more sections</p>
        </div>
    </div>
</div>
<main>
    <div>
        <h2>Templates</h2>
        <div class="row my-4 d-flex justify-content-between">
            <div class="col-12 col-md-auto mb-2"><a href="https://office.iium.edu.my/ola/wp-content/uploads/sites/12/2024/01/Letter-of-Intent-OLA.docx" class="download-button w-100"><i class="ti ti-brand-office me-2"></i> Letter of Intent-OLA</a></div>
            <div class="col-12 col-md-auto mb-2"><a href="https://www.iium.edu.my/media/62570/MOU%20TEMPLATE-OLA-2020.docx" class="download-button w-100"><i class="ti ti-brand-office me-2"></i> MOU Template</a></div>
            <div class="col-12 col-md-auto mb-2"><a href="https://www.iium.edu.my/media/62571/MOA%20TEMPLATE-OLA-2020.docx" class="download-button w-100"><i class="ti ti-brand-office me-2"></i> MOA Template</a></div>
        </div>
    </div>
</main>