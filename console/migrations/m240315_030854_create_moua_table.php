<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%moua}}`.
 */
class m240315_030854_create_moua_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%agreement}}', [
            'id' => $this->primaryKey(),

            //Collaborator Details
            'col_organization' => $this->string(522),
            'col_name' => $this->string(522),
            'col_address' => $this->string(522),
            'col_contact_details' => $this->string(522),
            'col_collaborators_name' => $this->string(522),
            'col_wire_up' => $this->string(522),
            'col_phone_number' => $this->string(512),
            'col_email' => $this->string(512),

            //Pi Details
            'pi_name' => $this->string(522),
            'pi_kulliyyah' => $this->string(522),
            'pi_phone_number' => $this->string(512),
            'pi_email' => $this->string(512),

            //research/ project
            'project_title' => $this->text(),
            'grant_fund' => $this->string(),
            'sign_date' => $this->date(),
            'end_date'  => $this->date(),
            'member' => $this->string(2),
            'progress' => $this ->text(),

            //status
            'status' => $this->integer(),

            //extra
            'ssm' => $this->string(522),
            'company_profile' => $this->string(),
            'mcom_date' => $this->date(),
            'meeting_link' => $this->string(),
            'agreement_type' => $this->string(),

            //docs
            'doc_applicant' => $this->string(522),

            'doc_draft' => $this->string(522),
            'doc_newer_draft' => $this->string(522),
            'doc_re_draft' => $this->string(522),

            'doc_final' => $this->string(522),
            //in case extra one needed
            'doc_extra' => $this->string(522),
            //messages
            'reason' => $this->text(),
        ]);

        //in case need foreign key
//        $this->addForeignKey(
//            'fk-agreement-user-id', // name of the foreign key constraint
//            '{{%agreement}}', // child table (agreement table)
//            'user_id', // name of the column in the child table
//            '{{%user}}', // parent table (user table)
//            'id', // name of the column in the parent table
//            'CASCADE', // on delete
//            'CASCADE' // on update
//        );

        $this->execute("
            CREATE OR REPLACE FUNCTION before_insert_agreement() 
            RETURNS TRIGGER AS $$
            DECLARE
                max_id INT;
            BEGIN
                SELECT COALESCE(MAX(id % 1000), 0) INTO max_id FROM agreement;
                NEW.id := CONCAT(EXTRACT(YEAR FROM CURRENT_DATE) % 100, LPAD(EXTRACT(MONTH FROM CURRENT_DATE)::TEXT, 2, '0'), LPAD((max_id + 1)::TEXT, 3, '0'));
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Create the trigger
        $this->execute("
            CREATE TRIGGER before_insert_agreement 
            BEFORE INSERT ON agreement
            FOR EACH ROW 
            EXECUTE FUNCTION before_insert_agreement();
        ");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%agreement}}');
    }
}
