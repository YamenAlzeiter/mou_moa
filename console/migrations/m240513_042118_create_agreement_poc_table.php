<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%agreement_poc}}`.
 */
class m240513_042118_create_agreement_poc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%agreement_poc}}', [
            'id' => $this->primaryKey(),
            'agreement_id' => $this->integer(),
            'pi_name' => $this->string(100),
            'pi_email' => $this->string(50),
            'pi_phone' => $this->string(15),
            'pi_kcdio' => $this->string(7),
            'pi_address' => $this->string(255),
            'pi_role' => $this->string(30),
            'pi_is_primary' => $this->boolean()->defaultValue(false),
            ]);
        $this->addForeignKey(
            'fk-agreement_poc',
            '{{agreement_poc}}',
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
        $this->dropTable('{{%agreement_poc}}');
    }
}
