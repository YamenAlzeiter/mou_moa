<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int|null $agreement_id
 * @property int|null $old_status
 * @property int|null $new_status
 * @property string|null $message
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Agreement $agreement
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agreement_id', 'old_status', 'new_status'], 'default', 'value' => null],
            [['agreement_id', 'old_status', 'new_status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['message'], 'string', 'max' => 255],
            [['agreement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agreement::class, 'targetAttribute' => ['agreement_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'agreement_id' => 'Agreement ID',
            'old_status' => 'Old Status',
            'new_status' => 'New Status',
            'message' => 'Message',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Agreement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgreement()
    {
        return $this->hasOne(Agreement::class, ['id' => 'agreement_id']);
    }
}
