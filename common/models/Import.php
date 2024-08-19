<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "import".
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $import_from
 * @property string|null $directory
 */
class Import extends \yii\db\ActiveRecord
{
    public $importedFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'import';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'type'], 'required'],
            [['importedFile'], 'file', 'extensions' => 'xlsx'],
            [['directory'], 'string'],
            [['type'], 'string', 'max' => 50],
            [['import_from'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'import_from' => 'Import From',
            'directory' => 'Directory',
        ];
    }
}
