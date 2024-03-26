<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */

$this->title = 'Update Agreement: ' . $model->id;

?>
<div class="agreement-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
