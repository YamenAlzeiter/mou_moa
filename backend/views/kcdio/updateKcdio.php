<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Kcdio $model */

$this->title = 'Update KCDIO: ' . $model->id;

?>
<div class="kcdio-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
