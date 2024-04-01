<?php

use common\helpers\builders;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Activities;

/** @var yii\web\View $this */
/** @var common\models\Activities $model */

\yii\web\YiiAsset::register($this);


foreach ($model as $activity) {


    // Check activity type and render content accordingly
    if ($activity->activity_type === 'Student Mobility for Credited') {
        echo '<h4>Student Mobility for Credited</h4>';
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
                    'value' => $activity->name_students,
                ],
                [
                    'label' => 'Semester',
                    'value' => $activity->semester,
                ],
            ],
        ]);


    }elseif($activity->activity_type === 'Student Mobility Non-Credited'){
        echo '<h4>Student Mobility for Non-Credited</h4>';
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
                    'value' => $activity->name_students,
                ],
                [
                    'label' => 'Semester',
                    'value' => $activity->semester,
                ],
                [
                    'label' => 'Program Name',
                    'value' => $activity->program_name,
                ],
            ],
        ]);
    }elseif($activity->activity_type === 'Staff Mobility (Inbound)'){
        echo '<h4>Staff Mobility (Inbound)</h4>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'Number of Staff',
                    'value' => $activity->number_of_staff,
                ],
                [
                    'label' => 'Staff Name',
                    'value' => $activity->staffs_name,
                ],
                [
                    'label' => 'Department Office',
                    'value' => $activity->department_office,
                ],
            ],
        ]);
    }elseif($activity->activity_type === 'Staff Mobility (Outbound)'){
        echo '<h4>Staff Mobility (Outbound)</h4>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'Number of Staff',
                    'value' => $activity->number_of_staff,
                ],
                [
                    'label' => 'Staff Name',
                    'value' => $activity->staffs_name,
                ],
                [
                    'label' => 'Department Office',
                    'value' => $activity->department_office,
                ],
            ],
        ]);
    }elseif($activity->activity_type === 'Seminar/Conference/Workshop/Training'){
        echo '<h4>Seminar/Conference/Workshop/Training</h4>';
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
            ],
        ]);
    }elseif($activity->activity_type === 'Research'){
        echo '<h4>Research</h4>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'Title',
                    'value' => $activity->research_title,
                ],
            ],
        ]);
    }elseif($activity->activity_type === 'Publication'){
        echo '<h4>Publication</h4>';
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
        ]);
    }elseif($activity->activity_type === 'Consultancy'){
        echo '<h4>Consultancy</h4>';
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
            ],
        ]);
    }elseif($activity->activity_type === 'Any other of Cooperation, Please specify'){
        echo '<h4>Others</h4>';
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

            ],
        ]);
    }elseif($activity->activity_type === 'No Activity, Please specify'){
        echo '<h4>No Activities</h4>';
        echo DetailView::widget([
            'model' => $activity,
            'attributes' => [
                [
                    'label' => 'justification',
                    'value' => $activity->justification,
                ],
            ],
        ]);
    }


}
?>
