<?php

namespace common\components;

use yii\base\Widget;
use yii\helpers\Html;

class ActivityWidget extends Widget
{
    public $title;
    public $headerText;
    public $activity;

    public function run()
    {
        $title = htmlspecialchars($this->title);
        $headerText = htmlspecialchars($this->headerText);
        $activityId = $this->activity ? $this->activity->id : '';

        // Start building the output
        $output = '
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-3">
                    <h4>' . $headerText . '</h4> <!-- Dynamic header text -->
                    <i class="ti ti-info-circle fs-7 text-dark" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="' . $title . '"></i>
                </div>';

        if (!empty($activityId)) {
            $output .= '
                <div>
                    ' . Html::button('<i class="ti ti-trash fs-5"></i>', [
                    'class' => 'btn mb-2 delete-button',
                    'data-id' => $activityId,
                ]) . '
                </div>';
        }

        $output .= '
            </div>';

        return $output;
    }
}
?>
