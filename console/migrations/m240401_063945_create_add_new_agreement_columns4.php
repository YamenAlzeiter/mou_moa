<?php

use yii\db\Migration;

/**
 * Class m240401_063945_create_add_new_agreement_columns4
 */
class m240401_063945_create_add_new_agreement_columns4 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%agreement}}', 'pi_details', $this->string());
        $this->addColumn('{{%agreement}}', 'col_details', $this->string());
        $this->addColumn('{{%agreement}}', 'collaboration_area', $this->string());
        $this->addColumn('{{%agreement}}', 'country', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240401_063945_create_add_new_agreement_columns4 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240401_063945_create_add_new_agreement_columns4 cannot be reverted.\n";

        return false;
    }
    */
}
