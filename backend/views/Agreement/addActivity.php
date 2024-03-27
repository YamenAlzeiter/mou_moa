<?php

use common\models\Kcdio;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

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
    'fieldConfig' => ['template' => "<div class='form-floating mb-3'>{input}{label}{error}</div>", 'labelOptions' => ['class' => ''],],]); ?>

    <div class = "row">
        <div class = "col-lg-6"><?= $form->field($model, 'name'        )->textInput(['maxlength' => true, 'placeholder' => ''])?></div>
        <div class = "col-lg-6"><?= $form->field($model, 'staff_number')->textInput(['maxlength' => true, 'placeholder' => ''])?></div>
    </div>

    <div class = "row">
        <div class = "col-lg-4"><?= $form->field($model, 'kcdio')->dropDownList(ArrayHelper::map(Kcdio::find()->all(), 'tag','kcdio'), ['prompt' => 'Select KCDIO', 'onchange' => 'loadOrganizations(this.value)',]) ?></div>
        <div class = "col-lg-4"><?= $form->field($model, 'mou_moa')->dropDownList([], ['prompt' => 'Select Organization']) ?></div>
        <div class = "col-lg-4"><?= $form->field($model, 'activity_type')->dropDownList($activityOptionsMap, ['prompt' => 'Select Activity Type']) ?></div>
    </div>

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



<script>
    $(document).ready(function() {
        // Define a map of activity types to section numbers
        var sectionMap = {
            'Student Mobility for Credited': '1',
            'Student Mobility Non-Credited': '2',
            'Staff Mobility (Inbound)': '3',
            'Staff Mobility (Outbound)': '4',
            'Seminar/Conference/Workshop/Training': '5',
            'Research': '6',
            'Publication': '7',
            'Consultancy': '8',
            'Any other of Cooperation, Please specify': '9',
            'No Activity, Please specify': '10'
        };

        // Function to toggle visibility based on dropdown selection
        $('#activities-activity_type').change(function() {
            var selectedOption = $(this).val();

            // Get the corresponding section number from the map
            var sectionNumber = sectionMap[selectedOption];
            // Hide all sections
            $('section.form-section').addClass('d-none');
            // Show the section corresponding to the selected option
            $('#section-' + sectionNumber).removeClass('d-none');

        });

    });
</script>
<!--$.ajax({-->
<!--url: '/agreement/add-activity', // Change this to the actual URL of your action-->
<!--type: 'POST',-->
<!--data: { scenario: 'section-'+sectionNumber },-->
<!--success: function(response) {-->
<!--console.log('Scenario set successfully'+ response);-->
<!--},-->
<!--error: function(xhr, status, error) {-->
<!--console.error('Error setting scenario: ' + error);-->
<!--}-->
<!--});-->

<script>
    function loadOrganizations(kcdioValue) {

        var userType = '<?= Yii::$app->user->identity->type ?>'; // Get user type
        $.ajax({
            url: '/agreement/get-organization',
            type: 'POST',
            data: { kcdio: kcdioValue, userType: userType },
            success: function(response) {
                var options = response.html;
                $('#activities-mou_moa').html(options);
            }
        });
    }
</script>


