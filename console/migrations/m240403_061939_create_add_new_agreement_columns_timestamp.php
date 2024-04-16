<?php

use yii\db\Migration;

/**
 * Class m240403_061939_create_add_new_agreement_columns_timestamp
 */
class m240403_061939_create_add_new_agreement_columns_timestamp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%agreement}}', 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->addColumn('{{%agreement}}', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240403_061939_create_add_new_agreement_columns_timestamp cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240403_061939_create_add_new_agreement_columns_timestamp cannot be reverted.\n";

        return false;
    }
    */
}
