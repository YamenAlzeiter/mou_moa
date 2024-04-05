<?php

use common\models\McomDate;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>



<?php $form = ActiveForm::begin([
    'action' => [''], 'method' => 'get', 'options' => ['class' => 'row gap-2']
]); ?>
<div class = "mt-4 d-flex flex-column gap-3">

    <div class = "d-flex gap-3">
        <?= $form->field($model, 'full_info', ['options' => ['mb-0']])->textInput([
            'class' => 'form-control', // Add class for styling
            'placeholder' => 'Search', // Placeholder text
            'onchange' => '$(this).closest("form").submit();', // Submit form on change
        ])->label(false) ?>

        <?= $form->field($model, 'agreement_type', ['options' => ['mb-0']])->dropDownList([
            'MOU' => 'MOU', 'MOUA' => 'MOUA', 'MOA' => 'MOA', 'MOAA' => 'MOAA'
        ], [
            'class' => 'form-select', 'prompt' => 'Pick Type', // Placeholder text
            'onchange' => '$(this).closest("form").submit();', // Submit form on change
        ])->label(false) ?>

        <?= $form->field($model, 'mcom_date')->dropDownList(ArrayHelper::map((array) McomDate::find()->all(), 'date',
            'date'), [
            'prompt' => 'Select MCOM Date', 'class' => 'form-select', 'onchange' => '$(this).closest("form").submit();',
        ])->label(false) ?>
    </div>

</div>


<?php ActiveForm::end(); ?>




