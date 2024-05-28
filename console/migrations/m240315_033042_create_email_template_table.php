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

        $this->insert('{{%email_template}}', ['id' => 1 , 'subject' => 'Rejected', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}', ['id' => 2 , 'subject' => 'Pick MCOM DATE', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}', ['id' => 3 , 'subject' => 'draft uploaded', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}', ['id' => 4 , 'subject' => 'Not Complete', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}', ['id' => 5 , 'subject' => 'New Application Submitted', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}', ['id' => 6 , 'subject' => 'Application re-submitted', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}', ['id' => 7 , 'subject' => 'Draft Not Recommended', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}', ['id' => 8 , 'subject' => 'Final Draft Uploaded', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}', ['id' => 9 , 'subject' => 'UMC Approve', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}', ['id' => 10, 'subject' => 'Reminder', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}', ['id' => 11, 'subject' => 'MCOM date Changed', 'body' => 'Edit This']);
        $this->insert('{{%email_template}}', ['id' => 12, 'subject' => 'Agreement Expired', 'body' => 'Edit This']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%email_template}}');
    }
}
