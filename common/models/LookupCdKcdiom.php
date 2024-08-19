<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lookup.cd_kcdiom".
 *
 * @property string|null $kcdiom_code
 * @property string|null $abb_code
 * @property string|null $kcdiom_desc
 * @property string|null $kcdiom_desc_alt
 * @property string|null $org_main
 * @property int|null $branch_code
 * @property string|null $type_code
 * @property string|null $lr_code
 * @property string|null $category
 * @property string|null $status
 * @property string|null $remarks
 * @property string|null $date_created
 * @property string|null $created_by
 * @property string|null $date_update
 * @property string|null $update_by
 * @property string|null $effective_date
 * @property string|null $Staff
 * @property string|null $Students
 * @property string|null $CFS
 * @property string|null $Mymohes
 * @property string|null $Research
 * @property string|null $Publication
 * @property string|null $Finance
 */
class LookupCdKcdiom extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lookup.cd_kcdiom';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kcdiom_code', 'abb_code', 'kcdiom_desc', 'kcdiom_desc_alt', 'type_code', 'lr_code', 'category', 'status', 'remarks', 'created_by', 'update_by', 'Staff', 'Students', 'CFS', 'Mymohes', 'Research', 'Publication', 'Finance'], 'string'],
            [['branch_code'], 'default', 'value' => null],
            [['branch_code'], 'integer'],
            [['date_created', 'date_update', 'effective_date'], 'safe'],
            [['org_main'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kcdiom_code' => 'Kcdiom Code',
            'abb_code' => 'Abb Code',
            'kcdiom_desc' => 'Kcdiom Desc',
            'kcdiom_desc_alt' => 'Kcdiom Desc Alt',
            'org_main' => 'Org Main',
            'branch_code' => 'Branch Code',
            'type_code' => 'Type Code',
            'lr_code' => 'Lr Code',
            'category' => 'Category',
            'status' => 'Status',
            'remarks' => 'Remarks',
            'date_created' => 'Date Created',
            'created_by' => 'Created By',
            'date_update' => 'Date Update',
            'update_by' => 'Update By',
            'effective_date' => 'Effective Date',
            'Staff' => 'Staff',
            'Students' => 'Students',
            'CFS' => 'Cfs',
            'Mymohes' => 'Mymohes',
            'Research' => 'Research',
            'Publication' => 'Publication',
            'Finance' => 'Finance',
        ];
    }
}
