<?php

namespace frontend\controllers;

use common\models\Agreement;
use common\models\Log;
use common\models\search\AgreementSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * AgreementController implements the CRUD actions for Agreement model.
 */
class AgreementController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Agreement models.
     *
     * @return string
     */
        public function actionIndex()
        {
            $searchModel = new AgreementSearch();

            $type = Yii::$app->user->identity->type;

            $dataProvider = $searchModel->search($this->request->queryParams);

            $dataProvider->query->andWhere(['pi_kulliyyah' => $type]); //KICT || KULLIYYAH OF INFORMATION TECHNOLOGY  == KICT

            //---updates the grid view asynchronously, in case database table updated---\\

//            if(Yii::$app->request->isAjax){
//                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//                return ['content' => $this->renderAjax('index', [
//                    'searchModel' => $searchModel,
//                    'dataProvider' => $dataProvider,
//                ])];
//            }

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

    /**
     * Checks for updates in the database and sends a JSON response indicating if updates are available.
     *
     * This action is typically used for asynchronous updates in the client-side (browser). It's triggered
     * periodically (e.g., using polling) to check if there have been any changes in the relevant database
     * table.
     *
     * commented because it might be heavy for the server
     *
     * @return \yii\web\Response A JSON response containing a single key-value pair:
     *   - `hasUpdates`: (bool) Indicates whether there are new updates in the database.
     */

//    public function actionCheckForUpdates()
//    {
//        $hasUpdates = true;
//
//        return $this->asJson(['hasUpdates' => $hasUpdates]);
//    }

    /**
     * Displays a single Agreement model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays logs model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionLog($id)
    {
        $logsDataProvider = new ActiveDataProvider([
            'query' => Log::find()->where(['agreement_id' => $id]), 'pagination' => [
                'pageSize' => 15,
            ], 'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC], // Display logs by creation time in descending order
            ],
        ]);

        return $this->renderAjax
        ('log', [
            'logsDataProvider' => $logsDataProvider,
        ]);
    }

    /**
     * Creates a new Agreement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Agreement();
        $model->scenario = 'uploadCreate';
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $status =  $this->request->post('checked');
                $model->status = $status;

                if($model->save(false)){
                    $this->fileHandler($model, 'fileUpload', 'document', 'doc_applicant');
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Agreement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {

            $model->status =  $this->request->post('checked');

            if ( $model->save(false)){
                $this->fileHandler($model, 'fileUpload', 'document', 'doc_applicant');
                return $this->redirect(['index']);
            }

        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Agreement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Agreement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Agreement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agreement::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    function fileHandler($model, $attribute, $fileNamePrefix, $docAttribute)
    {
        $file = UploadedFile::getInstance($model, $attribute);
        if ($file) {

            $baseUploadPath = Yii::getAlias('@common/uploads');
            $inputName = preg_replace('/[^a-zA-Z0-9]+/', '_', $file->name);
            $fileName = $model->id.'_'.$fileNamePrefix.'.'.$file->extension;
            $filePath = $baseUploadPath.'/'.$model->id.'/'.$fileName;

            // Create directory if not exists
            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }

            $file->saveAs($filePath);
            $model->$attribute = $fileName;
            $model->$docAttribute = $filePath;
            $model->save(false);
        }
    }
    public function actionDownloader($filePath)
    {
        Yii::$app->response->sendFile($filePath);
    }
}
