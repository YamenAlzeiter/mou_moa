<?php

use common\helpers\builders;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;
use common\models\Activities;

/** @var yii\web\View $this */
/** @var common\models\Activities $model */

\yii\web\YiiAsset::register($this);


foreach ($model as $activity) {

    $title = "<p>Created By: " .$activity->name. " </p> <p> Number: " . $activity->staff_number . "</p>";
    // Check activity type and render content accordingly
    if ($activity->activity_type === 'Student Mobility for Credited') {
        
        echo '<div class="d-flex gap-3"> <h4>Student Mobility for Credited</h4> <i class="ti ti-info-circle fs-7 text-dark" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="'.htmlspecialchars($title).'"></i></div>';


        // Render author name only
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'Type',
                    'value' => $activity->type,
                ],
                [
                    'label' => 'Number of Students',
                    'value' => $activity->number_students,
                ],
                [
                    'label' => 'Name of Students',
                    'value' => nl2br($activity->name_students),
                    'format' => 'raw'
                ],
                [
                    'label' => 'Semester',
                    'value' => $activity->semester,
                ],
            ],'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);


    }elseif($activity->activity_type === 'Student Mobility Non-Credited'){
        echo '<div class="d-flex gap-3"> <h4>Student Mobility for Non-Credited</h4> <i class="ti ti-info-circle fs-7 text-dark" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="'.htmlspecialchars($title).'"></i></div>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'Type',
                    'value' => $activity->non_type,
                ],
                [
                    'label' => 'Number of Students',
                    'value' => $activity->non_number_students,
                ],
                [
                    'label' => 'Name of Students',
                    'value' => nl2br($activity->non_name_students),
                    'format' => 'raw'
                ],
                [
                    'label' => 'Program Name',
                    'value' => $activity->non_program_name,
                ],
            ],'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    }elseif($activity->activity_type === 'Staff Mobility (Inbound)'){
        echo '<div class="d-flex gap-3"> <h4>Staff Mobility (Inbound)</h4> <i class="ti ti-info-circle fs-7 text-dark" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="'.htmlspecialchars($title).'"></i></div>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'Number of Staff',
                    'value' => $activity->in_number_of_staff,
                ],
                [
                    'label' => 'Staff Name',
                    'value' => $activity->in_staffs_name,
                ],
                [
                    'label' => 'Department Office',
                    'value' => $activity->in_department_office,
                ],
            ],'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    }elseif($activity->activity_type === 'Staff Mobility (Outbound)'){
        echo '<div class="d-flex gap-3"> <h4>Staff Mobility (Outbound)</h4> <i class="ti ti-info-circle fs-7 text-dark" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="'.htmlspecialchars($title).'"></i></div>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'Number of Staff',
                    'value' => $activity->out_number_of_staff,
                ],
                [
                    'label' => 'Staff Name',
                    'value' => $activity->out_staffs_name,
                ],
            ],'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    }elseif($activity->activity_type === 'Seminar/Conference/Workshop/Training'){
        echo '<div class="d-flex gap-3"> <h4>Seminar/Conference/Workshop/Training</h4> <i class="ti ti-info-circle fs-7 text-dark" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="'.htmlspecialchars($title).'"></i></div>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'SCWT Program Name',
                    'value' => $activity->scwt_name_of_program,
                ],
                [
                    'label' => 'Program Date',
                    'value' => $activity->date_of_program,
                ],
                [
                    'label' => 'Venue',
                    'value' => $activity->program_venue,
                ],
                [
                    'label' => 'Number of Participants',
                    'value' => $activity->participants_number,
                ],
                [
                    'label' => 'Participants Names',
                    'value' => $activity->name_participants_involved,
                ],
            ],'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    }elseif($activity->activity_type === 'Research'){
        echo '<div class="d-flex gap-3"> <h4>Research</h4> <i class="ti ti-info-circle fs-7 text-dark" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="'.htmlspecialchars($title).'"></i></div>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'Title',
                    'value' => $activity->research_title,
                ],
            ],'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    }elseif($activity->activity_type === 'Publication'){
        echo '<div class="d-flex gap-3"> <h4>Publication</h4> <i class="ti ti-info-circle fs-7 text-dark" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="'.htmlspecialchars($title).'"></i></div>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'Title Publication',
                    'value' => $activity->publication_title,
                ],
                [
                    'label' => 'Publisher',
                    'value' => $activity->publisher,
                ],
            ],
            'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
        echo '</div>';
    }elseif($activity->activity_type === 'Consultancy'){
        echo '<div class="d-flex gap-3"> <h4>Consultancy</h4> <i class="ti ti-info-circle fs-7 text-dark" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="'.htmlspecialchars($title).'"></i></div>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'Consultancy Name',
                    'value' => $activity->consultancy_name,
                ],
                [
                    'label' => 'Project Duration',
                    'value' => $activity->project_duration,
                ],
            ],'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    }elseif($activity->activity_type === 'Any other of Cooperation, Please specify'){
        echo '<div class="d-flex gap-3"> <h4>Others</h4> <i class="ti ti-info-circle fs-7 text-dark" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="'.htmlspecialchars($title).'"></i></div>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'other',
                    'value' => $activity->other,
                ],
                [
                    'label' => 'date',
                    'value' => $activity->date,
                ],
            ],'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    }elseif($activity->activity_type === 'No Activity, Please specify'){
        echo '<div class="d-flex gap-3"> <h4>No Activities</h4> <i class="ti ti-info-circle fs-7 text-dark" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title="'.htmlspecialchars($title).'"></i></div>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'justification',
                    'value' => $activity->justification,
                ],
            ],'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    }


}
?>
<?php
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
    ['depends' => [JqueryAsset::class]]);
$this->registerJs(<<<JS
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
JS
);
?>