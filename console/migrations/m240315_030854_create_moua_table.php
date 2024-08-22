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
            'ref_old_agreement' => $this->integer(),
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
            'umc_date' => $this->date(),
            'last_reminder' => $this->date(),


            'umc_series' => $this->string(100),
            'mcom_series' => $this->string(100),
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

        // Create or replace the trigger function
        $this->execute("
            CREATE OR REPLACE FUNCTION before_insert_agreement()
            RETURNS TRIGGER AS $$
           
            DECLARE
                max_id INT;
                new_id TEXT;
                current_seq_value TEXT;
            BEGIN
                -- Generate the new ID based on the current maximum ID
                SELECT COALESCE(MAX(id % 1000), 0) INTO max_id FROM agreement;
                new_id := CONCAT(
                    EXTRACT(YEAR FROM CURRENT_DATE) % 100, 
                    LPAD(EXTRACT(MONTH FROM CURRENT_DATE)::TEXT, 2, '0'), 
                    LPAD((max_id + 1)::TEXT, 4, '0')
                );

                -- Get the next value from the sequence
                SELECT last_value FROM agreement_id_seq INTO current_seq_value;

                -- Update the sequence to ensure it is always less than the new ID
                IF new_id > current_seq_value 
				THEN

	                NEW.id := new_id;

                    EXECUTE format(
                        'ALTER SEQUENCE agreement_id_seq RESTART WITH %s',
                        new_id
                    );
              
				ELSE
				 NEW.id = current_seq_value;
				END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Create the trigger
        $this->execute("
            CREATE TRIGGER before_insert_agreement_trigger
            BEFORE INSERT ON {{%agreement}}
            FOR EACH ROW
            EXECUTE FUNCTION before_insert_agreement();
        ");
        // Create the trigger function for logging changes
        $this->execute("
            CREATE OR REPLACE FUNCTION log_changes()
            RETURNS TRIGGER AS $$
            DECLARE
                log_message TEXT := '';
                change_record hstore := hstore('');
                key TEXT;
                old_value TEXT;
                new_value TEXT;
                resubmitted BOOLEAN := FALSE;
                imported BOOLEAN := FALSE;
                inserted BOOLEAN := FALSE;
                special BOOLEAN := FALSE;
            BEGIN
                -- Only log if status has changed
                IF OLD.status IS DISTINCT FROM NEW.status THEN
                    -- Determine log message based on status change
                    IF (OLD.status = 2 AND NEW.status = 15) THEN 
                        resubmitted := TRUE;
                    END IF;

                    IF (NEW.status = 100 OR NEW.status = 102) THEN 
                        imported := TRUE;
                    END IF;

                    IF (NEW.status = 10) THEN 
                        inserted := TRUE;
                    END IF;

                    IF (NEW.status = 91) THEN 
                        special := TRUE;
                    END IF;

                    IF resubmitted OR imported OR inserted OR special OR NEW.status IN (2, 12, 31, 32, 33, 34, 41, 42, 43, 82) THEN
                        log_message := CASE
                            WHEN inserted THEN 'New Application Submitted'
                            WHEN resubmitted THEN 'Application Resubmitted'
                            WHEN imported THEN 'Application Imported'
                            WHEN special THEN 'Application has been created by OLA, skipping all normal processes.'
                            ELSE COALESCE(NEW.reason, 'No reason provided')
                        END;
                    END IF;

                    -- Log the changes in the 'agreement' table
                    FOR key IN
                        SELECT column_name
                        FROM information_schema.columns
                        WHERE table_name = 'agreement' AND column_name NOT IN ('created_at', 'updated_at', 'status', 'reason', 'temp')
                    LOOP
                        EXECUTE 'SELECT $1.' || quote_ident(key) INTO old_value USING OLD;
                        EXECUTE 'SELECT $1.' || quote_ident(key) INTO new_value USING NEW;

                        IF old_value IS DISTINCT FROM new_value THEN
                            change_record := change_record || hstore(key, 'Old: ' || COALESCE(old_value, 'NULL') || ' | New: ' || COALESCE(new_value, 'NULL'));
                        END IF;
                    END LOOP;

                    INSERT INTO log (agreement_id, old_status, new_status, message, changes, created_by)
                    VALUES (NEW.id, OLD.status, NEW.status, log_message, change_record, NEW.temp);
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Create the trigger for logging changes
        $this->execute("
            CREATE TRIGGER before_insert_update_agreement
            BEFORE INSERT OR UPDATE ON agreement
            FOR EACH ROW 
            EXECUTE FUNCTION log_changes();
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
