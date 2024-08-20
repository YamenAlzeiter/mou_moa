<?php

use common\components\ActivityWidget;
use common\helpers\builders;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;
use yii\widgets\DetailView;
use common\models\Activities;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var common\models\Activities $model */

YiiAsset::register($this);
Pjax::begin(['id' => 'pjax-container']);

foreach ($model as $activity) {

    $title = "<p>Created By: " .$activity->name. " </p> <p> email: " . $activity->staff_email . "</p>";
    // Check an activity type and render content accordingly
    if ($activity->activity_type === 'Student Mobility for Credited') {

        echo ActivityWidget::widget([
            'title' => $title,
            'headerText' => $activity->activity_type,
            
        ]);
        echo DetailView::widget([
            'model' => $activity, 'attributes' => [
                [
                    'label' => 'Type', 'value' => $activity->type,
                ], [
                    'label' => 'Number of Students', 'value' => $activity->number_students,
                ], [
                    'label' => 'Name of Students', 'value' => nl2br($activity->name_students), 'format' => 'raw'
                ], [
                    'label' => 'Semester', 'value' => $activity->semester,
                ], [
                    'label' => 'Year', 'value' => $activity->year
                ]
            ], 'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);


    } elseif ($activity->activity_type === 'Student Mobility Non-Credited') {
        echo ActivityWidget::widget([
            'title' => $title,
            'headerText' => $activity->activity_type,
            
        ]);
        echo DetailView::widget([
            'model' => $activity, 'attributes' => [
                [
                    'label' => 'Type', 'value' => $activity->non_type,
                ], [
                    'label' => 'Number of Students', 'value' => $activity->non_number_students,
                ], [
                    'label' => 'Name of Students', 'value' => nl2br($activity->non_name_students), 'format' => 'raw'
                ], [
                    'label' => 'Program Name', 'value' => $activity->non_program_name,
                ],
            ], 'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    } elseif ($activity->activity_type === 'Staff Mobility (Inbound)') {
        echo ActivityWidget::widget([
            'title' => $title,
            'headerText' => $activity->activity_type,
            
        ]);
        echo DetailView::widget([
            'model' => $activity, 'attributes' => [
                [
                    'label' => 'Number of Staff', 'value' => $activity->in_number_of_staff,
                ], [
                    'label' => 'Staff Name', 'value' => $activity->in_staffs_name,
                ],
            ], 'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    } elseif ($activity->activity_type === 'Staff Mobility (Outbound)') {
        echo ActivityWidget::widget([
            'title' => $title,
            'headerText' => $activity->activity_type,
            
        ]);

        echo DetailView::widget([
            'model' => $activity, 'attributes' => [
                [
                    'label' => 'Number of Staff', 'value' => $activity->out_number_of_staff,
                ], [
                    'label' => 'Staff Name', 'value' => $activity->out_staffs_name,
                ],

            ], 'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    } elseif ($activity->activity_type === 'Seminar/Conference/Workshop/Training') {
        echo ActivityWidget::widget([
            'title' => $title,
            'headerText' => $activity->activity_type,
            
        ]);
        echo DetailView::widget([
            'model' => $activity, 'attributes' => [
                [
                    'label' => 'SCWT Program Name', 'value' => $activity->scwt_name_of_program,
                ], [
                    'label' => 'Program Date', 'value' => $activity->date_of_program,
                ], [
                    'label' => 'Venue', 'value' => $activity->program_venue,
                ], [
                    'label' => 'Number of Participants', 'value' => $activity->participants_number,
                ], [
                    'label' => 'Participants Names', 'value' => $activity->name_participants_involved,
                ],
            ], 'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    } elseif ($activity->activity_type === 'Research') {
        echo ActivityWidget::widget([
            'title' => $title,
            'headerText' => $activity->activity_type,
            
        ]);
        echo DetailView::widget([
            'model' => $activity, 'attributes' => [
                [
                    'label' => 'Title', 'value' => $activity->research_title,
                ],
            ], 'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    } elseif ($activity->activity_type === 'Publication') {
        echo ActivityWidget::widget([
            'title' => $title,
            'headerText' => $activity->activity_type,
            
        ]);
        echo DetailView::widget([
            'model' => $activity, 'attributes' => [
                [
                    'label' => 'Title Publication', 'value' => $activity->publication_title,
                ], [
                    'label' => 'Publisher', 'value' => $activity->publisher,
                ],
            ], 'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
        echo '</div>';
    } elseif ($activity->activity_type === 'Consultancy') {
        echo ActivityWidget::widget([
            'title' => $title,
            'headerText' => $activity->activity_type,
            
        ]);
        echo DetailView::widget([
            'model' => $activity, 'attributes' => [
                [
                    'label' => 'Consultancy Name', 'value' => $activity->consultancy_name,
                ], [
                    'label' => 'Project Duration', 'value' => $activity->project_duration,
                ],
            ], 'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    } elseif ($activity->activity_type === 'Any other of Cooperation, Please specify') {
        echo ActivityWidget::widget([
            'title' => $title,
            'headerText' => $activity->activity_type,
            
        ]);
        echo DetailView::widget([
            'model' => $activity, 'attributes' => [
                [
                    'label' => 'other', 'value' => $activity->other,
                ], [
                    'label' => 'date', 'value' => $activity->date,
                ],
            ], 'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    } elseif ($activity->activity_type === 'No Activity, Please specify') {
        echo ActivityWidget::widget([
            'title' => $title,
            'headerText' => $activity->activity_type,
            
        ]);
        echo DetailView::widget([
            'model' => $activity, 'attributes' => [
                [
                    'label' => 'justification', 'value' => $activity->justification,
                ],
            ], 'template' => "<tr'><th class='col-3'>{label}</th><td class='col-9 text-break'>{value}</td></tr>"
        ]);
    }


}
Pjax::end();
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
<script>

    $(document).ready(function() {
        $('.delete-button').on('click', function() {
            var activityId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You will not be able to recover this activity!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/agreement/delete-activity',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id: activityId,
                            _csrf: '<?= Yii::$app->request->csrfToken ?>',
                        },
                        success: function(data) {
                            console.log('Success:', data);
                            if (data.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'The activity has been deleted.',
                                    'success'
                                ).then(() => {
                                    $.pjax.reload({container: '#pjax-container'})
                                        .done(function() {
                                            console.log('PJAX reload successful');
                                        })
                                        .fail(function() {
                                            console.log('PJAX reload failed');
                                        });
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while deleting the activity.',
                                    'error'
                                );
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('AJAX error:', textStatus, errorThrown); // Debugging line
                            Swal.fire(
                                'Error!',
                                'Failed to delete the activity. Please try again later.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>