<?php

use common\helpers\agreementPocMaker;
use common\models\AgreementType;
use common\models\Poc;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */
/* @var $modelsPoc common\models\AgreementPoc[] */

$this->title = 'Create';
$templateFileInput = '<div class="col-md align-items-center"><div class="col-md-md-2 col-md-form-label">{label}</div>
                        <div class="col-md-md">{input}{error}</div></div>';

// get the default user for person in charge
$defaultPoc = Poc::findOne(['staff_id' => Yii::$app->user->identity->staff_id]);
$additionalPoc = new agreementPocMaker();

?>

<?php $form = ActiveForm::begin([
    'id' => 'create-form',
    'fieldConfig' => [
        'template' => "<div class='form-floating'>{input}{label}{error}</div>",
        'labelOptions' => ['class' => ''],
    ],
]); ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'agreement_type')->dropDownList(
            ArrayHelper::merge(ArrayHelper::map(AgreementType::find()->all(), 'type', 'type'), ['other' => 'Other']),
            [
                'prompt' => 'Select Type',
                'id' => 'agreement-type-dropdown'
            ]
        ) ?>
    </div>
    <div id="other-agreement-type" class="col-md-4">
        <?= $form->field($model, 'agreement_type_other')->textInput(['maxlength' => true, 'disabled' => true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'transfer_to')->dropDownList(
            ['IO' => 'IO', 'RMC' => 'RMC', 'OIL' => 'OIL'],
            [
                'prompt' => 'Select OSC',
                'options' => ['IO' => ['selected' => true]],
                'id' => 'transfer-to-dropdown'
            ]
        ) ?>
    </div>
</div>

<!-- Collaborator details start -->
<div class="row">
    <h4>Collaborator Details</h4>
    <div class="col-md-12">
        <?= $form->field($model, 'col_organization')->textInput(['maxlength' => true, 'value' => 'Kansai University']) ?>
    </div>
    <div class="col-md">
        <?= $form->field($model, 'col_name')->textInput(['maxlength' => true, 'value' => 'Dr. Prof. Keiko IKEDA']) ?>
        <?= $form->field($model, 'col_phone_number')->textInput(['maxlength' => true, 'value' => '81663681174']) ?>
    </div>
    <div class="col-md">
        <?= $form->field($model, 'col_address')->textInput(['maxlength' => true, 'value' => 'Center for International Education, Division of International Affairs']) ?>
        <?= $form->field($model, 'col_email')->textInput(['type' => 'email', 'maxlength' => true, 'value' => 'mi-room@ml.kandai.jp']) ?>
    </div>
</div>
<?= $form->field($model, 'col_collaborators_name')->textarea(['maxlength' => true, 'rows' => 6, 'value' => 'names......']) ?>

<div class="row">
    <div class="col-md-8">
        <?= $form->field($model, 'col_wire_up')->textInput(['maxlength' => true, 'value' => 'wire up .....']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'country')->textInput(['maxlength' => true, 'value' => 'Japan']) ?>
    </div>
</div>
<!-- Collaborator details end -->

<!-- IIUM person in charge details start -->
<div id="poc-container">
    <?php foreach ($modelsPoc as $index => $modelPoc):
        $additionalPoc->renderInitPocFields($form, $modelPoc, $index, $defaultPoc);
    endforeach; ?>
</div>

<div class="d-grid mb-3">
    <?= Html::button('Add person in charge', ['class' => 'btn btn-dark btn-block btn-lg', 'id' => 'add-poc-button']) ?>
</div>
<!-- IIUM person in charge details end -->

<!-- Project information start -->
<h4>Project Information</h4>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'project_title')->textarea(['rows' => 6, 'value' => 'Project Title Title Project']) ?>
    </div>
</div>
<div id="rmc-additional-info" class="row d-none">
    <div class="col-md">
        <?= $form->field($model, 'project_start_date')->textInput(['type' => 'date', 'id' => 'project-start-date']) ?>
    </div>
    <div class="col-md">
        <?= $form->field($model, 'project_end_date')->textInput(['type' => 'date', 'id' => 'project-end-date']) ?>
    </div>
    <div class="col-md">
        <p id="duration" class="mb-0 fw-bold"></p>
    </div>
