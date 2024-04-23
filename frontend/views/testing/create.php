<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Testing $model */

$this->title = 'Create Testing';

?>
<div class="testing-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
