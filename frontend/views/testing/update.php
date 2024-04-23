<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Testing $model */

$this->title = 'Update Testing: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Testings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="testing-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
