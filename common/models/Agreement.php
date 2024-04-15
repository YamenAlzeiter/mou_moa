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
 * @property string|null $pi_name
 * @property string|null $pi_kulliyyah
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property string|null $pi_phone_number
 * @property string|null $country
 * @property string|null $pi_email
 * @property string|null $project_title
 * @property string|null $grant_fund
 * @property string|null $sign_date
 * @property string|null $end_date
 * @property string|null $member
 * @property string|null $proposal
 * @property int|null $status
 * @property string|null $ssm
 * @property string|null $company_profile
 * @property string|null $mcom_date
 * @property string|null $meeting_link
 * @property string|null $agreement_type
 * @property string|null $doc_applicant
 * @property string|null $doc_draft
 * @property string|null $doc_newer_draft
 * @property string|null $doc_re_draft
 * @property string|null $doc_final
 * @property string|null $doc_extra
 * @property string|null $reason
 * @property string|null $transfer_to
 * @property Activities[] $activities
 * @property Log[] $logs
 */
class Agreement extends ActiveRecord
{
    public $submitter;

    public $fileUpload;
    public $olaDraft;
    public $oscDraft;
    public $finalDraft;
    public $executedAgreement;

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
                ['fileUpload', 'olaDraft', 'oscDraft', 'finalDraft', 'executedAgreement'], 'file', 'extensions' => 'docx, pdf'
            ],
            [
                [
                    'finalDraft', 'oscDraft', 'olaDraft', 'fileUpload', 'col_organization', 'col_name', 'col_address',
                    'col_contact_details', 'col_collaborators_name', 'col_wire_up', 'pi_name', 'pi_kulliyyah', 'ssm',
                    'project_title', 'proposal', 'col_phone_number', 'col_email', 'pi_phone_number', 'pi_email',
                    'company_profile', 'grant_fund', 'member', 'transfer_to', 'agreement_type'
                ], 'required', 'on' => 'uploadCreate'
            ], [['pi_email', 'col_email'], 'email'], [['project_title', 'proposal', 'reason'], 'string'],
            [['sign_date', 'end_date', 'mcom_date', 'created_at', 'updated_at'], 'safe'],
            [['status'], 'default', 'value' => null], [['status'], 'integer'], [
                [
                    'col_organization', 'col_name', 'col_address', 'col_contact_details', 'col_collaborators_name',
                    'col_wire_up', 'pi_name', 'pi_kulliyyah', 'ssm', 'doc_applicant', 'doc_draft', 'doc_newer_draft',
                    'doc_re_draft', 'doc_final', 'doc_extra', 'transfer_to', 'agreement_type', 'country'
                ], 'string', 'max' => 522
            ], [['col_phone_number', 'col_email', 'pi_phone_number', 'pi_email'], 'string', 'max' => 512],
            [['grant_fund', 'company_profile', 'meeting_link'], 'string', 'max' => 255],
            [['member'], 'string', 'max' => 2],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID', 'col_organization' => 'Organization', 'col_name' => 'Name', 'col_address' => 'Address',
            'col_contact_details' => 'Contact Details', 'col_collaborators_name' => 'Collaborators Name',
            'col_wire_up' => 'Wire Up', 'col_phone_number' => 'Phone Number', 'col_email' => 'Email',
            'pi_name' => 'Name', 'pi_kulliyyah' => 'Kulliyyah', 'pi_phone_number' => 'Phone Number',
            'pi_email' => 'Email', 'project_title' => 'Project Title', 'grant_fund' => 'Grant Fund',
            'sign_date' => 'Sign Date', 'end_date' => 'End Date', 'member' => 'Member', 'proposal' => 'proposal',
            'status' => 'Status', 'ssm' => 'SSM', 'company_profile' => 'Company Profile', 'mcom_date' => 'MCOM Date',
            'meeting_link' => 'Meeting Link', 'doc_applicant' => 'Document Applicant', 'doc_draft' => 'Document Draft',
            'doc_newer_draft' => 'Document Newer Draft', 'doc_re_draft' => 'Document Re-Draft',
            'doc_final' => 'Document Final', 'doc_extra' => 'Document Extra', 'reason' => 'Reason',
            'transfer_to' => 'direction', 'agreement_type' => 'type', 'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

        // Handle status log creation based on specific conditions
        $this->createStatusLogIfStatusChanged($changedAttributes);

        //delete draft files when status become 81 AKA ACTV
        if($this->status == 81) $this->deleteDrafts();
    }


    protected function deleteDrafts()
    {
        $fileLocations = [
            $this->doc_applicant,
            $this->doc_draft,
            $this->doc_newer_draft
        ];

        foreach ($fileLocations as $filePath) {
            if (is_file($filePath)) {  // Use is_file() to check existence
                FileHelper::unlink($filePath);
                Yii::info("File deleted: $filePath", __METHOD__);
            } else {
                Yii::warning("File not found: $filePath", __METHOD__);
            }
        }
    }
    protected function createStatusLogIfStatusChanged(array $changedAttributes)
    {

        if (!isset($changedAttributes['status']) && !$this->status == 10) {
            return; // No status change, nothing to log
        }

        if (isset($changedAttributes['status'])) {
            //check if new application initiated
            $isInit = !isset($changedAttributes['status']) && $this->status == 10;

            $oldStatus = $isInit ? 0 : $changedAttributes['status'];
            $newStatus = $this->status;


            if ($oldStatus == $newStatus) {
                return; // Status hasn't changed, no need to log
            }

            $reasonMap = [
                // Old Status => New Status (requires reason)
                10 => 2, //transition from 10 to 2 requires reason
                1 => 12, // Transition from 1 to 12 requires reason
                21 => [32, 33], 31 => [42, 43], 82 => true, // Status 82 always requires reason
                33 => true, // Status 33 always requires reason
                43 => true, // Status 43 always requires reason
            ];

            $needsReason = isset($reasonMap[$oldStatus]);
            $message = $needsReason ? $this->reason : null;

            $message = $isInit ? 'New Application Submitted' : $message;

            $this->createStatusLog($oldStatus, $newStatus, $message);
        }
    }

    protected function createStatusLog($oldStatus, $newStatus, $message)
    {
        $log = new Log();
        $log->agreement_id = $this->id;
        $log->old_status = $oldStatus;
        $log->new_status = $newStatus;
        $log->message = $message;
        $log->save();
    }

}
