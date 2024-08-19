<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reminder".
 *
 * @property int $id
 * @property string|null $type
 * @property int|null $reminder_before
 */
class Reminder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reminder';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reminder_before', 'reminder_after', 'type'], 'required'],
            [['reminder_before'], 'default', 'value' => null],
            [['reminder_before'], 'integer'],
            [['type'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'reminder_before' => 'Reminder Before',
        ];
    }
}
