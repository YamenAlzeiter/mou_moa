<?php
namespace common\helpers;

use Yii;
use yii\base\Model as BaseModel;
use yii\helpers\ArrayHelper;

class Model extends BaseModel
{
    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = [])
    {
        $model    = new $modelClass;
        $formName = $model->formName();
        $post     = Yii::$app->request->post($formName);
        $models   = [];

        if (!empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }

    /**
     * Loads multiple models from an array.
     *
     * @param array $models
     * @param array $data
     * @param string|null $formName
     * @return bool
     */
    public static function loadMultiple($models, $data, $formName = null)
    {
        $success = false;
        foreach ($models as $i => $model) {
            if ($formName === null) {
                if ($model->load($data)) {
                    $success = true;
                }
            } else {
                if ($model->load($data, $formName . "[$i]")) {
                    $success = true;
                }
            }
        }

        return $success;
    }

    /**
     * Validates multiple models.
     *
     * @param array $models
     * @param array|null $attributeNames
     * @return bool
     */
    public static function validateMultiple($models, $attributeNames = null)
    {
        $valid = true;
        foreach ($models as $model) {
            $valid = $model->validate($attributeNames) && $valid;
        }

        return $valid;
    }
}
