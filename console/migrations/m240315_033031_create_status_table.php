<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status}}`.
 */
class m240315_033031_create_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%status}}', [
            'id' => $this->primaryKey(),
            'status' => $this->integer(3),
            'tag' => $this->string(20),
            'description' => $this->string(522)
        ]);

        $this->insert('{{%status}}', ['status' => 1, 'tag' => 'OSC-A', 'description' => 'Approved by OSC']);
        $this->insert('{{%status}}', ['status' => 2, 'tag' => 'OSC-R', 'description' => 'Rejected by OSC']);
        $this->insert('{{%status}}', ['status' => 10, 'tag' => 'NA001', 'description' => 'New Agreement']);
        $this->insert('{{%status}}', ['status' => -10, 'tag' => 'ExtendedAgr', 'description' => 'Extended Agreement']);
        $this->insert('{{%status}}', ['status' => 11, 'tag' => 'OLA-A', 'description' => 'Approved by OLA']);
        $this->insert('{{%status}}', ['status' => 12, 'tag' => 'OLA-R', 'description' => 'Rejected by OLA']);
        $this->insert('{{%status}}', ['status' => 15, 'tag' => 'RE-SUB', 'description' => 'resubmited   ']);
        $this->insert('{{%status}}', ['status' => 21, 'tag' => 'MCOM-MTG', 'description' => 'MOCM set has been set']);
        $this->insert('{{%status}}', ['status' => 31, 'tag' => 'MCOM-A', 'description' => 'Accepted by MCOM']);
        $this->insert('{{%status}}', ['status' => 32, 'tag' => 'MCOM-R', 'description' => 'Rejected by MCOM']);
        $this->insert('{{%status}}', ['status' => 33, 'tag' => 'MCOM-C', 'description' => 'Document Required']);
        $this->insert('{{%status}}', ['status' => 34, 'tag' => 'MCOM-COND', 'description' => 'Conditional Approve by MCOM']);
        $this->insert('{{%status}}', ['status' => 41, 'tag' => 'UMC-A', 'description' => 'Approved by UMC']);
        $this->insert('{{%status}}', ['status' => 42, 'tag' => 'UMC-R', 'description' => 'Rejected by UMC ']);
        $this->insert('{{%status}}', ['status' => 43, 'tag' => 'UMC-C', 'description' => 'Document Required ']);
        $this->insert('{{%status}}', ['status' => 46, 'tag' => 'COND-UPD', 'description' => 'Conditional Recommendation Updated']);
        $this->insert('{{%status}}', ['status' => 47, 'tag' => 'COND-C', 'description' => 'Conditional Recommendation is Not Complete']);
        $this->insert('{{%status}}', ['status' => 51, 'tag' => 'OLA-DFT', 'description' => 'Draft Uploaded by OLA']);
        $this->insert('{{%status}}', ['status' => 61, 'tag' => 'OSC-U', 'description' => 'OSC Upload Draft']);
        $this->insert('{{%status}}', ['status' => 71, 'tag' => 'OLA-DA', 'description' => 'Draft Approved by OLA']);
        $this->insert('{{%status}}', ['status' => 72, 'tag' => 'OLA-DR', 'description' => 'Draft Rejected by OLA']);
        $this->insert('{{%status}}', ['status' => 81, 'tag' => 'OLA-FDA', 'description' => 'OLA Approve Final Draft']);
        $this->insert('{{%status}}', ['status' => 82, 'tag' => 'RJCT', 'description' => 'Applicaition Rejected']);
        $this->insert('{{%status}}', ['status' => 91, 'tag' => 'AGR-EXC', 'description' => 'Excuted Agreement']);
        $this->insert('{{%status}}', ['status' => 92, 'tag' => 'AGR-EXP', 'description' => 'Agreement Expired']);
        $this->insert('{{%status}}', ['status' => 100, 'tag' => 'I-AGR-EXC', 'description' => 'Excuted Agreement']);
        $this->insert('{{%status}}', ['status' => 102, 'tag' => 'I-AGR-EXP', 'description' => 'Expired Agreement']);
        $this->insert('{{%status}}', ['status' => 110, 'tag' => 'REMINDER', 'description' => 'Reminder for agreement update']);
        $this->insert('{{%status}}', ['status' => 111, 'tag' => 'ExtendedAgr', 'description' => 'Extended Agreement']);
        $this->insert('{{%status}}', ['status' => 112, 'tag' => 'Re-New-Agr', 'description' => 'Agreement renewed']);
        $this->insert('{{%status}}', ['status' => 121, 'tag' => 'AGR_EXT', 'description' => 'Agreement Extended']);
        $this->insert('{{%status}}', ['status' => 13, 'tag' => 'OLA-ACircu', 'description' => 'Approved in paper by circulation']);
        $this->insert('{{%status}}', ['status' => 14, 'tag' => 'MCOM-A-OLA', 'description' => 'Approved by legal Adviser via power delegated by UMC']);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%status}}');
    }
}
