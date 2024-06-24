<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mcom_date".
 *
 * @property int $id
 * @property string|null $date_from
 * @property string|null $date_until
 * @property int|null $counter
 */
class McomDate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mcom_date';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_from', 'date_until'], 'safe'],
            [['counter'], 'default', 'value' => null],
            [['counter'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_from' => 'Date From',
            'date_until' => 'Date Until',
            'counter' => 'Counter',
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Process date_until to ensure it contains the correct date part
            if ($this->date_from && $this->date_until) {
                $datePart = (new \DateTime($this->date_from))->format('Y-m-d');
                $timePart = (new \DateTime($this->date_until))->format('H:i:s');
                $this->date_until = $datePart . ' ' . $timePart;
            }
            return true;
        }
        return false;
    }
}
