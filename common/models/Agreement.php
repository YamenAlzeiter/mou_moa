<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "agreement".
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
 * @property string|null $champion
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property string|null $country
 *
 * @property string|null $project_title
 * @property string|null $grant_fund
 * @property string|null $sign_date
 * @property string|null $last_reminder
 * @property string|null $end_date
 * @property string|null $member
 * @property string|null $proposal
 * @property int|null $status
 * @property string|null $ssm
 * @property string|null $company_profile
 * @property string|null $mcom_date
 * @property string|null $meeting_link
 * @property string|null $agreement_type
 * @property string|null $execution_date
 * @property string|null $project_start_date
 * @property string|null $project_end_date
 * @property string|null $dp_doc
 * @property string|null $applicant_doc
 * @property string|null $reason
 * @property string|null $transfer_to
 * @property string|null $temp
 * @property integer|null $isReminded
 * @property Activities[] $activities
 * @property Log[] $logs
 */
class Agreement extends ActiveRecord
{
    public $hasMatchingMcomDate;
    public $files_applicant;
    public $files_dp;
    public $agreement_type_other;

    public $pi_delete_ids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agreement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['files_applicant'],
                'file',
                'maxFiles' => 5,
                'maxSize' => 1024 * 1024 * 2, // 2 MB limit
                'extensions' => ['docx'],
                'when' => function ($model) {
                    return $model->status != 91; // Allow 'docx' when status is not 81
                },
                'whenClient' => "function (attribute, value) {
                    return $('#agreement-status').val() != 91; // Client-side validation
                }",
            ],
            [
                ['files_applicant'],
                'file',
                'maxFiles' => 5,
                'maxSize' => 1024 * 1024 * 2, // 2 MB limit
                'extensions' => ['pdf'],
                'when' => function ($model) {
                    return $model->status == 91; // Allow 'pdf' when status is 81
                },
                'whenClient' => "function (attribute, value) {
                    return $('#agreement-status').val() == 91; // Client-side validation
                }",
            ],
            [
                [ 'execution_date', 'project_end_date', 'project_start_date'],
                'required',
                'when' => function ($model) {
                    return $model->status == 91; // Required when status is 81
                },
                'whenClient' => "function (attribute, value) {
                    return $('#agreement-status').val() == 91; // Client-side validation
                }",
            ],
            // Rule for files_dp: single or multiple docx files with a max size of 10MB
            [
                ['files_dp'],
                'file',
                'extensions' => 'docx',
                'maxSize' => 1024 * 1024 * 10
            ],


            // Required fields for 'uploadCreate' scenario
            [['col_organization', 'col_name', 'col_address', 'col_collaborators_name',
                'col_wire_up',  'proposal', 'col_phone_number',
                'col_email', 'transfer_to', 'agreement_type', 'country',
                'files_applicant', 'champion'], 'required', 'on' => 'uploadCreate'],

            // Required fields for 'createSpecial' scenario
            [['col_organization', 'col_name', 'col_address', 'col_collaborators_name',
                'col_wire_up', 'proposal', 'col_phone_number',
                'col_email', 'transfer_to',
                'agreement_type', 'country', 'sign_date', 'end_date', 'mcom_date',
                'files_applicant', 'champion'], 'required', 'on' => 'createSpecial'],

            // Conditional required rules
            [['agreement_type_other'], 'required', 'when' => function ($model) {
                return $model->agreement_type == 'other';
            }, 'whenClient' => "function (attribute, value) {
            return $('#agreement-type-dropdown').val() == 'other';
        }"],
            [['ssm', 'company_profile'], 'required', 'when' => function ($model) {
                return $model->transfer_to === 'OIL';
            }, 'whenClient' => "function (attribute, value) {
            return $('#transfer-to-dropdown').val() === 'OIL';
        }"],
            [['member', 'grant_fund', 'project_title'], 'required', 'when' => function ($model) {
                return $model->transfer_to !== 'RMC';
            }, 'whenClient' => "function (attribute, value) {
            return $('#transfer-to-dropdown').val() !== 'RMC';
        }"],
            [['project_start_date', 'project_end_date'], 'required', 'when' => function ($model) {
                return $model->transfer_to == 'RMC';
            }, 'whenClient' => "function (attribute, value) {
            return $('#transfer-to-dropdown').val() == 'RMC';
        }"],

            // Email validation rule
            [['col_email'], 'email'],

            // String type rules
            [['project_title', 'proposal', 'reason', 'temp'], 'string'],
            [['col_organization', 'col_name', 'col_address', 'col_contact_details',
                'col_collaborators_name', 'col_wire_up', 'champion',
                'ssm', 'dp_doc', 'applicant_doc', 'transfer_to', 'agreement_type', 'country'], 'string', 'max' => 522],
            [['col_phone_number', 'col_email'], 'string', 'max' => 512],
            [['grant_fund', 'company_profile', 'meeting_link'], 'string', 'max' => 255],
            [['member'], 'string', 'max' => 2],

            // Safe fields
            [['sign_date', 'end_date', 'mcom_date', 'created_at', 'updated_at', 'last_reminder'], 'safe'],

            // Default and integer rules
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],

            // Default value rules
            [['reason'], 'default', 'value' => null],
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
            'champion' => 'Champion',

            'project_end_date' => 'End Date',
            'project_start_date' => 'Start Date',
            'execution_date' => 'Execution Date',
            'project_title' => 'Project Title / Research Title',
            'grant_fund' => 'Grant Fund',
            'sign_date' => 'Sign Date',
            'end_date' => 'End Date',
            'member' => 'No. of Project Members',
            'proposal' => 'proposal',
            'status' => 'Status',
            'ssm' => 'SSM',
            'company_profile' => 'Company Profile',
            'mcom_date' => 'MCOM Date',
            'meeting_link' => 'Meeting Link',

            'dp_doc' => 'by department',
            'applicant_doc' => 'by applicant',

            'reason' => 'Reason',
            'transfer_to' => 'OSC',
            'agreement_type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'country' => 'Country',
            'isReminded' => 'Reminder Step',
            'temp_attribute_poc' => 'KCDIO',
            'temp_attribute' => 'person in charge name',
            'agreement_type_other' => 'Other'
        ];
    }

    /**
     * Gets query for [[Activities]].
     *
     * @return ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasMany(Activities::class, ['agreement_id' => 'id']);
    }

    public function getAgreementPoc()
    {
        return $this->hasMany(AgreementPoc::class, ['agreement_id' => 'id']);
    }

    public function getMcomDate()
    {
        return $this->hasOne(McomDate::class, ['date_from' => 'mcom_date']);
    }

    public function getPrimaryAgreementPoc()
    {
        return $this->hasOne(AgreementPoc::className(), ['agreement_id' => 'id'])->andWhere(['pi_is_primary' => true]);
    }
    /**
     * Gets query for [[Logs]].
     *
     * @return ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::class, ['agreement_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if ($this->isAttributeChanged('status') || $this->isNewRecord) {
            $this->updated_at = new Expression('NOW()');
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        //delete draft files when status become 91 AKA ACTV
//        if($this->status == 91) $this->deleteDrafts();
        if ($this->status == 21 || $this->status == 121) $this->increaseMCOMDate();
    }

    protected function increaseMCOMDate()
    {
        $mcom = McomDate::findOne(['date_from' => $this->mcom_date]);
        $mcom->counter++;
        $mcom->save();
    }
//    protected function deleteDrafts()
//    {
//        $fileLocations = [
//            $this->doc_applicant,
//            $this->doc_draft,
//            $this->doc_newer_draft
//        ];
//
//        foreach ($fileLocations as $filePath) {
//            if (is_file($filePath)) {  // Use is_file() to check existence
//                FileHelper::unlink($filePath);
//                Yii::info("File deleted: $filePath", __METHOD__);
//            } else {
//                Yii::warning("File not found: $filePath", __METHOD__);
//            }
//        }
//    }
}
