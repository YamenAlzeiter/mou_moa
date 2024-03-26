<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "email_template".
 *
 * @property int $id
 * @property string|null $subject
 * @property string|null $body
 */
class EmailTemplate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'email_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['body'], 'string'],
            [['subject'], 'string', 'max' => 522],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject' => 'Subject',
            'body' => 'Body',
        ];
    }
}
