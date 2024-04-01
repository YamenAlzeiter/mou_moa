<?php

use common\models\Kcdio;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\Activities $model */
/** @var yii\widgets\ActiveForm $form */
$type = Yii::$app->user->identity->type;
$activityOptions = [
    'Student Mobility for Credited',
    'Student Mobility Non-Credited',
    'Staff Mobility (Inbound)',
    'Staff Mobility (Outbound)',
    'Seminar/Conference/Workshop/Training',
    'Research',
    'Publication',
    'Consultancy',
    'Any other of Cooperation, Please specify',
    'No Activity, Please specify',
];
$activityOptionsMap = array_combine($activityOptions, $activityOptions);
$templateRadio = '<legend class="col-form-label col-sm-6 pt-0">{label}</legend>{input}{error}';
?>



<?php $form = ActiveForm::begin([
    'fieldConfig' => ['template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],]]); ?>
<section id = "section-1" class = "form-section d-none">
    <h2 class="my-4">Student Mobility for Credited</h2>
    <div class = "row align-items-center">
        <div class = "col-lg-2"><?= $form->field($model, 'type', ['template' => $templateRadio,   'labelOptions' => ['class' => 'form-label']])->radioList(['Inbound' => 'Inbound', 'Outbound' => 'Outbound'])?></div>
        <div class = "col-lg-6">
            <?= $form->field($model, 'number_students')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'name_students')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
    </div>
    <div class = "row">
        <div class = "col-lg-4">
            <?= $form->field($model, 'semester')->dropDownList(['semester 1' => 'semester 1', 'semester 2' => 'semester 2', 'semester 3' => 'semester 3',], ['prompt' => 'Select Semester']) ?>
        </div>
        <div class = "col-lg-4">
            <?= $form->field($model, 'number_students')->textInput(['type' => 'date', 'placeholder' => ''])?>
        </div>
        <div class = "col-lg-4">

        </div>
    </div>

    <div class="mb-4 text-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-dark', 'name' => 'submit', 'value' => 'section-2']) ?>
    </div>
</section>

<section id = "section-2" class = "form-section d-none">
    <h2 class="my-4">Student Mobility for Non-Credited</h2>
    <div class = "row align-items-center">
        <div class = "col-lg-2"><?= $form->field($model, 'type', ['template' => $templateRadio,   'labelOptions' => ['class' => 'form-label']])->radioList(['Inbound' => 'Inbound', 'Outbound' => 'Outbound'])?></div>
        <div class = "col-lg-6">
            <?= $form->field($model, 'number_students')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'name_students')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
    </div>
    <div class = "row">
        <div class = "col-lg-4">
            <?= $form->field($model, 'semester')->dropDownList(['semester 1' => 'semester 1', 'semester 2' => 'semester 2', 'semester 3' => 'semester 3',], ['prompt' => 'Select Semester']) ?>
        </div>
        <div class = "col-lg-4">
            <?= $form->field($model, 'number_students')->textInput(['type' => 'date', 'placeholder' => ''])?>
        </div>
        <div class = "col-lg-4">
            <?= $form->field($model, 'program_name')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
    </div>


    <div class="mb-4 text-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-dark', 'name' => 'submit', 'value' => 'section-2']) ?>
    </div>
</section>

<section id = "section-3" class = "form-section d-none">
    <h2 class="my-4">Staff Mobility (Inbound)</h2>
    <div class = "row align-items-center">
        <div class = "col-lg-6">
            <?= $form->field($model, 'number_of_staff')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
        <div class = "col-lg-6">
            <?= $form->field($model, 'staffs_name')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
    </div>
    <div class="mb-4 text-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-dark', 'name' => 'submit', 'value' => 'section-3']) ?>
    </div>
</section>

<section id = "section-4" class = "form-section d-none">
    <h2 class="my-4">Staff Mobility (Outbound)</h2>
    <div class = "row align-items-center">
        <div class = "col-lg-6">
            <?= $form->field($model, 'number_of_staff')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
        <div class = "col-lg-6">
            <?= $form->field($model, 'staffs_name')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
    </div>
    <div class="mb-4 text-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-dark', 'name' => 'submit', 'value' => 'section-4']) ?>
    </div>
</section>

<section id = "section-5" class = "form-section d-none">
    <h2 class="my-4">Seminar/Conference/Workshop/Training</h2>
    <div class = "row align-items-center">
        <div class = "col-lg-6">
            <?= $form->field($model, 'scwt_name_of_program')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
        <div class = "col-lg-6">
            <?= $form->field($model, 'date_of_program')->textInput(['type' => 'date', 'placeholder' => ''])?>
        </div>
    </div>
    <div class = "row align-items-center">
        <div class = "col-lg-4">
            <?= $form->field($model, 'program_venue')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
        <div class = "col-lg-4">
            <?= $form->field($model, 'participants_number')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
        <div class = "col-lg-4">
            <?= $form->field($model, 'name_participants_involved')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
    </div>
    <div class="mb-4 text-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-dark', 'name' => 'submit', 'value' => 'section-5']) ?>
    </div>
</section>

<section id = "section-6" class = "form-section d-none">
    <h2 class="my-4">Seminar/Conference/Workshop/Training</h2>
    <div class = "row align-items-center">
        <div class = "col-lg-6">
            <?= $form->field($model, 'research_title')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
        <div class = "col-lg-6">

        </div>
    </div>
    <div class="mb-4 text-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-dark', 'name' => 'submit', 'value' => 'section-6']) ?>
    </div>
</section>

<section id = "section-7" class = "form-section d-none">
    <h2 class="my-4">Publication</h2>
    <div class = "row align-items-center">
        <div class = "col-lg-6">
            <?= $form->field($model, 'publication_title')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
        <div class = "col-lg-6">
            <?= $form->field($model, 'publisher')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
    </div>
    <div class="mb-4 text-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-dark', 'name' => 'submit', 'value' => 'section-7']) ?>
    </div>
</section>

<section id = "section-8" class = "form-section d-none">
    <h2 class="my-4">Consultancy</h2>
    <div class = "row align-items-center">
        <div class = "col-lg-6">
            <?= $form->field($model, 'consultancy_name')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
        <div class = "col-lg-6">
            <?= $form->field($model, 'project_duration')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
    </div>
    <div class="mb-4 text-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-dark', 'name' => 'submit', 'value' => 'section-8']) ?>
    </div>
</section>

<section id = "section-9" class = "form-section d-none">
    <h2 class="my-4">Any other of Collaboration</h2>
    <div class = "row align-items-center">
        <div class = "col-lg-6">
            <?= $form->field($model, 'other')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
        <div class = "col-lg-6">
            <?= $form->field($model, 'date')->textInput(['maxlength' => true, 'placeholder' => ''])?>
        </div>
    </div>
    <div class="mb-4 text-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-dark', 'name' => 'submit', 'value' => 'section-9']) ?>
    </div>
</section>

<section id = "section-10" class = "form-section d-none">
    <h2 class="my-4">No Activity</h2>

    <?= $form->field($model, 'justification')->textarea(['maxlength' => true, 'placeholder' => ''])?>

    <div class="mb-4 text-end">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-lg btn-dark', 'name' => 'submit', 'value' => 'section-10']) ?>
    </div>
</section>
<?php ActiveForm::end(); ?>

