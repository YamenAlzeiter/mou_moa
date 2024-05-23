<?php

use yii\bootstrap5\Html;
use yii\helpers\Url;
?>
<div class="d-flex justify-content-between align-items-center">
    <h1 class="mb-0 fs-7"><i class = "ti ti-clock "></i> Reminders</h1>
    <?= Html::button('<i class="ti fs-7 ti-plus"></i>',
        [
            'value' => Url::to(['create-reminder']),
            'class' => 'btn btn-outline-dark text-start d-flex align-items-center gap-2 text-capitalize', 'id' => 'modelButton',
            'data-bs-toggle' => "offcanvas", 'data-bs-target' => "#myOffcanvas",'onclick' => 'loadOffcanvasContent(this)',
        ]
    );
    ?>
</div>
<hr class = "border border-black ">
<div class = " flex-column d-flex gap-2 overflow-auto" style="height: 300px; max-height: 300px">
    <?php


    $models = $reminderDataProvider->getModels();
    foreach ($models as $model) {
        echo '<div class="d-flex  align-items-center gap-2 ">';
        echo Html::button('<i class="ti fs-7 ti-clock"></i> Remind '.$model->reminder_before.'  '.$model->type.' before ',
            [
                'value' => Url::to(['update-reminder', 'id' => $model->id]),
                'class' => 'btn btn-outline-dark text-start d-flex flex-fill align-items-center gap-2 text-capitalize',
                'id' => 'modelButton',
                'data-bs-toggle' => "offcanvas",
                'data-bs-target' => "#myOffcanvas",
                'onclick' => 'loadOffcanvasContent(this)',
            ]
        );

        // Delete Button
        echo Html::a('<i class="ti fs-6 ti-trash"></i>',
            ['delete-reminder', 'id' => $model->id], // Controller action and ID
            [
                'class' => 'btn btn-outline-danger',
                'data-confirm' => 'Are you sure you want to delete this reminder?', // Add confirmation
                'data-method' => 'post',
                'data' => ['action' => Url::to(['delete-reminder', 'id' => $model->id]),],
            ]
        );
        echo '</div>';
    }

    ?>
</div>
