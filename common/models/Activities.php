<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activities".
 *
 * @property int $id
 * @property int|null $agreement_id
 * @property string|null $name
 * @property string|null $staff_number
 * @property string|null $kcdio
 * @property string|null $mou_moa
 * @property string|null $activity_type
 * @property string|null $type
 * @property int|null $number_students
 * @property string|null $name_students
 * @property string|null $semester
 * @property string|null $program_name
 * @property int|null $number_of_staff
 * @property string|null $staffs_name
 * @property string|null $department_office
 * @property string|null $scwt_name_of_program
 * @property string|null $date_of_program
 * @property string|null $program_venue
 * @property int|null $participants_number
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
class Activities extends \yii\db\ActiveRecord
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
            [['agreement_id', 'number_students', 'number_of_staff', 'participants_number'], 'default', 'value' => null],
            [['agreement_id', 'number_students', 'number_of_staff', 'participants_number'], 'integer'],
            [['name_students', 'staffs_name', 'research_title', 'justification'], 'string'],
            [['date_of_program', 'date'], 'safe'],
            [['name', 'kcdio', 'mou_moa', 'activity_type', 'program_name', 'scwt_name_of_program', 'program_venue', 'upload_files'], 'string', 'max' => 522],
            [['staff_number'], 'string', 'max' => 7],
            [['type', 'semester'], 'string', 'max' => 10],
            [['department_office'], 'string', 'max' => 100],
            [['publication_title', 'publisher', 'consultancy_name', 'other'], 'string', 'max' => 255],
            [['project_duration'], 'string', 'max' => 50],
            [['agreement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agreement::class, 'targetAttribute' => ['agreement_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'agreement_id' => 'Agreement ID',
            'name' => 'Name',
            'staff_number' => 'Staff Number',
            'kcdio' => 'Kcdio',
            'mou_moa' => 'Mou Moa',
            'activity_type' => 'Activity Type',
            'type' => 'Type',
            'number_students' => 'Number Students',
            'name_students' => 'Name Students',
            'semester' => 'Semester',
            'program_name' => 'Program Name',
            'number_of_staff' => 'Number Of Staff',
            'staffs_name' => 'Staffs Name',
            'department_office' => 'Department Office',
            'scwt_name_of_program' => 'Scwt Name Of Program',
            'date_of_program' => 'Date Of Program',
            'program_venue' => 'Program Venue',
            'participants_number' => 'Participants Number',
            'research_title' => 'Research Title',
            'publication_title' => 'Publication Title',
            'publisher' => 'Publisher',
            'consultancy_name' => 'Consultancy Name',
            'project_duration' => 'Project Duration',
            'other' => 'Other',
            'date' => 'Date',
            'justification' => 'Justification',
            'upload_files' => 'Upload Files',
        ];
    }

    /**
     * Gets query for [[Agreement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgreement()
    {
        return $this->hasOne(Agreement::class, ['id' => 'agreement_id']);
    }
}
