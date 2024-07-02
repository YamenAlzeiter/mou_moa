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
 * @property string|null $pi_role
 * @property boolean|null $pi_is_primary
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
            [['pi_name', 'pi_email', 'pi_phone', 'pi_kcdio', 'pi_address', 'pi_role'], 'string', 'max' => 255],
            [['pi_name', 'pi_email', 'pi_phone', 'pi_kcdio', 'pi_address', 'pi_role'], 'required'],
            ['pi_email', 'email'],
            [['agreement_id'], 'default', 'value' => null],
            [['agreement_id'], 'integer'],
            [['pi_is_primary'], 'boolean'],
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
            'pi_name' => 'Person in Charge Name',
            'pi_email' => 'Person in Charge Email',
            'pi_phone' => 'Person in Charge Phone',
            'pi_kcdio' => 'Person in Charge Kcdio',
            'pi_address' => 'Person in Charge Address',
            'pi_role' => 'Role',
            'pi_is_primary' => 'prime? '
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
