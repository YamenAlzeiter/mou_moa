<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%activities}}`.
 */
class m240320_043149_create_activities_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%activities}}', [
            'id'            => $this->primaryKey(),
            'agreement_id'  => $this->integer   (),
            'name'          => $this->string(522),
            'staff_number'  => $this->string(7),
            'kcdio'         => $this->string(522),
            'mou_moa'       => $this->string(522),
            'activity_type' => $this->string(522),

            //Student Mobility for Credited && non-Credited
            'type'            => $this->string(10),
            'number_students' => $this->integer(3),
            'name_students'   => $this->text    (),
            'semester'        => $this->string(10),
            'year'            => $this->date    (),
            'program_name'    => $this->string(522), //only for non-Credited

            //Staff Mobility (Inbound / outbound)
            'number_of_staff' => $this->integer(3),
            'staffs_name' => $this->text(),
            'department_office' => $this->string(100), //inbound only

            //Seminar/Conference/Workshop/Training
            'scwt_name_of_program' => $this->string(522),
            'date_of_program' => $this->date(),
            'program_venue' => $this->string(522),
            'participants_number' =>$this->integer(3),
            'name_participants_involved' => $this->string(),

            //Research
            'research_title' => $this->text(),

            //Publication
            'publication_title' => $this->string(),
            'publisher' => $this->string(),

            //Consultancy
            'consultancy_name' => $this->string(),
            'project_duration' => $this->string(50),

            //Any other of Cooperation, Please specify
            'other' => $this->string(),
            'date' => $this->date(),

            //No Activity, Please specify
            'justification' => $this->text(),
            'upload_files' => $this->string(522),
        ]);
        $this->addForeignKey(
            'fk_activity', // ForeignKey name
            '{{%activities}}', // Source table
            'agreement_id', // Source column
            '{{%agreement}}', // Target table
            'id', // Target column
            'CASCADE' // Optional: Define the ON DELETE behavior (e.g., CASCADE, SET NULL)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%activities}}');
    }
}
