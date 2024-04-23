<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Kcdio $model */

$this->title = 'Create KCDIO';

?>
<div class="kcdio-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
