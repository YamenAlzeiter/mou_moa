<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "agreement_poc".
 *
 * @property int $id
 * @property int|null $agreement_id
 * @property string|null $pi_name
 * @property string|null $pi_email
 * @property string|null $pi_phone
 * @property string|null $pi_kcdio
 * @property string|null $pi_address
 *
 * @property Agreement $agreement
 */
class AgreementPoc extends \yii\db\ActiveRecord
{

    public $poc_kcdio_getter;
    public $poc_name_getter;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agreement_poc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pi_name', 'pi_email', 'pi_phone', 'pi_kcdio', 'pi_address'], 'string', 'max' => 255],
//            [['pi_name', 'pi_email', 'pi_phone', 'pi_kcdio'], 'required'],
            [['agreement_id'], 'default', 'value' => null],
            [['agreement_id'], 'integer'],
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
            'pi_name' => 'Pi Name',
            'pi_email' => 'Pi Email',
            'pi_phone' => 'Pi Phone',
            'pi_kcdio' => 'Pi Kcdio',
            'pi_address' => 'Pi Address',
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
