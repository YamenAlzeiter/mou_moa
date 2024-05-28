<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ResetPasswordForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Reset password';
?>


<div id="main-wrapper">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="col-md-8 col-lg-6 col-xxl-4 auth-card">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="site-login">
                                <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

                                <p class="text-center">Please fill out the following fields to reset your password:</p>

                                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                            </div>

                            <div class="form-group mt-4">
                                <?= Html::submitButton('Send', ['class' => 'btn-iium w-100 py-8 mb-4 rounded-2']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
