<?php

use common\models\Agreement;
use yii\widgets\ActiveForm;


/** @var yii\web\View $this */
/** @var common\models\search\AgreementSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>



<?php $form = ActiveForm::begin([

    'method' => 'get',
    'options' => [
        'data-pjax' => 1,
        ['class' => 'row gap-2']
    ]

]); ?>
<div class="d-flex flex-column gap-3">

    <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="table-responsive">
            <?php
            echo $form->field($model, 'applications')->radioList([
                'all' => 'All', 'new_applications' => 'New Applications', 'active_applications' => 'Active Applications',
                'expired_applications' => 'Expired Applications'
            ], [
                'class' => 'd-flex', 'item' => function ($index, $label, $name, $checked, $value) {
                    $checkedAttribute = $checked ? 'checked' : ($index === 0 ? 'checked' : ''); // Check the first radio button by default
                    $radio = '<input type="radio" class="btn-check" name="' . $name . '" id="' . $name . $index . '" value="' . $value . '" autocomplete="off" ' . $checkedAttribute . ' onchange="$(this).closest(\'form\').submit();">';
                    $label = '<label class="btn-bb rounded-3 fs-5 fw-bold font-medium me-2 mb-2 text-nowrap" for="' . $name . $index . '">' . $label . '</label>';
                    return '<div>' . $radio . $label . '</div>';
                }
            ])->label(false);
            ?>
        </div>
    <?php endif; ?>
    <div class="d-flex align-items-center gap-1">

        <?= $form->field($model, 'full_info', ['options' => ['mb-0']])->textInput([
            'class' => 'form-control', // Add class for styling
            'placeholder' => 'Search', // Placeholder text
            'onchange' => '$(this).closest("form").submit();', // Submit form on change
        ])->label(false) ?>

        <?= $form->field($model, 'id', ['options' => ['mb-0']])->textInput([
            'class' => 'form-control', // Add class for styling
            'placeholder' => 'ID', // Placeholder text
            'onchange' => '$(this).closest("form").submit();', // Submit form on change
        ])->label(false) ?>

        <?= $form->field($model, 'agreement_type', ['options' => ['mb-0']])
            ->dropDownList(
                \yii\helpers\ArrayHelper::map(Agreement::find()
                    ->select(['agreement_type'])
                    ->groupBy('agreement_type')
                    ->orderBy('agreement_type')
                    ->asArray()
                    ->all(), 'agreement_type', 'agreement_type'),
                [
                    'class' => 'form-select',
                    'prompt' => 'Pick Type',
                    'onchange' => '$(this).closest("form").submit();'
                ]
            )->label(false)
        ?>


        <?= $form->field($model, 'transfer_to', ['options' => ['mb-0']])->dropDownList(['IO' => 'IO', 'OIL' => 'OIL', 'RMC' => 'RMC'], ['class' => 'form-select', 'prompt' => 'Pick ', // Placeholder text
            'onchange' => '$(this).closest("form").submit();', // Submit form on change
        ])->label(false) ?>


    </div>

</div>


<?php ActiveForm::end(); ?>




