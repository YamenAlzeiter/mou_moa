<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%remider}}`.
 */
class m240426_001439_create_remider_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reminder}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(),
            'reminder_before' => $this->integer(2),
        ]);
        $this->insert('{{%reminder}}', ['type' => 'MONTH', 'reminder_before' => 6]);
        $this->insert('{{%reminder}}', ['type' => 'MONTH', 'reminder_before' => 3]);
        $this->insert('{{%reminder}}', ['type' => 'MONTH', 'reminder_before' => 1]);
        $this->insert('{{%reminder}}', ['type' => 'DAY'  , 'reminder_before' => 10]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%remider}}');
    }
}
