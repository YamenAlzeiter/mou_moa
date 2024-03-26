<?php

use yii\db\Migration;

/**
 * Class m240321_071135_create_new_column_agreement
 */
class m240321_071135_create_new_column_agreement extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%agreement}}', 'transfer_to', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240321_071135_create_new_column_agreement cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240321_071135_create_new_column_agreement cannot be reverted.\n";

        return false;
    }
    */
}
