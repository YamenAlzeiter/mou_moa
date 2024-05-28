<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mcom_date}}`.
 */
class m240319_020747_create_mcom_date_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mcom_date}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date(),
            'counter' => $this->integer()->unsigned()->check('counter <= 10'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%mcom_date}}');
    }
}
