<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log}}`.
 */
class m240320_055809_create_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'agreement_id' => $this->integer(),

            'old_status' => $this->integer(),
            'new_status' => $this->integer(),

            'message' => $this->text(),
            'changes' => 'hstore',


            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),

            'created_by' => $this->string(255),
        ]);
        $this->addForeignKey(
            'fk-log',
            '{{log}}',
            'agreement_id',
            '{{agreement}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%log}}');
    }
}