</div>
<div class="row">
    <div class="col-md">
        <?= $form->field($model, 'grant_fund')->textInput(['maxlength' => true, 'value' => '1000']) ?>
    </div>
    <div class="col-md">
        <?= $form->field($model, 'member')->textInput(['maxlength' => true, 'value' => '10']) ?>
    </div>
</div>
<div id="oil-additional-info" class="row d-none">
    <div class="col-md"><?= $form->field($model, 'ssm')->textInput(['maxlength' => true]) ?></div>
    <div class="col-md"><?= $form->field($model, 'company_profile')->textInput(['maxlength' => true]) ?></div>
</div>
<?= $form->field($model, 'proposal')->textarea(['rows' => 6, 'maxlength' => true, 'value' => 'proposal.....................']) ?>
<?= $form->field($model, 'files_applicant[]', ['template' => $templateFileInput])->fileInput(['multiple' => true])->label('Document') ?>

<div class="modal-footer p-0">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'checked', 'value' => 10]) ?>
</div>
<?php ActiveForm::end(); ?>

<script>
    $(document).ready(function() {
        function calculateDuration() {
            var startDate = new Date($('#project-start-date').val());
            var endDate = new Date($('#project-end-date').val());

            if (startDate && endDate && startDate <= endDate) {
                var duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                $('#duration').text('Duration: ' + duration + ' days');
            } else {
                $('#duration').text('Please select valid start and end dates.');
            }
        }

        $('#project-start-date, #project-end-date').on('change', calculateDuration);
        calculateDuration();
    });

    $(document).ready(function() {
        function populateRoleDropdowns() {
            var selectedValue = $('#transfer-to-dropdown').val();
            var $roleDropdowns = $('.role-dropdown');

            $roleDropdowns.each(function() {
                var $this = $(this);
                var currentValue = $this.val();
                $this.empty();
                $this.append($('<option>', { value: '', text: 'Select Role' }));

                var options = [];
                if (selectedValue === 'IO' || selectedValue === 'OIL') {
                    options = [
                        { value: 'Project Leader', text: 'Project Leader' },
                        { value: 'Member', text: 'Member' }
                    ];
                } else if (selectedValue === 'RMC') {
                    options = [
                        { value: 'Principal Researcher', text: 'Principal Researcher' },
                        { value: 'Co Researcher', text: 'Co Researcher' }
                    ];
                }

                $.each(options, function(index, option) {
                    $this.append($('<option>', { value: option.value, text: option.text }));
                });

                $this.val(currentValue);
            });
        }

        $('#transfer-to-dropdown').on('change', populateRoleDropdowns);
        $('#transfer-to-dropdown').trigger('change');

        $('#add-poc-button').on('click', function() {
            var pocIndex = $('#poc-container .poc-row').length;
            if (pocIndex < 5) {
                var newRow = `<?php $additionalPoc->renderExtraPocFields( $form, $modelPoc);?>`;
                newRow = newRow.replace(/\[pocIndex\]/g, pocIndex);
                newRow = newRow.replace(/AgreementPoc\d*\[pi_/g, 'AgreementPoc[' + pocIndex + '][pi_');
                newRow = newRow.replace(/id="agreementpoc-pocindex/g, 'id="agreementpoc-' + pocIndex);
                newRow = newRow.replace(/field-agreementpoc-pocindex/g, 'field-agreementpoc-' + pocIndex);

                $('#poc-container').append(newRow);

                populateRoleDropdowns();

                $('#create-form').yiiActiveForm('add', {
                    id: 'agreementpoc-' + pocIndex + '-pi_kcdio',
                    name: 'AgreementPoc[' + pocIndex + '][pi_kcdio]',
                    container: '.field-agreementpoc-' + pocIndex + '-pi_kcdio',
                    input: '#agreementpoc-' + pocIndex + '-pi_kcdio',
                    error: '.invalid-feedback',
                    validate: function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {message: "Person in Charge Cannot be Blank"});
                    }
                });

                $('#create-form').yiiActiveForm('add', {
                    id: 'agreementpoc-' + pocIndex + '-pi_name',
                    name: 'AgreementPoc[' + pocIndex + '][pi_name]',
                    container: '.field-agreementpoc-' + pocIndex + '-pi_name',
                    input: '#agreementpoc-' + pocIndex + '-pi_name',
                    error: '.invalid-feedback',
                    validate: function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {message: "Name cannot be blank."});
                    }
                });

                $('#create-form').yiiActiveForm('add', {
                    id: 'agreementpoc-' + pocIndex + '-pi_email',
                    name: 'AgreementPoc[' + pocIndex + '][pi_email]',
                    container: '.field-agreementpoc-' + pocIndex + '-pi_email',
                    input: '#agreementpoc-' + pocIndex + '-pi_email',
                    error: '.invalid-feedback',
                    validate: function (attribute, value, messages, deferred, $form) {
                        yii.validation.email(value, messages, {message: "Email is not a valid email address."});
                        yii.validation.required(value, messages, {message: "Email cannot be blank."});
                    }
                });

                $('#create-form').yiiActiveForm('add', {
                    id: 'agreementpoc-' + pocIndex + '-pi_phone',
                    name: 'AgreementPoc[' + pocIndex + '][pi_phone]',
                    container: '.field-agreementpoc-' + pocIndex + '-pi_phone',
                    input: '#agreementpoc-' + pocIndex + '-pi_phone',
                    error: '.invalid-feedback',
                    validate: function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {message: "Phone number cannot be blank."});
                    }
                });

                $('#create-form').yiiActiveForm('add', {
                    id: 'agreementpoc-' + pocIndex + '-pi_address',
                    name: 'AgreementPoc[' + pocIndex + '][pi_address]',
                    container: '.field-agreementpoc-' + pocIndex + '-pi_address',
                    input: '#agreementpoc-' + pocIndex + '-pi_address',
                    error: '.invalid-feedback',
                    validate: function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {message: "Address cannot be blank."});
                    }
                });

                $('#create-form').yiiActiveForm('add', {
                    id: 'agreementpoc-' + pocIndex + '-pi_role',
                    name: 'AgreementPoc[' + pocIndex + '][pi_role]',
                    container: '.field-agreementpoc-' + pocIndex + '-pi_role',
                    input: '#agreementpoc-' + pocIndex + '-pi_role',
                    error: '.invalid-feedback',
                    validate: function (attribute, value, messages, deferred, $form) {
                        yii.validation.required(value, messages, {message: "Role cannot be blank."});
                    }
                });
            } else {
                Swal.fire({
                    title: "Oops...!",
                    text: "You Can't Add More than 5 Person in Charge.",
                    icon: "error",
                });
            }
        });

        $(document).on('click', '.remove-poc-button', function() {
            var index = $(this).data('index');
            $('#poc-row-' + index).remove();
        });
    });
