<?php

use yii\db\Migration;

/**
 * Class m240426_002901_create_add_new_agreement_isremided_column
 */
class m240426_002901_create_add_new_agreement_isremided_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%agreement}}', 'isReminded', $this->integer(1)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240426_002901_create_add_new_agreement_isremided_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240426_002901_create_add_new_agreement_isremided_column cannot be reverted.\n";

        return false;
    }
    */
}
