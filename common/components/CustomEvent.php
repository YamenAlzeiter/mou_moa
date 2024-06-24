<?php
// CustomEvent.php
namespace common\components;

use yii2fullcalendar\models\Event as BaseEvent;

class CustomEvent extends BaseEvent
{
    public $repeat;
    public $description;
    public $backColor;
}
