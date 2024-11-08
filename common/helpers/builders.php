<?php

namespace common\helpers;

use Yii;
use yii\bootstrap5\Html;
use yii\helpers\Url;

class builders
{
    public function tableProbChanger($status, $viewer)
    {
        switch ($viewer) {
            case 'Applicant'         :
                return in_array($status, [
                    Variables::agreement_re_state,
                    Variables::agreement_init,
                    Variables::agreement_not_complete_osc,
                    Variables::agreement_approved_ola,
                    Variables::agreement_not_complete_ola,
                    Variables::agreement_MCOM_KIV,
                    Variables::agreement_UMC_KIV,
                    Variables::agreement_conditional_upload_not_complete,
                    Variables::agreement_draft_uploaded_ola,
                    Variables::agreement_draft_upload_applicant,
                    Variables::agreement_draft_rejected_ola,
                    Variables::agreement_reminder_sent
                ]);
            case 'ApplicantMCOM'     :
                return in_array($status, [Variables::agreement_approved_ola]);
            case 'ApplicantActivity' :
                return in_array($status, [
                    Variables::imported_agreement_executed,
                    Variables::agreement_executed
                ]);
            case 'OSC'               :
                return in_array($status, [
                    Variables::agreement_init,
                    Variables::agreement_resubmitted,
                    Variables::agreement_draft_approve_final_draft
                ]);
            case 'OLA'               :
                return in_array($status, [
                    Variables::agreement_approved_osc,
                    Variables::agreement_MCOM_date_set,
                    Variables::agreement_MCOM_approved,
                    Variables::agreement_UMC_approve,
                    Variables::agreement_conditional_upload,
                    Variables::agreement_draft_upload_applicant,
                    Variables::agreement_MCOM_date_changed,
                    Variables::agreement_approved_circulation,
                    Variables::agreement_approved_via_power
                ]);
        }
    }

    public function pillBuilder($status, $options = "")
    {
        $statusHelper = new statusLabel();
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

    public function buttonWithoutStatus($model, $type, $title = '')
    {
        $icon = [
            'view' => 'text-dark ti-eye              ',
            'view-email-template' => 'text-dark ti-eye              ',
            'delete' => 'text-danger ti-trash             ',
            'update' => 'text-primary ti-edit-circle         ',
            'status-update' => 'text-primary ti-edit-circle         ',
            'update-kcdio' => 'text-primary ti-edit-circle         ',
            'update-collaboration' => 'text-primary ti-edit-circle         ',

            'mcom-update' => 'text-primary ti-edit-circle         ',
            'type-update' => 'text-primary ti-edit-circle         ',
            'poc-update' => 'text-primary ti-edit-circle         ',
            'update-email-template' => 'text-primary ti-edit-circle           ',
            'log' => 'text-warning ti-file-description ',
            'MCOM Date' => 'text-secondary ti-calendar-event',
            'Add Activity' => 'text-indigo ti-radar-2',
            'create' => 'text-primary ti-eye              ',

        ];


        $header = $title == '' ? "<p class='title_tool_tip'>$type</p>" : "<p class='title_tool_tip'>$title</p>";
        return Html::button(
            '<i class="ti fs-7 ' . $icon[$type] . '" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="' . $title . '"></i>',
            [
                'value' => Url::to([$type, 'id' => $model->id]),
                'class' => 'btn-action',
                'id' => 'modelButton',
                'onclick' => "$('#modal').modal('show').find('#modalContent').load($(this).attr('value'), function() {
                            $('#modalContent').append('');
                            $('#modal').find('.modal-title').html('<h1 class=\"mb-0\">$title</h1>');
                        });"
            ]
        );
    }

    public function actionBuilder($model, $type, $modal_id = "#modal")
    {
        $icon = [
            'view' => 'text-dark ti-eye              ',
            'delete' => 'text-danger ti-trash             ',
            'update' => 'text-primary ti-edit-circle         ',
            'update-poc' => 'text-primary ti-user-circle          ',
            'update-record' => 'text-primary ti-pencil',
            'log' => 'text-warning ti-file-description ',
            'MCOM Date' => 'text-secondary ti-calendar-event',
            'Add Activity' => 'text-indigo ti-radar-2',
            'create' => 'text-primary ti-eye              ',
            'mcom' => 'text-secondary ti-calendar-event ',
        ];
        $title = [
            21 => 'Result of MCOM Meeting',
            13 => 'Result of MCOM Meeting',
            14 => 'Result of UMC Meeting',
            31 => 'Result of UMC Meeting',
            11 => 'Set MCOM Date',
        ];
        if ($type == 'update') {
            $label = (isset($title[$model->status])) ? $title[$model->status] : "$type: $model->id";
        } elseif ($type == 'log' && in_array(Yii::$app->user->identity->type, ['IO', 'RMC', 'admin', 'OLA', 'OIL'])) {
            $downloadLink = Html::a('<i class="ti ti-download text-black"></i>', Yii::$app->urlManager->createUrl(['agreement/generate-pdf', 'id' => $model->id]), ['class' => 'btn btn-iium', 'target'=>'_blank',]);
            $label = "$type: $model->id " . $downloadLink;
        } else  $label = "$type: $model->id";

        if ($type == 'MCOM Date') {
            $url = 'update';
        } elseif ($type == 'Add Activity') {
            $url = 'add-activity';
        } elseif ($type == 'statusUpdate') {
            $url = 'update';
        } else
            $url = $type;

        $title = "<p class='title_tool_tip'>$type</p>";
        return Html::button(
            '<i class="ti fs-7 ' . $icon[$type] . '" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="' . $title . '"></i>',
            [
                'value' => Url::to([$url, 'id' => $model->id]),
                'class' => 'btn-action',
                'id' => 'modelButton',
                'onclick' => "$('$modal_id').modal('show').find('#modalContent').load($(this).attr('value'), function() {
        $('#modalContent').append('');
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

    function createButton($url, $iconClass, $title, $modalTitle)
    {
        return Html::button(
            "<i class=\"ti fs-5 $iconClass\" data-bs-toggle=\"tooltip\" data-bs-placement=\"bottom\" data-bs-html=\"true\" title=\"$title\"></i> $title",
            [
                'value' => Url::to($url),
                'class' => 'btn btn-lg btn-success w-100',
                'id' => 'modelButton',
                'onclick' => "$('#modal').modal('show').find('#modalContent').load($(this).attr('value'), function() {
                                            $('#modal').find('.modal-title').html('<h1 class=\"mb-0\">$modalTitle </h1>'); 
                                        });"
            ]
        );
    }


}

?>