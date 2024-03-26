<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "status".
 *
 * @property int $id
 * @property int|null $status
 * @property string|null $tag
 * @property string|null $description
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['tag'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 522],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'tag' => 'Tag',
            'description' => 'Description',
        ];
    }
}
