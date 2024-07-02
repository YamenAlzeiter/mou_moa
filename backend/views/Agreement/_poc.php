<?php

use common\models\Kcdio;
use common\models\Poc;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var common\models\AgreementPoc $modelsPoc */
/** @var yii\bootstrap5\ActiveForm $form */

$form = ActiveForm::begin(['id' => 'actiontaken', 'validateOnBlur' => false, 'validateOnChange' => false, 'options' => ['enctype' => 'multipart/form-data'],]);



$additionalPoc = new \common\helpers\agreementPocMaker();
foreach ($modelsPoc as $index => $modelPoc) {
    $roleData[$index] = $modelPoc->pi_role;
}


?>



<div id="poc-container">
<?php foreach ($modelsPoc as $index => $modelPoc):
    $additionalPoc->renderUpdatedPocFields($form, $modelPoc, $index);
    //id needed but it's not included in get methode ..........sadly
    echo $form->field($modelPoc, "[$index]id", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])->hiddenInput(['value' => $modelPoc->id, 'maxlength' => true, 'readonly' => true])->label(false);
endforeach; ?>
</div>
<div class="d-grid mb-3">
    <?= Html::button('Add person in charge', ['class' => 'btn btn-dark btn-block btn-lg', 'id' => 'add-poc-button']) ?>
</div>
<?= $form->field($model,'pi_delete_ids')->hiddenInput()->label(false)?>
<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

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
        var roleData = <?= json_encode($roleData); ?>;
        function populateRoleDropdowns() {
            var selectedValue = '<?= $model->transfer_to?>';
            var $roleDropdowns = $('.role-dropdown');

            $roleDropdowns.each(function(index) {
                var $this = $(this);
                var currentValue = roleData[index];
                $this.empty();
                $this.append($('<option>', { value: '', text: 'Select Role' }));

                var options = [];
                if (selectedValue === 'IO' || selectedValue === 'OIL') {
                    options = [
                        { value: 'Project Leader', text: 'Project Leader'},
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

// Call the function when the page loads to initialize the dropdowns
        $(document).ready(function() {
            populateRoleDropdowns();

            // Attach the function to the change event of the transfer-to dropdown
            $('#transfer-to-dropdown').change(function() {
                populateRoleDropdowns();
            });
        });

        $('#transfer-to-dropdown').on('change', populateRoleDropdowns);
        $('#transfer-to-dropdown').trigger('change');

        $('#add-poc-button').on('click', function() {

            var pocIndex = $('#poc-container .poc-row').length;
            console.log(pocIndex)
            if (pocIndex < 5) {
                var newRow = `<?php $additionalPoc->renderExtraPocFields($form, new \common\models\AgreementPoc());?>`;
                newRow = newRow.replace(/\[pocIndex\]/g, pocIndex);
                newRow = newRow.replace(/AgreementPoc\d*\[pi_/g, 'AgreementPoc[' + pocIndex + '][pi_');
                newRow = newRow.replace(/id="agreementpoc-pocindex/g, 'id="agreementpoc-' + pocIndex);
                $('#poc-container').append(newRow);
                pocIndex++;

                populateRoleDropdowns();
            } else {
                Swal.fire({
                    title: "Oops...!",
                    text: "You Can't Add More than 5 Person in Charge.",
                    icon: "error",
                });
            }
        });
        var deletedPocIds = [];

        $(document).on('click', '.remove-poc-button', function() {
            var index = $(this).data('index');
            var pocId = $('input[name="AgreementPoc[' + index + '][id]"]').val();
            if (pocId) {
                deletedPocIds.push(pocId);
            }

            $('#poc-row-' + index).remove();
            $('#agreement-pi_delete_ids').val(deletedPocIds.join(','));

        });
    });
</script>