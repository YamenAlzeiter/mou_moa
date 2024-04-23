<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\TestingSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="testing-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'fullinfo') ?>


    <?php ActiveForm::end(); ?>

</div>
