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
            'agreement_id'        => $this->integer   (),
            'name'          => $this->string(100),
            'staff_email'   => $this->string(50),
            'kcdio'         => $this->text(),
            'mou_moa'       => $this->integer(),
            'activity_type' => $this->string(150),

            //Student Mobility for Credited
            'type'              => $this->string(15),
            'number_students'   => $this->integer(3),
            'name_students'     => $this->text(),
            'semester'          => $this->string(10),
            'year'              => $this->string(10),

            //Student Mobility for Non_Credited
            'non_type'            => $this->string(15),
            'non_number_students' => $this->integer(3),
            'non_name_students'   => $this->text(),
            'non_semester'        => $this->string(10),
            'non_year'            => $this->string(10),
            'non_program_name'    => $this->string(255),

            //Staff Mobility (Inbound)
            'in_number_of_staff'    => $this->integer(3),
            'in_staffs_name'        => $this->text(),

            //Staff Mobility (Outbound)
            'out_number_of_staff'   => $this->integer(3),
            'out_staffs_name'       => $this->text(),

            //Seminar/Conference/Workshop/Training
            'scwt_name_of_program'      => $this->string(255),
            'date_of_program'           => $this->date(),
            'program_venue'             => $this->string(522),
            'participants_number'       =>$this->integer(3),
            'name_participants_involved'=> $this->text(),

            //Research
            'research_title' => $this->string(),

            //Publication
            'publication_title' => $this->string(),
            'publisher'         => $this->string(),

            //Consultancy
            'consultancy_name' => $this->string(100),
            'project_duration' => $this->date(),

            //Any other of Cooperation, Please specify
            'other' => $this->text(),
            'date' => $this->date(),

            //No Activity, Please specify
            'justification' => $this->text(),
            'upload_files' => $this->text(),
        ]);
        $this->addForeignKey(
            'fk_activity',
            '{{%activities}}',
            'agreement_id',
            '{{%agreement}}',
            'id',
            'CASCADE'
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
