<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "kcdio".
 *
 * @property int $id
 * @property string|null $kcdio
 */
class Kcdio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kcdio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kcdio'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kcdio' => 'Kcdio',
        ];
    }
}
