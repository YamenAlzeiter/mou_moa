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
                //status
                'status' => $this->integer(),
                //Collaborator Details
                'col_organization' => $this->string(522),
                'col_name' => $this->string(522),
                'col_address' => $this->string(522),
                'col_contact_details' => $this->string(522),
                'col_collaborators_name' => $this->string(522),
                'col_wire_up' => $this->string(522),
                'col_phone_number' => $this->string(512),
                'col_email' => $this->string(512),
                'country' => $this->string(522),
                //primary person in charge
                'champion' => $this->string(522),
                //research/ project
                'project_title' => $this->text(),
                'grant_fund' => $this->string(),
                'sign_date' => $this->date(),
                'end_date'  => $this->date(),
                'member' => $this->string(2),
                'progress' => $this ->text(),
                //extra
                'ssm' => $this->string(522),
                'company_profile' => $this->string(),
                'mcom_date' => $this->date(),
                'meeting_link' => $this->string(),
                'agreement_type' => $this->string(),
                'transfer_to' => $this->string(),
                'proposal' => $this->string(),
                //docs
                'applicant_doc' =>$this->text(),
                'dp_doc' =>$this->text(),
                //messages
                'reason' => $this->text(),
                //time
                'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
                //details
                'pi_details' => $this->text(),
                'col_details' => $this->text(),
                'collaboration_area' => $this->text(),
                //reminder
                'isReminded' => $this->integer(1)->defaultValue(0),//this for application
                'last_reminder' => $this->date(),//this for activities
                //temp
                'temp' => $this->text(),

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

//        $this->execute("
//            CREATE OR REPLACE FUNCTION clear_draft_fields()
//            RETURNS TRIGGER AS $$
//            BEGIN
//                IF NEW.status = 81 THEN
//                    NEW.doc_applicant := '';
//                    NEW.doc_draft := '';
//                    NEW.doc_newer_draft := '';
//                END IF;
//                RETURN NEW;
//            END;
//            $$ LANGUAGE plpgsql;
//        ");

        // Create the trigger
//        $this->execute("
//            CREATE TRIGGER clear_files_on_status_81
//            BEFORE UPDATE ON agreement
//            FOR EACH ROW
//            WHEN (NEW.status = 81 AND OLD.status <> 81) -- Trigger only on change TO 81
//            EXECUTE FUNCTION clear_draft_fields();
//        ");
        $this->execute("
         CREATE OR REPLACE FUNCTION create_status_log()
RETURNS trigger AS $$ 
BEGIN
   -- Access changed attributes using special variables
IF (TG_OP = 'INSERT' AND NEW.status = 10) OR 
     (TG_OP = 'INSERT' AND NEW.status = 100 OR TG_OP = 'INSERT' AND NEW.status = 102) OR
      (TG_OP = 'UPDATE' AND OLD.status != NEW.status)
   THEN
       DECLARE
           old_status INTEGER := (CASE WHEN TG_OP = 'UPDATE' THEN OLD.status ELSE 0 END);
           new_status INTEGER := NEW.status;
           reason TEXT := NEW.reason;
           log_message TEXT;
         resubmitted bool;
         imported bool;
         inserted bool;

       BEGIN 

       IF (OLD.status = 2 AND NEW.status = 15) THEN resubmitted = TRUE;
                                        ELSE resubmitted = FALSE;
       END IF;
       IF (TG_OP = 'INSERT' AND NEW.status = 100 OR TG_OP = 'INSERT' AND NEW.status = 102) THEN imported = TRUE;
                                        ELSE imported = FALSE;
       END IF;
       IF (TG_OP = 'INSERT' AND NEW.status = 10) THEN inserted = TRUE;
                                        ELSE inserted = FALSE;
       END IF;
       --what status need reason message 
         IF ( resubmitted = TRUE
                      OR imported = TRUE
                      OR TG_OP = 'INSERT' 
                      OR NEW.status = 2 -- Not Recommended from OSC
                     OR NEW.status = 12 -- Not Recommended from OLA
                     OR NEW.status = 42 -- Rejected After UMC
                     OR NEW.status = 43 -- KIV After UMC
                     OR NEW.status = 82 -- Newer Draft Not Recommended
         ) THEN 
          -- Build the log message
           log_message := CASE 
                            WHEN inserted = TRUE THEN 'New Application Submitted' 
                     WHEN resubmitted = TRUE THEN 'Application Resubmitted'
                     WHEN imported = TRUE THEN 'Application Imported'
                            ELSE reason 
                          END;
       END IF;
           -- Insert into log table 
           INSERT INTO log (agreement_id, old_status, new_status, message)
           VALUES (NEW.id, old_status, new_status, log_message);
       END;
   END IF;

   RETURN NEW; 
END;
$$ LANGUAGE plpgsql;
       
      
        ");
        $this->execute("
             CREATE TRIGGER log
            AFTER INSERT OR UPDATE ON agreement -- Replace 'your_agreement_table' 
            FOR EACH ROW 
            EXECUTE PROCEDURE create_status_log();
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
