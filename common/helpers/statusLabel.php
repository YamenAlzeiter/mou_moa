<?php

namespace common\helpers;

use common\models\Status;

class statusLabel
{
    public function statusTag($status)
    {
        if ($status != null || $status != "") {
            $tag = Status::find()->where(['status' => $status])->one();
            return $tag->tag;
        } else {
            return "ERROR";
        }

    }

    public function statusDescription($status)
    {
        if ($status != null || $status != "") {
            $description = Status::find()->where(['status' => $status])->one();
            return $description->description;
        } else {
            return "No Status Found";
        }
    }

    public function getStatusClasses($status, $attribute)
    {
        $classes = [
            1  =>  ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'Applicant', 'to' => 'OSC'],
            11 =>  ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'OLA'      , 'to' => 'Applicant'],
            13  => ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'OLA'      ,'to' => 'Applicant'],
            14  => ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'OLA'      ,'to' => 'Applicant'],
            21 =>  ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'OLA'      , 'to' => 'applicant'],
            31 =>  ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'Applicant', 'to' => 'OLA/MCOM'],
            41 =>  ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'OLA'      , 'to' => 'OLA/UMC'],
            51 =>  ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'OLA'      , 'to' => 'Applicant'],
            61 =>  ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'Applicant', 'to' => 'OLA'],
            71 =>  ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'OLA'      , 'to' => 'Applicant'],
            110 => ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'System'      ,'to' => 'Applicant'],
            10 =>  ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'Applicant', 'to' => 'OSC'],
            -10 => ['badge' => 'pill-warning', 'dot' => 'text-bg-warning', 'from' => 'Applicant', 'to' => 'Applicant'],

            15 =>  ['badge' => 'bg-primary-subtle text-primary', 'dot' => 'text-bg-primary', 'from' => 'OSC'      , 'to' => 'Applicant'],
            121 => ['badge' => 'bg-primary-subtle text-primary', 'dot' => 'text-bg-primary', 'from' => 'OLA'      , 'to' => 'Applicant'],


            42 =>  ['badge' => 'bg-danger-subtle   text-danger', 'dot' => 'text-bg-danger' , 'from' => 'OLA'      , 'to' => 'Applicant'],
            33 =>  ['badge' => 'bg-info-subtle       text-info', 'dot' => 'text-bg-primary', 'from' => 'OLA'      , 'to' => 'Applicant'],
            43 =>  ['badge' => 'bg-info-subtle       text-info', 'dot' => 'text-bg-primary', 'from' => 'OLA'      , 'to' => 'Applicant'],

            81 =>  ['badge' => 'pill-upcoming', 'dot' => 'text-bg-success', 'from' => 'OLA'      , 'to' => 'Applicant'],
            91 =>  ['badge' => 'pill-upcoming', 'dot' => 'text-bg-success', 'from' => 'OSC'      , 'to' => 'Applicant'],
            100 => ['badge' => 'pill-upcoming', 'dot' => 'text-bg-success', 'from' => 'OSC'      , 'to' => 'Applicant'],

            92 =>  ['badge' => 'pill-alert' , 'dot' => 'text-bg-danger' , 'from' => 'OSC'      , 'to' => 'Applicant'],
            102 => ['badge' => 'pill-alert' , 'dot' => 'text-bg-danger' , 'from' => 'OSC'      , 'to' => 'Applicant'],
            2  =>  ['badge' => 'pill-alert', 'dot' => 'text-bg-danger' , 'from' => 'OSC'      , 'to' => 'Applicant'],
            12 =>  ['badge' => 'pill-alert', 'dot' => 'text-bg-danger' , 'from' => 'OLA'      , 'to' => 'Applicant'],
            32 =>  ['badge' => 'pill-alert', 'dot' => 'text-bg-danger' , 'from' => 'OLA'      , 'to' => 'Applicant'],

            111 => ['badge' => 'pill-sendBack', 'dot' => 'text-bg-warning', 'from' => 'System'      ,'to' => 'Applicant'],
            112 => ['badge' => 'pill-sendBack', 'dot' => 'text-bg-warning', 'from' => 'System'      ,'to' => 'Applicant'],

        ];

        $default = ['badge' => 'pill-alert', 'dot' => 'text-bg-danger', 'from' => 'error', 'to' => 'error'];

        if (isset($classes[$status])) {
            return $classes[$status][$attribute];
        }

        return $default[$attribute];
    }

    public function statusBadgeClass($status)
    {
        return 'badge fw-bolder fs-7 gap-2 d-inline-flex align-items-center justify-content-between mw-pill rounded-2 ' . $this->getStatusClasses($status, 'badge');
    }

    public function statusDotClass($status)
    {
        return 'round-8 rounded-circle d-inline-block me-1 ' . $this->getStatusClasses($status, 'dot');
    }
    public function getStatusFrom($status)
    {
        return $this->getStatusClasses($status, 'from');
    }

    public function getStatusTo($status)
    {
        return $this->getStatusClasses($status, 'to');
    }

}
