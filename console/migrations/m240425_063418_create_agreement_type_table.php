<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%agreement_type}}`.
 */
class m240425_063418_create_agreement_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%agreement_type}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(),
        ]);

        $this->insert('{{%agreement_type}}', ['type' => 'MOU']);
        $this->insert('{{%agreement_type}}', ['type' => 'MOU (Academic)']);
        $this->insert('{{%agreement_type}}', ['type' => 'MOA']);
        $this->insert('{{%agreement_type}}', ['type' => 'MOA (Academic)']);
        $this->insert('{{%agreement_type}}', ['type' => 'RCA']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%agreement_type}}');
    }
}
