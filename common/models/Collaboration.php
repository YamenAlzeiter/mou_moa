<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "collaboration".
 *
 * @property int $id
 * @property string|null $col_organization
 * @property string|null $col_name
 * @property string|null $col_address
 * @property string|null $col_contact_details
 * @property string|null $col_collaborators_name
 * @property string|null $col_wire_up
 * @property string|null $col_phone_number
 * @property string|null $col_email
 * @property string|null $country
 *
 * @property Agreement[] $agreements
 */
class Collaboration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'collaboration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['col_organization', 'col_name',
            'col_collaborators_name', 'col_address', 'col_wire_up',
                'col_phone_number', 'col_email', 'country'  ],'required'],
            [['col_organization'], 'string', 'max' => 150],
            [['col_name', 'col_contact_details', 'col_collaborators_name'], 'string', 'max' => 100],
            [['col_address'], 'string', 'max' => 522],
            [['col_wire_up'], 'string', 'max' => 255],
            [['col_phone_number'], 'string', 'max' => 20],
            [['col_email', 'country'], 'string', 'max' => 50],
            [['col_organization'], 'unique'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'col_organization' => 'Organization',
            'col_name' => 'Name',
            'col_address' => 'Address',
            'col_contact_details' => 'Contact Details',
            'col_collaborators_name' => 'Collaborators Name',
            'col_wire_up' => 'Project Description',
            'col_phone_number' => 'Phone Number',
            'col_email' => 'Email',
            'country' => 'Country',
        ];
    }

    /**
     * Gets query for [[Agreements]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgreements()
    {
        return $this->hasMany(Agreement::class, ['col_id' => 'id']);
    }
}
