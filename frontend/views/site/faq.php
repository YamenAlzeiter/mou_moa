<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'FAQ';
?>

<div class="nav-section">
    <div class="d-flex flex-row justify-content-between w-100">
        <div class="logo">
            <?= Html::a(Html::img(Yii::getAlias('@web') . '/iiumLogo.svg', ['class' => 'logoface']), 'index', ['class' => 'logo']) ?>
        </div>

        <div class="menu">
            <a href="/site/index" class="menu-item active">Home</a>
            <a href="/site/public-index" class="menu-item">Agreements</a>
            <a href="/site/faq" class="menu-item">FAQ</a>
            <a href="#contact" class="menu-item">Contact</a>
            <?php if(Yii::$app->user->isGuest):?>
                <a href="/site/cas-login" class="menu-item">Sign In</a>
            <?php else: ?>
                <form method="post" action="/site/logout">
                    <?= \yii\helpers\Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                    <button type="submit" class="menu-item" style="background: none; border: none; color: inherit; cursor: pointer;">Logout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <div class="mt-5">
        <h2 class="text-white mb-4">FAQ</h2>
        <p>Answers to some questions you might have.</p>
    </div>
</div>

<div class="faq-container">
    <div class="faq-card">
        <div class="faq-list">
            <?php foreach ($dataProvider->models as $faq): ?>
                <div class="faq">
                    <div class="faq-q">
                        <h3><?= htmlspecialchars($faq->question) ?></h3>
                        <i class="ti ti-plus faq-icon fw-5"></i>
                    </div>
                    <div class="faq-a">
                        <p><?= nl2br($faq->answer) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script>
    const faqQuestions = document.querySelectorAll(".faq");
    const faqAnswers = document.querySelectorAll(".faq-a");
    const faqIcons = document.querySelectorAll(".faq-icon");

    faqQuestions.forEach((faqQuestion, index) => {
        faqQuestion.addEventListener('click', () => {
            faqIcons[index].classList.toggle("expand");
            faqAnswers[index].classList.toggle("expand");
        });
    });

    function submitFilter(type) {
        // Set the value of the hidden input
        document.getElementById('faq-type').value = type;
        inbound = document.getElementById('inbound');
        outbound = document.getElementById('outbound');

        if(type === 'I'){
            inbound.classList.add('active');
            outbound.classList.remove('active');
        }else if(type === 'O'){
            inbound.classList.remove('active');
            outbound.classList.add('active');
        }

        document.querySelector('form').submit();
    }
    document.addEventListener('DOMContentLoaded', function() {
        var type = document.getElementById('faq-type').value ;
        var inbound = document.getElementById('inbound');
        var outbound = document.getElementById('outbound');
        if(type === 'I'){
            inbound.classList.add('active');
            outbound.classList.remove('active');

        }else if(type === 'O'){
            inbound.classList.remove('active');
            outbound.classList.add('active');
        }
    });
</script>