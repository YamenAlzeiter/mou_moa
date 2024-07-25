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

        $this->insert('{{%email_template}}', [
            'id' => 1 ,
            'subject' => 'Rejected',
            'body' =>
                '
                <p>السلام عليكم ورحمة الله وبركاته</p>

                <p>Dear {user},</p>
                
                <p>Your application ID no: {id} has been reviewed. After a thorough review, we<br />
                regret to inform you that your application is rejected due to:</p>
                
                <p>{reason}</p>
                '
        ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 2 ,
                'subject' => 'Pick MCOM DATE',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>

                    <p>Dear {user},</p>
                    
                    <p>Your application ID no:&nbsp;{id}&nbsp;is Approved by OLA. Please choose the MCOM date.</p>
                    '
            ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 3 ,
                'subject' => 'draft uploaded',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>
                    
                    <p>Dear {user},</p>
                    
                    <p>We have reviewed the draft agreement and our response are<br />
                    as follows:</p>
                    
                    <p>The same has been reflected in the track changes of the draft<br />
                    agreement.</p>
                    '
            ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 4 ,
                'subject' => 'Not Complete',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>

                    <p>&nbsp;</p>
                    
                    <p>Dear &nbsp;{user}&nbsp;,</p>
                    
                    <p>Your application ID no:&nbsp;{id}&nbsp; has been received but is not complete, please provide the missing information as follows;<br />
                    &nbsp;{reason}&nbsp;</p>
                    '
            ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 5 ,
                'subject' => 'New Application Submitted',
                'body' =>
                    '
                        <p>السلام عليكم ورحمة الله وبركاته</p>

                        <p>&nbsp;</p>
                        
                        <p>Dear&nbsp;{user},</p>
                        
                        <p>Thank you for submitting your application! Your application ID no: &nbsp;{id}&nbsp;has been received. You can expect to hear back from us regarding the status within xx working days from the date of submission. We appreciate your patience!</p>
                    '
            ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 6 ,
                'subject' => 'Application re-submitted',
                'body' =>
                    '
                        <p>السلام عليكم ورحمة الله وبركاته</p>

                        <p>&nbsp;</p>
                        
                        <p>Dear&nbsp;{user},</p>
                        
                        <p>Thank you for submitting your application! Your application ID no: &nbsp;{id}&nbsp;has been received. You can expect to hear back from us regarding the status within xx working days from the date of submission. We appreciate your patience!</p>
                    '
            ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 7 ,
                'subject' => 'Draft Not Recommended',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>

                    <p>Dear {user},</p>
                    
                    <p>Your draft agreement {id} has been<br />
                    reviewed and not recommended due to: {reason}.</p>

                    '
            ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 8 ,
                'subject' => 'Final Draft Uploaded',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>

                    <p>Dear {user},</p>
                    
                    <p>Your draft agreement {id} has been<br />
                    reviewed and ready for execution.</p>

                    '
            ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 9 ,
                'subject' => 'UMC Approve',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>

                    <p>Dear {user},</p>
                    
                    <p>We are pleased to inform you that your application<br />
                    has been approved in principle by the University<br />
                    Management Committee No.x/2024 dated on {reason}. We will review the draft agreement within 21 working days. .</p>
                    '
            ]);
        $this->insert('{{%email_template}}', ['id' => 10, 'subject' => 'Reminder', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}',
            [
                'id' => 11,
                'subject' => 'MCOM date Changed',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>

                    <p>Dear {user},</p>
                    
                    <p>Please be informed that there has been a<br />
                    change in the date scheduled for the<br />
                    MCOM Meeting. Your next MCOM date<br />
                    will be on: {date}.</p>
                    '
            ]);
        $this->insert('{{%email_template}}', ['id' => 12, 'subject' => 'Agreement Expired', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}',
            [
                'id' => 13,
                'subject' => 'MCOM Approve',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>
                    
                    <p>Dear&nbsp;{user}&nbsp;,</p>
                    
                    <p>Congratulations. We are pleased to inform<br />
                    you that your application {id} has been<br />
                    recommended for approval in principle by the<br />
                    MCOM No.x/2024 dated on {date}.<br />
                    Your application will be deliberated in the<br />
                    UMC Meeting No.x/2024 dated on&nbsp;{reason}&nbsp;for<br />
                    approval.</p>
                    '
            ]);
        $this->insert('{{%email_template}}', ['id' => 14, 'subject' => 'MCOM Special', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}',
            [
                'id' => 15,
                'subject' => 'MCOM KIV',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>

                    <p>Dear {user},</p>
                    
                    <p>Please be informed that your application has<br />
                    been recommended for KIV due to:&nbsp;{reason}&nbsp;<br />
                    during MCOM No.x/ 2024 dated on {date}.<br />
                    You are advice to match all requirments. Once all the<br />
                    action above has been completed, please<br />
                    resubmit to Office of Legal Adviser.</p>
                    '
            ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 16,
                'subject' => 'MCOM Reject',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>

                    <p>Dear&nbsp;{user}&nbsp;,</p>
                    
                    <p>We regret to inform you that MCOM has<br />
                    rejected your application ID no: {id}<br />
                    due to {reason}. We appreciate your<br />
                    interest and effort.</p>
                    '
            ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 17,
                'subject' => 'UMC Reject',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>

                    <p>Dear {user},</p>
                    
                    <p>We regret to inform you that your application has<br />
                    been rejected by the University Management<br />
                    Committee No. due<br />
                    to {reason}. We appreciate your interest and<br />
                    effort.</p>
                    '
            ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 18,
                'subject' => 'UMC KIV',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>

                    <p>Dear Applicant/ OSC,</p>
                    
                    <p>Please be informed that your application has been KIV due<br />
                    to: {reason&nbsp; }during University Management Committee. You are advice to..... Once all the action above has been<br />
                    completed, please resubmit to Office of Legal Adviser.</p>
                    '
            ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 19,
                'subject' => 'MCOM Resubmit – Applicant resubmit',
                'body' =>
                    '
                    <p>السلام عليكم ورحمة الله وبركاته</p>

                    <p>Dear {user},</p>
                    
                    <p>Please be informed that your resubmission of application {id}<br />
                    has been received. and MCOM date upldated to {date}</p>
                    '
            ]);
        $this->insert('{{%email_template}}',
            [
                'id' => 20,
                'subject' => 'Agreement Executed',
                'body' =>
                    '
                   
                    '
            ]);
       
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%email_template}}');
    }
}
