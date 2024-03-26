<?php
namespace common\helpers;

use yii\grid\DataColumn;

class  customColumClass extends DataColumn
{
    public function init()
    {
        parent::init();
        if (!empty($this->attribute)) {
            $this->headerOptions = array_merge($this->headerOptions, [
                'class' => 'd-flex align-itmes-center justify-content-between flex-row',
            ]);
        }
    }
}