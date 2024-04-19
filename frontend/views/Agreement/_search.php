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
<div class = "d-flex flex-column gap-3">

<?php if(!Yii::$app->user->isGuest) :?>
    <div class="table-responsive">
        <?php
        echo $form->field($model, 'applications')->radioList([
            'all' => 'All', 'new_applications' => 'New Applications', 'active_applications' => 'Active Applications',
            'expired_applications' => 'Expired Applications'
        ], [
            'class' => 'd-flex', 'item' => function ($index, $label, $name, $checked, $value) {
                $checkedAttribute = $checked ? 'checked' : ($index === 0 ? 'checked' : ''); // Check the first radio button by default
                $radio = '<input type="radio" class="btn-check" name="'.$name.'" id="'.$name.$index.'" value="'.$value.'" autocomplete="off" '.$checkedAttribute.' onchange="$(this).closest(\'form\').submit();">';
                $label = '<label class="btn-bb btn-outline-dark fs-5 fw-bold font-medium me-2 mb-2 text-nowrap" for="'.$name.$index.'">'.$label.'</label>';
                return '<div>'.$radio.$label.'</div>';
            }
        ])->label(false);
        ?>
    </div>
<?php endif;?>
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




