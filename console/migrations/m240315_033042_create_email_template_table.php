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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%email_template}}');
    }
}
