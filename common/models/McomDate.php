<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mcom_date".
 *
 * @property int $id
 * @property string|null $date
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
            [['date'], 'safe'],
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
            'date' => 'Date',
            'counter' => 'Counter',
        ];
    }
}
