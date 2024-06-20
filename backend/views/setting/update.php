<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Agreement $model */

$this->title = 'Update Agreement: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Agreements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agreement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
