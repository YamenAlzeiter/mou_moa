<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "activities".
 *
 * @property int $id
 * @property int|null $col_id
 * @property string|null $name
 * @property string|null $staff_email
 * @property string|null $kcdio
 * @property string|null $mou_moa
 * @property string|null $activity_type
 *
 * @property string|null $type
 * @property int|null $number_students
 * @property string|null $name_students
 * @property string|null $semester
 * @property string|null $year
 *
 * @property string|null $non_type
 * @property int|null $non_number_students
 * @property string|null $non_name_students
 * @property string|null $non_semester
 * @property string|null $non_year
 * @property string|null $non_program_name
 *
 * @property int|null $in_number_of_staff
 * @property string|null $in_staffs_name
 * @property string|null $in_department_office
 *
 * @property int|null $out_number_of_staff
 * @property string|null $out_staffs_name
 *
 * @property string|null $scwt_name_of_program
 * @property string|null $date_of_program
 * @property string|null $program_venue
 * @property int|null $participants_number
 * @property string|null $name_participants_involved
 * @property string|null $research_title
 * @property string|null $publication_title
 * @property string|null $publisher
 * @property string|null $consultancy_name
 * @property string|null $project_duration
 * @property string|null $other
 * @property string|null $date
 * @property string|null $justification
 * @property string|null $upload_files
 *
 * @property Agreement $agreement
 */
class Activities extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activity_type'], 'required'],
            [['type', 'number_students', 'name_students', 'semester'], 'required', 'when' => function ($model) {
                return $model->activity_type == 'Student Mobility for Credited';
            }, 'whenClient' => "function (attribute, value) {
            return $('#activity-type-dropdown').val() == 'Student Mobility for Credited';
        }"],
            [['non_type', 'non_number_students', 'non_semester', 'non_program_name'], 'required', 'when' => function ($model) {
                return $model->activity_type == 'Student Mobility Non-Credited';
            }, 'whenClient' => "function (attribute, value) {
            return $('#activity-type-dropdown').val() == 'Student Mobility Non-Credited';
        }"],
            [['in_number_of_staff', 'in_staffs_name'], 'required', 'when' => function ($model) {
                return $model->activity_type == 'Staff Mobility (Inbound)';
            }, 'whenClient' => "function (attribute, value) {
            return $('#activity-type-dropdown').val() == 'Staff Mobility (Inbound)';
        }"],
            [['out_number_of_staff', 'out_staffs_name'], 'required', 'when' => function ($model) {
                return $model->activity_type == 'Staff Mobility (Outbound)';
            }, 'whenClient' => "function (attribute, value) {
            return $('#activity-type-dropdown').val() == 'Staff Mobility (Outbound)';
        }"],
            [['scwt_name_of_program', 'date_of_program', 'program_venue', 'participants_number', 'name_participants_involved'], 'required', 'when' => function ($model) {
                return $model->activity_type == 'Seminar/Conference/Workshop/Training';
            }, 'whenClient' => "function (attribute, value) {
            return $('#activity-type-dropdown').val() == 'Seminar/Conference/Workshop/Training';
        }"],
            [['research_title'], 'required', 'when' => function ($model) {
                return $model->activity_type == 'Research';
            }, 'whenClient' => "function (attribute, value) {
            return $('#activity-type-dropdown').val() == 'Research';
        }"],
            [['publication_title', 'publisher'], 'required', 'when' => function ($model) {
                return $model->activity_type == 'Publication';
            }, 'whenClient' => "function (attribute, value) {
            return $('#activity-type-dropdown').val() == 'Publication';
        }"],
            [['consultancy_name', 'project_duration'], 'required', 'when' => function ($model) {
                return $model->activity_type == 'Consultancy';
            }, 'whenClient' => "function (attribute, value) {
            return $('#activity-type-dropdown').val() == 'Consultancy';
        }"],
            [['other', 'date'], 'required', 'when' => function ($model) {
                return $model->activity_type == 'Any other of Cooperation, Please specify';
            }, 'whenClient' => "function (attribute, value) {
            return $('#activity-type-dropdown').val() == 'Any other of Cooperation, Please specify';
        }"],
            [['justification'], 'required', 'when' => function ($model) {
                return $model->activity_type == 'No Activity, Please specify';
            }, 'whenClient' => "function (attribute, value) {
            return $('#activity-type-dropdown').val() == 'No Activity, Please specify';
        }"],


            [['col_id', 'number_students', 'non_number_students', 'in_number_of_staff', 'out_number_of_staff', 'participants_number'], 'default', 'value' => null],
            [['col_id', 'number_students', 'non_number_students', 'in_number_of_staff', 'out_number_of_staff', 'participants_number'], 'integer'],
            [['name_students', 'non_name_students', 'in_staffs_name', 'out_staffs_name', 'research_title', 'justification'], 'string'],
            [['date_of_program', 'date', 'year', 'non_year'], 'safe'],
            [['name', 'kcdio', 'mou_moa', 'activity_type', 'non_program_name', 'scwt_name_of_program', 'program_venue', 'upload_files', 'staff_email'], 'string', 'max' => 522],
            [['type', 'non_type', 'semester', 'non_semester'], 'string', 'max' => 10],
            [['in_department_office'], 'string', 'max' => 100],
            [['publication_title', 'publisher', 'consultancy_name', 'other', 'name_participants_involved'], 'string', 'max' => 255],
            [['project_duration'], 'string', 'max' => 50],
            [['col_id'], 'exist', 'skipOnError' => true, 'targetClass' => Collaboration::class, 'targetAttribute' => ['col_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {


        return [
            'id' => 'ID',
            'col_id' => 'Agreement ID',
            'name' => 'Name',
            'staff_email' => 'Staff Number',
            'kcdio' => 'Kcdio',
            'mou_moa' => 'Mou Moa',
            'activity_type' => 'Activity Type',
            'type' => 'Type',
            'number_students' => 'Number of students for Credited (Inbound & Outbound)',
            'name_students' => 'Name of Students Involve (credited)',
            'semester' => 'Which Semester',
            'year' => 'year',
            'non_type' => 'Type',
            'non_number_students' => 'Number of students for Credited (Inbound & Outbound)',
            'non_name_students' => 'Name of Students Involve (credited)',
            'non_semester' => 'Which Semester',
            'non_year' => 'year',
            'non_program_name' => 'Name of the programme (SMNC)',
            'in_number_of_staff' => 'Number of Staff ',
            'in_staffs_name' => 'Name of Staff Involve',
            'in_department_office' => 'Department Office',
            'out_number_of_staff' => 'Number of Staff ',
            'out_staffs_name' => 'Name of Staff Involve',
            'scwt_name_of_program' => 'Name of the programme',
            'date_of_program' => 'Date Of Program',
            'program_venue' => 'Venue of the programme',
            'participants_number' => 'Number of participants involve',
            'name_participants_involved' => 'Name of Participants involve',
            'research_title' => 'Title Research',
            'publication_title' => 'Title of Publication',
            'publisher' => 'Publisher (If any)',
            'consultancy_name' => 'Name of Consultancy/project',
            'project_duration' => 'Duration of Project',
            'other' => 'Please Specify',
            'date' => 'Date',
            'justification' => 'Please give justification for no activity reported',
            'upload_files' => 'Upload Files',
        ];
    }

    /**
     * Gets query for [[Agreement]].
     *
     * @return ActiveQuery
     */
    public function getAgreement()
    {
        return $this->hasOne(Collaboration::class, ['id' => 'col_id']);
    }
}
