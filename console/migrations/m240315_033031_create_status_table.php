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
        $this->insert('{{%status}}', ['status' => 10, 'tag' => 'NA001', 'description' => 'New Application']);
        $this->insert('{{%status}}', ['status' => 11, 'tag' => 'OLA-A', 'description' => 'Approved by OLA']);
        $this->insert('{{%status}}', ['status' => 12, 'tag' => 'OLA-R', 'description' => 'Rejected by OLA']);
        $this->insert('{{%status}}', ['status' => 21, 'tag' => 'MCOM-MTG', 'description' => 'MOCM set has been set']);
        $this->insert('{{%status}}', ['status' => 31, 'tag' => 'MCOM-A', 'description' => 'Accepted by MCOM']);
        $this->insert('{{%status}}', ['status' => 32, 'tag' => 'MCOM-R', 'description' => 'Rejected by MCOM']);
        $this->insert('{{%status}}', ['status' => 33, 'tag' => 'MCOM-C', 'description' => 'Document Required ']);
        $this->insert('{{%status}}', ['status' => 41, 'tag' => 'UMC-A', 'description' => 'Approved by UMC']);
        $this->insert('{{%status}}', ['status' => 42, 'tag' => 'UMC-R', 'description' => 'Rejected by UMC ']);
        $this->insert('{{%status}}', ['status' => 43, 'tag' => 'UMC-C', 'description' => 'Document Required ']);
        $this->insert('{{%status}}', ['status' => 51, 'tag' => 'OLA-DFT', 'description' => 'Draft Uploaded by OLA']);
        $this->insert('{{%status}}', ['status' => 61, 'tag' => 'OSC-U', 'description' => 'OSC Upload Draft']);
        $this->insert('{{%status}}', ['status' => 71, 'tag' => 'OLA-DA', 'description' => 'Draft Approved by OLA']);
        $this->insert('{{%status}}', ['status' => 81, 'tag' => 'ACTV', 'description' => 'ACTIVE']);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%status}}');
    }
}
