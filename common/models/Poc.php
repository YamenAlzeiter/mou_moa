<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "poc".
 *
 * @property int $id
 * @property string $name
 * @property string $phone_number
 * @property string $email
 * @property string $address
 * @property int $staff_id
 * @property string $kcdio
 */
class Poc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'poc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone_number', 'email', 'address', 'kcdio'], 'required'],
            [['name', 'phone_number', 'email', 'address', 'kcdio'], 'string', 'max' => 255],
            [['staff_id'], 'integer'],
            ['email', 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'phone_number' => 'Phone Number',
            'email' => 'Email',
            'address' => 'Address',
            'kcdio' => 'K/C/D/I/O'
        ];
    }
}
