<?php

use common\helpers\builders;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\models\Activities;
/** @var yii\web\View $this */
/** @var common\models\Agreement $model */

$this->title = $model->id;

$build = new builders();
\yii\web\YiiAsset::register($this);
?>

<?php modal::begin([
    'title' => '',
    'id' => 'modal-activity',
    'size' => 'modal-lg',
    'bodyOptions' => ['class' =>'modal-inner-padding-body mt-0'],
    'headerOptions' => ['class' => 'modal-inner-padding justify-content-between'],
    'centerVertical' => true,
    'scrollable' => true,
]);

echo "<div id='modalContent'></div>";

modal::end();
?>


  <div class="d-flex justify-content-between">
      <div class="d-flex gap-3">
          <?=$build->pillBuilder($model->status, 'mb-3');?>
          <span class='text-gray-dark fw-bolder fs-5'><?= $model->agreement_type?></span>
      </div>
      <?php if ($haveActivity):?>
        <?=  \yii\bootstrap5\Html::button(
             'View Activities',
              [
                  'value' => Url::to(['view-activities', 'id' => $model->id]),
                  'class' => 'btn-action',
                  'id' => 'modelButton',
                  'onclick' => "$('#modal-activity').modal('show').find('#modalContent').load($(this).attr('value'), function() {
            // Append the HTML snippet to the modal content
            $('#modalContent').append('');
            
            // Set the modal title
            $('#modal').find('.modal-title').html('<h1 class=\"mb-0\">Activities: $model->id</h1>');
        });"
              ]
          );
          ?>
      <?php endif;?>
  </div>


    <h4>Collaborator Details</h4>
<?php if ($model->col_name == "" || $model->col_name == null):?>
    <div class = "row">
        <div class = "">
            <p class="fw-bolder mb-2"><span class="fw-normal"><?= nl2br($model->col_details)?></span></p>
        </div>
    </div>
<?php else :?>
    <div class = "row">
        <div class = "col-6">
            <p class="fw-bolder mb-2">Name: <span class="fw-normal"><?= $model->col_name?></span></p>
            <p class="fw-bolder mb-2">Phone Number: <span class="fw-normal"><?= $model->col_phone_number?></span></p>
            <p class="fw-bolder mb-2">Collaborators Name: <span class="fw-normal"><?= $model->col_collaborators_name?></span></p>
            <p class="fw-bolder mb-2">Address: <span class="fw-normal"><?= $model->col_address?></span></p>
        </div>
        <div class = "col-6">
            <p class="fw-bolder mb-2">Organization: <span class="fw-normal"><?= $model->col_organization?></span></p>
            <p class="fw-bolder mb-2">Email Address: <a href=" mailto: <?= $model->col_email?>" class="fw-normal"><?= $model->col_email?></a></p>
            <p class="fw-bolder mb-2">Wire Up: <span class="fw-normal"><?= $model->col_wire_up?></span></p>
        </div>
    </div>
<?php endif;?>

    <h4>Person In Charge Details</h4>
   <?php if ($model->pi_name == "" || $model->pi_name == null):?>
    <p class="fw-bolder mb-2">Details <span class="fw-normal"><?= nl2br($model->pi_details)?></span></p>
    <?php else :?>
    <div class="row">
    <div class="col-md-6">
    <p class="fw-bolder mb-2">Name: <span class="fw-normal"><?= $model->pi_name?></span></p>
    <p class="fw-bolder mb-2">Kulliyyah: <span class="fw-normal"><?= $model->pi_kulliyyah?></span></p>
    </div>
    <div class="col-md-6">
        <p class="fw-bolder mb-2">Phone Number: <span class="fw-normal"><?= $model->pi_phone_number?></span></p>
        <p class="fw-bolder mb-2">Email Address: <a href="mailto:<?= $model->pi_email?>" class="fw-normal"><?= $model->pi_email?></a></p>
    </div>
    </div>
    <?php endif;?>


    <h4> Details</h4>
    <p class="fw-bolder mb-2">Fund: <span class="fw-normal"><?= $model->grant_fund?></span></p>
    <p class="fw-bolder mb-2">Number of Members: <span class="fw-normal"><?= $model->member?></span></p>
    <p class="fw-bolder mb-2">Proposal: <span class="fw-normal"><?= $model->proposal?></span></p>
    <p class="fw-bolder mb-2">Project Title: <span class="fw-normal"><?= $model->project_title?></span></p>




    <p class="fw-bolder mb-2">ssm: <span class="fw-normal"><?= $model->ssm?></span></p>
    <p class="fw-bolder mb-2">company_profile: <span class="fw-normal"><?= $model->company_profile?></span></p>

    <p class="fw-bolder mb-2">Project Title: <span class="fw-normal"><?= $model->mcom_date?></span></p>

    <p class="fw-bolder mb-2">Sign Data: <span class="fw-normal"><?= $model->sign_date?></span></p>
    <p class="fw-bolder mb-2">End Data: <span class="fw-normal"><?= $model->end_date?></span></p>

<?php if ($model->doc_applicant != null):?>
    <h4>Files</h4>
    <?php endif;?>
<div class="d-flex gap-3">
    <?php echo $build->downloadLinkBuilder($model->doc_applicant, 'Init Document'); ?>
    <?php echo $build->downloadLinkBuilder($model->doc_draft, 'first Draft'); ?>
    <?php echo $build->downloadLinkBuilder($model->doc_newer_draft, 'Newer Draft'); ?>
    <?php echo $build->downloadLinkBuilder($model->doc_re_draft, 'Draft'); ?>
    <?php echo $build->downloadLinkBuilder($model->doc_extra, 'Extra Document'); ?>
</div>