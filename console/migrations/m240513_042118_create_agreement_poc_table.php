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
            'pi_name' => $this->string(),
            'pi_email' => $this->string(),
            'pi_phone' => $this->string(),
            'pi_kcdio' => $this->string(),
            'pi_address' => $this->string(),
            'role' => $this->string(),
            'is_primary' => $this->boolean(),
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
