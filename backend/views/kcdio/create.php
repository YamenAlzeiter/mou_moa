<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Kcdio $model */

$this->title = 'Create Kcdio';
$this->params['breadcrumbs'][] = ['label' => 'Kcdios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kcdio-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
