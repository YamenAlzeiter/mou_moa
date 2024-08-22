<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%email_template}}`.
 */
class m240315_033042_create_email_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%email_template}}', [
            'id' => $this->primaryKey(),
            'subject' => $this->string(522),
            'body' => $this->text(),
        ]);

        $templates = [
            [
                'id' => 1,
                'subject' => 'Agreement Init',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user}</p>
                
                <p>Your application ID no: {id} has been received and you will be notified on the status of submission within xx working days from the date of submission.</p>
                
                HTML
            ],
            [
                'id' => 2,
                'subject' => 'Agreement not complete (OSC)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Your application ID no: {id} has been received but is not complete, please provide the missing information as follows;</p>
                
                HTML
            ],
            [
                'id' => 3,
                'subject' => 'Agreement Rejected',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Your application ID no: {id} has been reviewed. After a thorough review, we regret to inform you that your application is rejected due to:</p>
                
                <p>{reason}</p>                
                HTML
            ],
            [
                'id' => 4,
                'subject' => 'Review & Complete (OLA)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Your application ID no: {id} is complete. Please choose the MCOM date.</p>
                
                HTML
            ],
            [
                'id' => 5,
                'subject' => 'Review & Reject (OLA)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Your application ID no: {id} is rejected due to</p>
                
                <p>{reason}</p>
                
                HTML
            ],
            [
                'id' => 6,
                'subject' => 'MCOM Date Picked (Applicant)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Your MCOM date will be on: {MCOM_date} .</p>

                HTML
            ],
            [
                'id' => 7,
                'subject' => 'MCOM Date Changed (OLA)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Please be informed that there has been a change in the date scheduled for the MCOM Meeting. Your next MCOM date will be on: {MCOM_date}.</p>
                
                HTML
            ],
            [
                'id' => 8,
                'subject' => 'MCOM Approved (OLA)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Congratulations. We are pleased to inform you that your application has been recommended for approval in principle by the MCOM No.x/2024 dated on {MCOM_date}.</p>
                
                <p>{reason}</p>
                
                <p>Your application will be deliberated in the UMC Meeting No.x/2024 dated on {UMC_date} for approval.</p>
                
                HTML
            ],
            [
                'id' => 9,
                'subject' => 'MCOM KIV (OLA)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Please be informed that your application has been recommended for KIV due to: {reason} during MCOM No.x/ 2024 dated on {MCOM_date}. You are advice to</p>
                
                <p>{advice}</p>
                
                <p>Once all the action above has been completed, please resubmit to Office of Legal Adviser.</p>
                
                HTML
            ],
            [
                'id' => 10,
                'subject' => 'MCOM Reject (OLA)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>We regret to inform you that MCOM has rejected your application ID no: {id} due to</p>
                
                <p>{reason}</p>
                
                <p>We appreciate your interest and effort.</p>
                
                HTML
            ],
            [
                'id' => 11,
                'subject' => 'MCOM Resubmitted (Applicant)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Please be informed that your resubmission of agreement {id} has been received.</p>
                
                HTML
            ],
            [
                'id' => 12,
                'subject' => 'UMC Approved (OLA)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>We are pleased to inform you that your application has been {principle} by the University Management Committee No.x/2024 dated on {UMC_date}.</p>
                
                <p>{reason}</p>
                
                <p>We will review the draft agreement within 21 working days. .</p>
                
                HTML
            ],
            [
                'id' => 13,
                'subject' => 'UMC KIV (OLA)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Please be informed that your application has been KIV due to: {reason} during University Management Committee No.x/ 2024 dated on {UMC_date}. You are advice to</p>
                
                <p>{advice}</p>
                
                <p>Once all the action above has been completed, please resubmit to Office of Legal Adviser.</p>

                HTML
            ],
            [
                'id' => 14,
                'subject' => 'UMC Reject (OLA)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>We regret to inform you that your application has been rejected by the University Management Committee No. x/ 2024 dated on {UMC_date} due to</p>
                
                <p>{reason}</p>
                
                <p>We appreciate your interest and effort.</p>

                HTML
            ],
            [
                'id' => 15,
                'subject' => 'Draft Uploaded (OLA)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>We have reviewed the draft agreement and our response are as follows:</p>
                
                <p>The same has been reflected in the track changes of the draft agreement.</p>

                HTML
            ],
            [
                'id' => 16,
                'subject' => 'Draft Uploaded (Applicant)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Your draft agreement has been received. Office of Legal Adviser will review the feedback received within 7 working days.</p>
                
                HTML
            ],
            [
                'id' => 17,
                'subject' => 'Draft Complete (OLA)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Your draft agreement has been reviewed and ready for execution.</p>

                HTML
            ],
            [
                'id' => 18,
                'subject' => 'Draft Not Complete (OLA)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Your draft agreement {id} has been reviewed and cannot be approved for execution due to the following reason:</p>
                
                <p>{reason}</p>
                
                <p>Please address the issue and resubmit the draft for further review.</p>

                HTML
            ],
            [
                'id' => 19,
                'subject' => 'Agreement Executed (OSC)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>The agreement {id} has been executed on: {execution_date}. The executed agreement has been uploaded.</p>
                
                HTML
            ],
            [
                'id' => 20,
                'subject' => 'Progress Report (Reminder)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>This is a friendly reminder that the deadline for reporting progress on the MOU/MOA is approaching. Please ensure that the necessary updates are submitted promptly.</p>
                
                <p>Thank you for your attention to this matter.</p>
                
                HTML
            ],
            [
                'id' => 21,
                'subject' => 'Expiry Date (Reminder)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>This is a reminder that the expiry date of the agreement {id} related to your MOA/MOU is approaching. Please ensure that any necessary actions are taken before the expiration date.</p>
                
                <p>Thank you for your attention to this matter.</p>
                
                HTML
            ],
            [
                'id' => 22,
                'subject' => 'Agreement Expired (Reminder)',
                'body' => <<<HTML
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>The agreement {id} has expired on {expiry_date}. Please reach out to discuss any necessary next steps on the agreement (if any).</p>
                
                HTML
            ],
            [
                'id' => 23,
                'subject' => 'Agreement Approved',
                'body' => <<<HTML
                  <p>السلام عليكم ورحمة الله وبركاته</p>

                  <p>Dear {user}</p>

                  <p>Your application ID no: {id} has been received by the Office of Legal Adviser and will be reviewed within 3 working days.</p>
                HTML
            ],[
                'id' => 24,
                'subject' => 'Agreement Approved in Paper',
                'body' => <<<HTML
                 <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>

                <p>Your application ID no: {id} has been approved by in paper by circulation NO. {circulation}</p>

                HTML
            ],[
                'id' => 25,
                'subject' => 'MCOM Approved by OLA via Power delecated by UMC',
                'body' => <<<HTML
                  <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>

                <p>Your application ID no: {id} has been approved by OLA via power delecated by UMC</p>

                HTML
            ],[
                'id' => 26,
                'subject' => 'Agreement Information Updated',
                'body' => <<<HTML
                  <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Your application Info with ID no: {id} has been Changed or updated: changes made:</p>
                
                <p>{changes}</p>
                <p>by: {applicant}</p>
                HTML
            ],[
                'id' => 27,
                'subject' => 'Agreement Extended',
                'body' => <<<HTML
                  <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Your application Info with ID no: {ref_id} has been Extended and the new one Extended Agreement {id}, please check it's information before submitting to OSC for reviewal </p>
                
                
                HTML
            ],

        ];

        foreach ($templates as $template) {
            $this->insert('{{%email_template}}', $template);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%email_template}}');
    }
}
