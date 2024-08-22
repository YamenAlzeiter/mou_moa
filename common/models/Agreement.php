<?php

namespace common\models;

use common\helpers\Variables;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "agreement".
 *
 * @property int $id
 * @property int|null $status
 * @property int|null $col_id
 * @property string|null $champion
 * @property string|null $project_title
 * @property string|null $grant_fund
 * @property string|null $member
 * @property string|null $agreement_type
 * @property string|null $transfer_to
 * @property string|null $proposal
 * @property string|null $rmc_start_date
 * @property string|null $rmc_end_date
 * @property string|null $ssm
 * @property string|null $company_profile
 * @property string|null $agreement_sign_date
 * @property string|null $agreement_expiration_date
 * @property string|null $execution_date
 * @property string|null $mcom_date
 * @property string|null $umc_date
 * @property string|null $last_reminder
 * @property string|null $umc_series
 * @property string|null $mcom_series
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property string|null $applicant_doc
 * @property string|null $dp_doc
 * @property string|null $reason
 * @property string|null $collaboration_area
 * @property int|null $isReminded
 * @property string|null $temp
 *
 * @property Activities[] $activities
 * @property AgreementPoc[] $agreementPocs
 * @property Log[] $logs
 */
class Agreement extends ActiveRecord
{
    public $hasMatchingMcomDate;
    public $files_applicant;
    public $files_dp;
    public $agreement_type_other;
    public $pi_delete_ids;

    public $advice;
    public $principle;
    public $circulation;
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
                    return $model->status != Variables::agreement_executed; // Allow 'docx' when status is not 81
                },
                'whenClient' => "function (attribute, value) {
                    return $('#agreement-status').val() != 91; // Client-side validation
                }",
            ],
            [
                ['files_applicant'],
                'file',
                'maxFiles' => 1,
                'maxSize' => 1024 * 1024 * 2, // 2 MB limit
                'extensions' => ['pdf'],
                'when' => function ($model) {
                    return $model->status == Variables::agreement_executed; // Allow 'pdf' when status is 81
                },
                'whenClient' => "function (attribute, value) {
                    return $('#agreement-status').val() == 91; // Client-side validation
                }",
            ],
            [
                [ 'execution_date', 'agreement_expiration_date', 'agreement_sign_date'],
                'required',
                'when' => function ($model) {
                    return $model->status == Variables::agreement_executed; // Required when status is 81
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
            [['transfer_to', 'agreement_type', 'files_applicant', 'champion'], 'required', 'on' => 'uploadCreate'],

            // Required fields for 'createSpecial' scenario
            [['transfer_to', 'agreement_type', 'agreement_sign_date', 'agreement_expiration_date', 'mcom_date', 'files_applicant', 'champion'], 'required', 'on' => 'createSpecial'],

            // Conditional required rules
            [['files_applicant'], 'required', 'when' => function ($model) {
                return $model->status == Variables::agreement_draft_uploaded_ola;
            }, 'whenClient' => "function (attribute, value) {
            return $('#agreement-status').val() == 51 ;
        }"],

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
            [['rmc_start_date', 'rmc_end_date'], 'required', 'when' => function ($model) {
                return $model->transfer_to == 'RMC';
            }, 'whenClient' => "function (attribute, value) {
            return $('#transfer-to-dropdown').val() == 'RMC';
        }"],

            [['status', 'col_id', 'isReminded'], 'default', 'value' => null],
            [['status', 'col_id', 'isReminded'], 'integer'],
            [['rmc_start_date', 'rmc_end_date', 'agreement_sign_date', 'agreement_expiration_date', 'execution_date', 'mcom_date', 'umc_date', 'last_reminder', 'updated_at', 'created_at'], 'safe'],
            [['applicant_doc', 'dp_doc', 'reason', 'collaboration_area', 'temp'], 'string'],
            [['champion'], 'string', 'max' => 522],
            [['project_title', 'proposal', 'company_profile'], 'string', 'max' => 255],
            [['grant_fund', 'transfer_to'], 'string', 'max' => 10],
            [['member'], 'string', 'max' => 2],
            [['agreement_type'], 'string', 'max' => 50],
            [['ssm'], 'string', 'max' => 25],
            [['umc_series', 'mcom_series', 'circulation'], 'string', 'max' => 100],
            [['col_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collaboration::class, 'targetAttribute' => ['col_id' => 'id']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',

            'agreement_expiration_date' => 'Agreement Expiration Date',
            'agreement_sign_date' => 'Agreement Sign Date',
            'execution_date' => 'Agreement Execution Date',
            'rmc_start_date' => 'Project Start Date',
            'rmc_end_date' => 'Project End Date',

            'project_title' => 'Project Title / Research Title',
            'grant_fund' => 'Grant Fund',

            'member' => 'No. of Project Members',
            'proposal' => 'proposal',
            'status' => 'Status',

            'ssm' => 'SSM',
            'company_profile' => 'Company Profile',

            'mcom_date' => 'MCOM Date',
            'mcom_series' => 'MCOM Series',

            'umc_date' => 'UMC Date',
            'umc_series' => 'UMC Series',

            'dp_doc' => 'Upload Files',
            'applicant_doc' => 'Upload Files',

            'reason' => 'Reason',
            'transfer_to' => 'OSC',
            'agreement_type' => 'Type',

            'created_at' => 'Created At',
            'updated_at' => 'Updated At',

            'isReminded' => 'Reminder Step',



            'agreement_type_other' => 'Other'
        ];
    }

    /**
     * Gets query for [[Activities]].
     *
     * @return ActiveQuery
     */

    public function getAgreementPoc()
    {
        return $this->hasMany(AgreementPoc::class, ['agreement_id' => 'id']);
    }
    public function getcollaboration(){
        return $this->hasOne(Collaboration::class, ['id' => 'col_id']);
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

        if (in_array($this->status,[Variables::agreement_MCOM_date_set, Variables::agreement_MCOM_date_changed])) $this->increaseMCOMDate();
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
