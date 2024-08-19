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
            'col_id' => $this->integer(),
            //primary person in charge
            'champion' => $this->string(522),
            //research/ project
            'project_title' => $this->string(255),
            'grant_fund' => $this->string(10),
            'member' => $this->string(2),

            'agreement_type' => $this->string(50),
            'transfer_to' => $this->string(10),
            'proposal' => $this->string(255),

            //rmc additional requirement
            'rmc_start_date' => $this->date(),
            'rmc_end_date' => $this->date(),

            //oil additional requirement
            'ssm' => $this->string(25),
            'company_profile' => $this->string(),

            // dates
            'agreement_sign_date' => $this->date(),
            'agreement_expiration_date' => $this->date(),
            'execution_date' => $this->date(),
            'mcom_date' => $this->date(),
            'last_reminder' => $this->date(),

            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),

            //docs
            'applicant_doc' => $this->text(),
            'dp_doc' => $this->text(),

            //messages
            'reason' => $this->text(),

            //details
            'collaboration_area' => $this->text(),

            //reminder
            'isReminded' => $this->integer(1)->defaultValue(0),// application reminder counter

            //temp
            'temp' => $this->text(),

        ]);

        $this->addForeignKey(
            'fk-col-id',
            '{{%agreement}}',
            'col_id',
            '{{%collaboration}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

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


        $this->execute("
CREATE OR REPLACE FUNCTION create_status_log()
RETURNS trigger AS $$ 
BEGIN
   IF (TG_OP = 'INSERT' AND NEW.status = 10) OR 
      (TG_OP = 'INSERT' AND NEW.status = 91) OR
      (TG_OP = 'INSERT' AND NEW.status = 100) OR
      (TG_OP = 'INSERT' AND NEW.status = 102) OR
      (TG_OP = 'UPDATE' AND OLD.status != NEW.status)
   THEN
       DECLARE
           old_status INTEGER := CASE WHEN TG_OP = 'UPDATE' THEN OLD.status ELSE 0 END;
           new_status INTEGER := NEW.status;
           reason TEXT := NEW.reason;
           log_message TEXT;
           resubmitted BOOLEAN;
           imported BOOLEAN;
           inserted BOOLEAN;
           special BOOLEAN;
           creator TEXT := NEW.temp;

       BEGIN 
           -- Determine boolean flags based on conditions
           IF OLD.status = 2 AND NEW.status = 15 THEN 
               resubmitted := TRUE;
           ELSE 
               resubmitted := FALSE;
           END IF;

           IF TG_OP = 'INSERT' AND (NEW.status = 100 OR NEW.status = 102) THEN 
               imported := TRUE;
           ELSE 
               imported := FALSE;
           END IF;

           IF TG_OP = 'INSERT' AND NEW.status = 10 THEN 
               inserted := TRUE;
           ELSE 
               inserted := FALSE;
           END IF;

           IF TG_OP = 'INSERT' AND NEW.status = 91 THEN 
               special := TRUE;
           ELSE 
               special := FALSE;
           END IF;

           -- Determine if a log message is needed
           IF resubmitted OR imported OR TG_OP = 'INSERT' OR 
              NEW.status IN (2, 12, 31, 32, 33, 34, 41, 42, 43, 82)
           THEN 
               -- Build the log message
               log_message := CASE 
                   WHEN inserted THEN 'New Application Submitted' 
                   WHEN resubmitted THEN 'Application Resubmitted'
                   WHEN imported THEN 'Application Imported'
                   WHEN special THEN 'Application has been created by OLA skipping all normal process.'
                   ELSE reason 
               END;

               -- Insert into log table 
               INSERT INTO log (agreement_id, old_status, new_status, message, created_by)
               VALUES (NEW.id, old_status, new_status, log_message, creator);
           END IF;
       END;
   END IF;

   RETURN NEW; 
END;
$$ LANGUAGE plpgsql;
");

        $this->execute("
    CREATE TRIGGER log
    AFTER INSERT OR UPDATE ON agreement
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
