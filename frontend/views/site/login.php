<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';

?>


<div id = "main-wrapper">
    <div class = "position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
        <div class = "d-flex align-items-center justify-content-center w-100">
            <div class = "row justify-content-center w-100">
                <div class = "col-md-8 col-lg-6 col-xxl-4 auth-card">
                    <div class = "card mb-0">
                        <div class = "card-body">
                            <div class = "site-login">
                                <h1 class = "text-center"><?= Html::encode($this->title) ?></h1>

                                <p class = "text-center">Please fill out the following fields to login:</p>

                                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                                <?= $form->field($model, 'staff_id')->textInput([
                                    'autofocus' => true, 'placeholder' => 'Enter Staff ID'
                                ])->label(false) ?>

                                <?= $form->field($model,
                                    'password')->passwordInput(['placeholder' => 'Please Enter your Password'])->label(false) ?>


                            </div>
                            <div class = "d-flex align-items-center justify-content-between mb-4">

                                <?= $form->field($model, 'rememberMe',
                                    ['options' => ['class' => 'mb-0']])->checkbox() ?>


                                <?= Html::a('Forgot Password?', ['site/request-password-reset'],
                                    ['class' => 'text-primary fw-medium']) ?>

                            </div>

                            <div class = "form-group mt-4">
                                <?= Html::submitButton('Login', [
                                    'class' => 'btn-iium w-100 py-8 mb-4 rounded-2', 'name' => 'login-button'
                                ]) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
