<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\FileHelper;

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
 *
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

    public $files_applicant;
    public $files_dp;
    public $poc_kcdio_getter;
    public $poc_kcdio_getter_x;
    public $poc_kcdio_getter_xx;
    public $poc_name_getter;
    public $poc_name_getter_x;
    public $poc_name_getter_xx;

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
            [['files_applicant'], 'file', 'maxFiles' => 5, 'extensions' => 'docx', 'maxSize' => 1024 * 1024 * 2],
            [['files_dp'], 'file', 'extensions' => 'docx', 'maxSize' => 1024 * 1024 * 10],

            [[ 'col_organization',
                'col_name', 'col_address', 'col_collaborators_name',
                'col_wire_up','project_title',
                'proposal', 'col_phone_number', 'col_email',
                'grant_fund', 'member', 'transfer_to',
                'agreement_type', 'country', 'files_applicant', 'champion'],
                'required', 'on' => 'uploadCreate'],

            [[ 'col_organization',
                'col_name', 'col_address', 'col_collaborators_name',
                'col_wire_up', 'project_title',
                'proposal', 'col_phone_number', 'col_email',
                'grant_fund', 'member', 'transfer_to',
                'agreement_type', 'country', 'sign_date', 'end_date', 'mcom_date', 'files_applicant', 'champion'],
                'required', 'on' => 'createSpecial'],

            [['col_email'], 'email'],
            [['project_title', 'proposal', 'reason', 'temp'], 'string'],
            [['sign_date', 'end_date', 'mcom_date', 'created_at', 'updated_at', 'last_reminder'], 'safe'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['col_organization', 'col_name', 'col_address', 'col_contact_details',
                'col_collaborators_name', 'col_wire_up', 'champion',
                'ssm','dp_doc','applicant_doc', 'transfer_to', 'agreement_type', 'country',
                ], 'string', 'max' => 522],
            [['col_phone_number', 'col_email'], 'string', 'max' => 512],
            [['grant_fund', 'company_profile', 'meeting_link'], 'string', 'max' => 255],
            [['member'], 'string', 'max' => 2],
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
            'col_wire_up' => 'Wire Up',
            'col_phone_number' => 'Phone Number',
            'col_email' => 'Email',
            'champion' => 'Champion',


            'project_title' => 'Project Title',
            'grant_fund' => 'Grant Fund',
            'sign_date' => 'Sign Date',
            'end_date' => 'End Date',
            'member' => 'Member',
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
            'agreement_type' => 'type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'country' => 'Country',
            'isReminded' => 'Reminder Step',
            'temp_attribute_poc' => 'KCDIO',
            'temp_attribute' => 'person in charge name'

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
        if($this->status == 21 || $this->status == 121) $this->increaseMCOMDate();
    }

    protected function increaseMCOMDate(){
        $mcom = McomDate::findOne(['date' => $this->mcom_date]);
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
