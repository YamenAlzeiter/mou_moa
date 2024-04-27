<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\EmailTemplate $model */

$this->title = $model->id;
\yii\web\YiiAsset::register($this);
?>
<div class="email-template-view">
    <div class="p-4 border border-2 border-black rounded-2">
        <h6 class="mb-0 text-black">
            <span class="fs-2 d-block text-decoration-underline mb-2">Body</span>
            <?= $model->body?></h6>
    </div>
</div>
