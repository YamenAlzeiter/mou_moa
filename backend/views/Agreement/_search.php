<?php

use common\models\AgreementType;
use common\models\McomDate;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>




<?php $form = ActiveForm::begin([
    'action' => ['index'], 'method' => 'get', 'options' => ['data-pjax' => 1, ['class' => 'row gap-2']]

]); ?>
<div class = "d-flex flex-column gap-3">

    <div class = "table-responsive">
        <?php
        echo $form->field($model, 'applications')->radioList([
            'all' => 'All', 'new_applications' => 'New Applications', 'active_applications' => 'Active Applications',
            'expired_applications' => 'Expired Applications'
        ], [
            'class' => 'd-flex', 'item' => function ($index, $label, $name, $checked, $value) {
                $checkedAttribute = $checked ? 'checked' : ($index === 0 ? 'checked' : ''); // Check the first radio button by default
                $radio = '<input type="radio" class="btn-check" name="'.$name.'" id="'.$name.$index.'" value="'.$value.'" autocomplete="off" '.$checkedAttribute.' onchange="$(this).closest(\'form\').submit();">';
                $label = '<label class="btn-bb btn-dark rounded-3 fs-5 fw-bold font-medium me-2 mb-2 text-nowrap" for="'.$name.$index.'">'.$label.'</label>';
                return '<div>'.$radio.$label.'</div>';
            }
        ])->label(false);
        ?>
    </div>

    <div class = "d-flex gap-3 ">
        <?= $form->field($model, 'full_info', ['options' => ['mb-0']])->textInput([
            'class' => 'form-control', // Add class for styling
            'placeholder' => 'Search', // Placeholder text
            'onchange' => '$(this).closest("form").submit();', // Submit form on change
        ])->label(false) ?>

        <?= $form->field($model, 'agreement_type',
            ['options' => ['mb-0']])->dropDownList(ArrayHelper::map(Agreementtype::find()->all(), 'type', 'type'), [
            'class' => 'form-select', 'prompt' => 'Pick Type', // Placeholder text
            'onchange' => '$(this).closest("form").submit();', // Submit form on change
        ])->label(false) ?>

        <?= $form->field($model, 'endDate', ['options' => ['mb-0']])->dropDownList([
            '1 Year' => '1 Year', '6 Month' => '6 Month', '3 Month' => '3 Month', '2 Month' => '2 Month',
            '1 Month' => '1 Month'
        ], [
            'class' => 'form-select', 'prompt' => 'Pick End Date', // Placeholder text
            'onchange' => '$(this).closest("form").submit();', // Submit form on change
        ])->label(false) ?>
        <?= $form->field($model, 'mcom_date')
            ->dropDownList(ArrayHelper::map((array)McomDate::find()->all(), 'date_from', function ($model) {
                $dateFrom = new DateTime($model->date_from);
                $dateUntil = new DateTime($model->date_until);
                return  $dateFrom->format('y/m/d') .",  " . $dateFrom->format('H:i') . " - " . $dateUntil->format('H:i');
            }), [
                'prompt' => 'Filter MCOM Date', 'class' => 'form-select', 'onchange' => '$(this).closest("form").submit();',
            ])->label(false) ?>
        <buttton class = "btn btn-dark-light btn-sm"><i class = "ti ti-search fs-7"></i></buttton>
    </div>

</div>


<?php ActiveForm::end(); ?>




