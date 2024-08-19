<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%collaboration}}`.
 */
class m240315_030853_create_collaboration_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%collaboration}}', [
            'id' => $this->primaryKey(),
            'col_organization' => $this->string(150)->unique(),
            'col_name' => $this->string(100),
            'col_address' => $this->string(522),
            'col_contact_details' => $this->string(100),
            'col_collaborators_name' => $this->string(100),
            'col_wire_up' => $this->string(255),
            'col_phone_number' => $this->string(20),
            'col_email' => $this->string(50),
            'country' => $this->string(50),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%collaboration}}');
    }
}
