<?php


use Itstructure\CKEditor\CKEditor;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Faq $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="email-template-form">

    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],
        ],
    ]); ?>

    <?= $form->field($model, 'question')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'answer')->widget(
        CKEditor::className(),
        [
            'preset' => 'basic',
            'options' => ['id' => 'email_template'],
            'clientOptions' => [
                'extraPlugins' => 'insertId', // Register the custom plugin
                'toolbar' => [
                    ['name' => 'clipboard', 'items' => ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']],
                ],
            ],
        ]
    )->label(false) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
