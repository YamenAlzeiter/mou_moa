<?php

use common\helpers\builders;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
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
            <h2 class="text-white mb-4">Executed Agreement</h2>
            <p>List of Current Agreement that are Executed.</p>
        </div>
    </div>

    <div class="faq-container">
        <div class="faq-card">

                <div class="mb-4">
                    <?= $this->render('/agreement/_search', ['model' => $searchModel]); ?>
                </div>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'tableOptions' => ['class' => 'table  table-borderless table-striped table-header-flex text-nowrap rounded-3 overflow-hidden'],
                    'summary' => '',
                    'rowOptions' => function ($model) {
                        $build = new builders();
                        return $build->tableProbChanger($model->status, 'OLA') ? ['class' => 'need-action fw-bolder'] : [];
                    },
                    'columns' => [
                        'id',
                        [
                            'attribute' => 'collaboration.col_organization',
                            'contentOptions' => ['class' => 'truncate'],
                        ],
                        [
                            'attribute' => 'created_at',
                            'label' => 'Date',
                            'format' => ['date', 'php:d/m/y'],
                            'enableSorting' => false,
                        ],
                        'collaboration.country',
                        [
                            'label' => 'Champion',
                            'value' => function ($model) {
                                return $model->primaryAgreementPoc ? $model->primaryAgreementPoc->pi_kcdio : null;
                            },
                        ],
                        'sign_date',
                        'end_date',
                        'agreement_type',
                    ],
                ]); ?>

        </div>
    </div>
