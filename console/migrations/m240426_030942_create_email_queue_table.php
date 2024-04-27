<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%email_queue}}`.
 */
class m240426_030942_create_email_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%email_queue}}', [
            'id' => $this->primaryKey(),
            'recipient_email' => $this->string(255),
            'email_template_id' => $this->integer(), // Renamed from 'email_template'
            'body' => $this->text(),
            'subject' => $this->string(255),
            'status' => $this->string()->defaultValue('pending'), // Use string for status
            'send_at' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'attempts' => $this->integer()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%email_queue}}');
    }
}
