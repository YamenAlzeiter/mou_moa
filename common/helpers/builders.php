<?php
namespace common\helpers;

use common\helpers\statusLable;

use yii\bootstrap5\Html;
use yii\helpers\Url;

class builders
{
    public function pillBuilder($status, $options = "")
    {
        $statusHelper = new statusLable();
        $padgeClass = $statusHelper->statusBadgeClass($status);
        $textClass = $statusHelper->statusDotClass($status);
        $tag = $statusHelper->statusTag($status);
        $description = $statusHelper->statusDescription($status);

        return <<<HTML
                    <div class='$padgeClass status-w $options'>
                        <p class="m-0 fs-4">$tag</p>
                        <i class='cursor-pointer ti ti-info-circle fs-5'
                           data-bs-toggle='tooltip'
                           data-bs-placement='bottom'
                           data-bs-html='true'
                           title='$description'></i>
                    </div>
                HTML;
    }

    public function actionBuilder($model, $type , $modal_id ="#modal")
    {
        $icon = [
            'view'     => 'text-primary ti-eye             ',
            'delete'   => 'text-danger ti-trash            ',
            'update'   => 'text-dark ti-edit-circle        ',
            'log'      => 'text-warning ti-file-description',
            'MCOM Date' => 'text-secondary ti-calendar-event',
        ];

         $url = $type == 'MCOM Date' ? 'update' : $type;

        return Html::button(
            '<i class="ti fs-7 ' . $icon[$type] . '" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="' . $type . '"></i>',
            [
                'value' => Url::to([$url, 'id' => $model->id]),
                'class' => 'btn-action',
                'id' => 'modelButton',
                'onclick' => "$('$modal_id').modal('show').find('#modalContent').load($(this).attr('value'), function() {
            // Append the HTML snippet to the modal content
            $('#modalContent').append('');
            
            // Set the modal title
            $('#modal').find('.modal-title').html('<h1 class=\"mb-0\">$type: $model->id</h1>');
        });"
            ]
        );


    }
    public function downloadLinkBuilder($attribute, $name)
    {
        $link = Html::tag('p', Html::a($name, ['downloader', 'filePath' => $attribute], ['class' => 'dropdown-item fw-bolder']));
        return $attribute !== null ? $link : null;

    }


}
?>


