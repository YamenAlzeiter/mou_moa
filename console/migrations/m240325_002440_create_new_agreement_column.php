<?php

use yii\db\Migration;

/**
 * Class m240325_002440_create_new_agreement_column
 */
class m240325_002440_create_new_agreement_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%agreement}}', 'agreement_type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240325_002440_create_new_agreement_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240325_002440_create_new_agreement_column cannot be reverted.\n";

        return false;
    }
    */
}
