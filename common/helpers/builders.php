<?php
namespace common\helpers;

use common\helpers\statusLable;

use yii\bootstrap5\Html;
use yii\helpers\Url;

class builders
{
    public function tableProbChanger($status, $viewer){
        switch ($viewer){
            case 'Applicant'         : return in_array($status, [2, 12, 33, 34, 43, 47, 81, 110]);
            case 'ApplicantMCOM'     : return in_array($status, [11]);
            case 'ApplicantActivity' : return in_array($status, [100, 91]);
            case 'OSC'               : return in_array($status, [10, 51, 15, 72]);
            case 'OLA'               : return in_array($status, [1, 21, 31, 41, 46, 61, 121]);
        }
    }
    public function pillBuilder($status, $options = "")
    {
        $statusHelper = new statusLable();
        $padgeClass = $statusHelper->statusBadgeClass($status);
        $textClass = $statusHelper->statusDotClass($status);
        $tag = $statusHelper->statusTag($status);
        $description = $statusHelper->statusDescription($status);
        $title = "$description";
        return <<<HTML
                    <div class='$padgeClass status-w $options'>
                        <p class="m-0 fs-4">$tag</p>
                        <i class='cursor-pointer ti ti-info-circle fs-5'
                           data-bs-toggle='tooltip'
                           data-bs-placement='bottom'
                           data-bs-html='true'
                           title=" $title  "></i>
                    </div>
                HTML;
    }

    public function buttonWithoutStatus($model, $type, $title ='')
    {
        $icon = [
            'view'     => 'text-dark ti-eye              ',
            'view-email-template'     => 'text-dark ti-eye              ',
            'delete'   => 'text-danger ti-trash             ',
            'update'   => 'text-primary ti-edit-circle         ',
            'status-update'   => 'text-primary ti-edit-circle         ',
            'update-kcdio'   => 'text-primary ti-edit-circle         ',
            'update-email-template'     => 'text-primary ti-edit-circle           ',
            'log'      => 'text-warning ti-file-description ',
            'MCOM Date' => 'text-secondary ti-calendar-event',
            'Add Activity' => 'text-indigo ti-radar-2',
            'create'     => 'text-primary ti-eye              ',
        ];


        $header = $title == '' ? "<p class='title_tool_tip'>$type</p>" : "<p class='title_tool_tip'>$title</p>";
        return Html::button(
            '<i class="ti fs-7 ' . $icon[$type] . '" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="' . $title . '"></i>',
            [
                'value' => Url::to([$type, 'id' => $model->id]),
                'class' => 'btn-action',
                'id' => 'modelButton',
                'onclick' => "$('#modal').modal('show').find('#modalContent').load($(this).attr('value'), function() {
    // Append the HTML snippet to the modal content
    $('#modalContent').append('');
    
    // Set the modal title
    $('#modal').find('.modal-title').html('<h1 class=\"mb-0\">$title</h1>');
});"
            ]
        );
    }

    public function actionBuilder($model, $type , $modal_id ="#modal")
    {
        $icon = [
            'view'           => 'text-dark ti-eye              ',
            'delete'         => 'text-danger ti-trash             ',
            'update'         => 'text-primary ti-edit-circle         ',
            'update-poc'      => 'text-primary ti-user-circle          ',
            'log'            => 'text-warning ti-file-description ',
            'MCOM Date'      => 'text-secondary ti-calendar-event',
            'Add Activity'   => 'text-indigo ti-radar-2',
            'create'         => 'text-primary ti-eye              ',
            'mcom'           => 'text-secondary ti-calendar-event ',
        ];
        $title = [
            21 => 'Result of MCOM Meeting',
            31 => 'Result of UMC Meeting',
            11 => 'Set MCOM Date',
        ];
        if ($type == 'update'){
            $label = (isset($title[$model->status])) ? $title[$model->status] : "$type: $model->id";
        }else  $label ="$type: $model->id";

        if ($type == 'MCOM Date') {
            $url = 'update';
        } elseif ($type == 'Add Activity') {
            $url = 'add-activity';
        }elseif($type == 'statusUpdate') {
            $url = 'update';
        }else
         $url = $type;

        $title = "<p class='title_tool_tip'>$type</p>";
        return Html::button(
            '<i class="ti fs-7 ' . $icon[$type] . '" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="' . $title . '"></i>',
            [
                'value' => Url::to([$url, 'id' => $model->id]),
                'class' => 'btn-action',
                'id' => 'modelButton',
                'onclick' => "$('$modal_id').modal('show').find('#modalContent').load($(this).attr('value'), function() {
            // Append the HTML snippet to the modal content
            $('#modalContent').append('');
            
            // Set the modal title
            $('#modal').find('.modal-title').html('<h1 class=\"mb-0\">$label</h1>');
        });"
            ]
        );


    }
    public function downloadLinkBuilder($attribute, $name)
    {
        $link = Html::tag('p', Html::a($name, ['downloader', 'filePath' => $attribute], ['class' => 'dropdown-item fw-bolder']));
        return $attribute !== null ? $link : null;

    }
    function createButton($url, $iconClass, $title, $modalTitle) {
        return Html::button(
            "<i class=\"ti fs-5 $iconClass\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" data-bs-html=\"true\" title=\"$title\"></i> $title",
            [
                'value' => Url::to($url),
                'class' => 'btn btn-lg btn-success w-100',
                'id' => 'modelButton',
                'onclick' => "$('#modal').modal('show').find('#modalContent').load($(this).attr('value'), function() {
                                            // Set the modal title
                                            $('#modal').find('.modal-title').html('<h1 class=\"mb-0\">$modalTitle</h1>'); 
                                        });"
            ]
        );
    }


}
?>