</script>

<?php
$script = <<< JS
$('#agreement-type-dropdown').change(function() {
    if ($(this).val() === 'other') {
        $('#other-agreement-type input').prop('disabled', false);
    } else {
        $('#other-agreement-type input').prop('disabled', true);
    }
}).change();

$('#transfer-to-dropdown').change(function() {
    if ($(this).val() === 'OIL') {
        $('#oil-additional-info').removeClass('d-none');
        $('#rmc-additional-info').addClass('d-none');
    }else if  ($(this).val() === 'RMC'){
        $('#rmc-additional-info').removeClass('d-none');
        $('#oil-additional-info').addClass('d-none');
    } else {
        $('#oil-additional-info').addClass('d-none');
        $('#rmc-additional-info').addClass('d-none');
    }

    var form = $('#{$form->id}');
    form.yiiActiveForm('validateAttribute', 'model-member');
    form.yiiActiveForm('validateAttribute', 'model-grant_fund');
    form.yiiActiveForm('validateAttribute', 'model-project_title');
    
}).change();

$('#{$form->id}').on('beforeSubmit', function() {
    if ($('#agreement-type-dropdown').val() === 'other') {
        var otherValue = $('#{$model->formName()}-agreement_type_other').val();
        $('#{$model->formName()}-agreement_type').val(otherValue);
    }
});
JS;
$this->registerJs($script);
?>
