<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Status $model */

$this->title = 'Update Status: ' . $model->id;

?>
<div class="status-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
