<?php

use common\models\Kcdio;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */

$this->title = 'Create';
$templateFileInput = '<div class="col-md align-items-center"><div class="col-md-md-2 col-md-form-label">{label}</div><div class="col-md-md">{input}</div>{error}</div>';
?>

<?php $form = ActiveForm::begin([
    'id' => 'create-form',
    'enableClientValidation' => true,
    'options' => ['data-pjax' => 1, 'enctype' => 'multipart/form-data'], // Add enctype here
    'fieldConfig' => [
        'template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>",
        'labelOptions' => ['class' => ''],
    ],
]); ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'agreement_type')->dropDownList(['MOU (Academic)' => 'MOU (Academic)', 'MOU (Non-Academic)' => 'MOU (Non-Academic)', 'MOA (Academic)' => 'MOA (Academic)', 'MOA (Non-Academic)' => 'MOA (Non-Academic)'], ['prompt' => 'Select Type']) ?>

    </div>
    <div class="col-md-8">
        <?= $form->field($model, 'col_organization')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
</div>


<div class="row">
    <div class="col-md">
        <?= $form->field($model, 'col_name')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
        <?= $form->field($model, 'col_phone_number')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
    <div class="col-md">
        <?= $form->field($model, 'col_address')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
        <?= $form->field($model, 'col_email')->textInput(['type => email', 'maxlength' => true, 'placeholder' => '']) ?>
    </div>
</div>
<?= $form->field($model, 'col_collaborators_name')->textarea(['maxlength' => true, 'placeholder' => '', 'rows' => 6]) ?>


<div class="row">
    <div class="col-md-8">
        <?= $form->field($model, 'col_wire_up')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'country')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
</div>
<h4>Person In Charge Details</h4>

<div class="row">
    <div class="col-md poc">
        <?= $form->field($model, 'needMe')->hiddenInput(['value' => '1'])->label(false) ?>
        <?= $form->field($model, 'pi_name')->hiddenInput(['value' => Yii::$app->user->identity->username])->label(false) ?>
        <?= $form->field($model, 'pi_kulliyyah')->hiddenInput(['value' => Yii::$app->user->identity->type])->label(false) ?>
        <?= $form->field($model, 'pi_email')->hiddenInput(['value' => Yii::$app->user->identity->email])->label(false) ?>
        <?= $form->field($model, 'pi_phone_number')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
</div>

<div id="extra-pi-fields-container"></div>
<button class="btn btn-lg btn-dark text-capitalize mb-3" onclick="handleAdd()" data-clicks="0">Add person in charge
</button>
<?= $form->field($model, 'project_title')->textarea(['rows' => 6]) ?>
<div class="row">
    <div class="col-md">
        <?= $form->field($model, 'grant_fund')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>

    <div class="col-md">
        <?= $form->field($model, 'member')->textInput(['maxlength' => true, 'placeholder' => '']) ?>
    </div>
    <div class="col-md">
        <?= $form->field($model, 'transfer_to')->dropDownList(['IO' => 'IO', 'RMC' => 'RMC', 'OIL' => 'OIL'], ['prompt' => 'select OSC']) ?>
    </div>
</div>


<?php //= $form->field($model, 'sign_date')->textInput() ?>
<!---->
<?php //= $form->field($model, 'end_date')->textInput() ?>



<?= $form->field($model, 'proposal')->textarea(['rows' => 6]) ?>



<?= $form->field($model, 'fileUpload', ['template' => $templateFileInput])->fileInput()->label('Document') ?>

<div class="modal-footer p-0">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'checked', 'value' => 10]) ?>
</div>
<?php ActiveForm::end(); ?>

<script>
     clicks = 0;

    function handleAdd() {
        event.preventDefault();

        if (clicks >= 2) {
            Swal.fire({
                title: "Oops...!",
                text: "You Can't Add More than 2 Person in Charge.",
                icon: "error",
            });
            return;
        }

        const newRow = document.createElement('div');
        newRow.classList.add('row'); // Add the "row" class

        let fieldsHtml;
        switch (clicks + 1) {
            case 1:
                fieldsHtml = `
                <div class="col-md">
                    <?= $form->field($model, 'pi_name_extra')->textInput(['maxlength' => true, 'placeholder' => ''])->label('Person in Charge Name (1)') ?>
                    <?= $form->field($model, 'pi_kulliyyah_extra')->textInput(['maxlength' => true, 'placeholder' => ''])->label('Kulliyyah (1)') ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'pi_email_extra')->textInput(['maxlength' => true, 'placeholder' => ''])->label('Email (1)') ?>
                    <?= $form->field($model, 'pi_phone_number_extra')->textInput(['maxlength' => true, 'placeholder' => ''])->label('Phone Number (1)') ?>
                </div>
            `;
                break;
            case 2:
                fieldsHtml = `
                <div class="col-md">
                    <?= $form->field($model, 'pi_name_extra2')->textInput(['maxlength' => true, 'placeholder' => '', 'required' => true])->label('Person in Charge Name (2)') ?>
                    <?= $form->field($model, 'pi_kulliyyah_extra2')->textInput(['maxlength' => true, 'placeholder' => '', 'required' => true])->label('Kulliyyah (2)') ?>
                </div>
                <div class="col-md">
                    <?= $form->field($model, 'pi_email_extra2')->textInput(['maxlength' => true, 'placeholder' => '', 'required' => true, 'type' => 'email'])->label('Email (2)') ?>
                    <?= $form->field($model, 'pi_phone_number_extra2')->textInput(['maxlength' => true, 'placeholder' => '', 'required' => true])->label('Phone Number (2)') ?>
                </div>
            `;
                break;
        }

        newRow.innerHTML = fieldsHtml;
        $('#extra-pi-fields-container').append(newRow);
        clicks++;
    }

</script>

<script>
    $('#create-form').on('beforeSubmit', function () {
        var $yiiform = $(this);

        var fileInput = $yiiform.find('input[type="file"]'); // Find the file input
        if (fileInput.length && !fileInput[0].files.length) {
            Swal.fire({
                title: "Please Select a File",
                text: "You must select a document to upload.",
                icon: "warning"
            });
            event.preventDefault();
            return;
        }
        $.ajax({
            type: $yiiform.attr('method'),
            url: $yiiform.attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false
        })
            .done(function (data) {
                if (data.success) {
                    console.log('hello ')
                    $.pjax.reload({container: '#p0'});
                    $('#modal').modal('hide'); // Hide modal
                    Swal.fire({
                        title: "Success!",
                        text: "New Record Added.",
                        icon: "success",
                    });
                }
            })
            .fail(function () {
                Swal.fire({
                    title: "Oops..!",
                    text: "Something Went Wrong.",
                    icon: "warning",
                });
            });
        return false;
    });


</script>
