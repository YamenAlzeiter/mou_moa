<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%import}}`.
 */
class m240417_080345_create_import_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%import}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(50),
            'import_from' => $this->string(10),
            'directory' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%import}}');
    }
}
