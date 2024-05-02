<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%poc}}`.
 */
class m240502_003157_create_poc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%poc}}', [
            'id' => $this->primaryKey(),
            'staff_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'phone_number' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%poc}}');
    }
}
