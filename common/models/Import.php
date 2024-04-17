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
            [['importedFile'], 'file', 'extensions' => 'xlsx'],
            [['type', 'import_from', 'directory'], 'string', 'max' => 255],
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
