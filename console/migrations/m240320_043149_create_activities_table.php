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

            //Student Mobility for Credited
            'type'              => $this->text    (),
            'number_students'   => $this->integer(3),
            'name_students'     => $this->text    (),
            'semester'          => $this->text    (),
            'year'              => $this->text    (),

            //Student Mobility for Non_Credited
            'non_type'            => $this->text    (),
            'non_number_students' => $this->integer(3),
            'non_name_students'   => $this->text    (),
            'non_semester'        => $this->text    (),
            'non_year'            => $this->text    (),
            'non_program_name'    => $this->text    (), //only for non-Credited

            //Staff Mobility (Inbound)
            'in_number_of_staff'    => $this->integer(3),
            'in_staffs_name'        => $this->text  (),
            'in_department_office'  => $this->text  (),

            //Staff Mobility (Outbound)
            'out_number_of_staff'   => $this->integer(3),
            'out_staffs_name'       => $this->text(),

            //Seminar/Conference/Workshop/Training
            'scwt_name_of_program'      => $this->text(),
            'date_of_program'           => $this->date(),
            'program_venue'             => $this->text(),
            'participants_number'       =>$this->integer(3),
            'name_participants_involved'=> $this->text(),

            //Research
            'research_title' => $this->text(),

            //Publication
            'publication_title' => $this->text(),
            'publisher'         => $this->text(),

            //Consultancy
            'consultancy_name' => $this->text(),
            'project_duration' => $this->text(),

            //Any other of Cooperation, Please specify
            'other' => $this->text(),
            'date' => $this->date(),

            //No Activity, Please specify
            'justification' => $this->text(),
            'upload_files' => $this->text(),
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
