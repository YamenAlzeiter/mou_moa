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



?>




<?php foreach ($modelsPoc as $index => $modelPoc):
    $additionalPoc->renderUpdatedPocFields($form, $modelPoc, $index);
    //id needed but it's not included in get methode ..........sadly
    echo $form->field($modelPoc, "[$index]id", ['template' => "{input}{label}{error}", 'options' => ['class' => 'mb-0']])->hiddenInput(['value' => $modelPoc->id, 'maxlength' => true, 'readonly' => true])->label(false);
endforeach; ?>

<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>



<?php
//
//use common\models\Kcdio;
//use common\models\Poc;
//use yii\bootstrap5\ActiveForm;
//use yii\bootstrap5\Html;
//use yii\helpers\ArrayHelper;
//
///** @var yii\web\View $this */
///** @var common\models\Agreement $model */
///** @var yii\bootstrap5\ActiveForm $form */
//
//$form = ActiveForm::begin(['id' => 'actiontaken', 'validateOnBlur' => false, 'validateOnChange' => false, 'options' => ['enctype' => 'multipart/form-data'],]);
//
//
//
//$additionalPoc = new \common\helpers\pocFieldMaker()
//
//?>




<?php
//$additionalPoc->renderExtraFields($form, $model, '');
//
//if ($model->pi_name_x) {
//    $additionalPoc->renderExtraFields($form, $model, '_x');
//}
//
//if ($model->pi_name_xx) {
//    $additionalPoc->renderExtraFields($form, $model, '_xx');
//}
//?>
<!---->
<?php //= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
<!---->
<?php //ActiveForm::end(); ?>
