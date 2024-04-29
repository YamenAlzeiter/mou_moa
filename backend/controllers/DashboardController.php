<?php

namespace backend\controllers;

use common\models\Agreement;
use common\models\EmailTemplate;
use common\models\Kcdio;
use common\models\Log;
use common\models\Reminder;
use common\models\search\AgreementSearch;
use common\models\Status;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DashboardController implements the CRUD actions for Agreement model.
 */
class DashboardController extends Controller
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
//        $this->layout = 'blank';
        $searchModel = new AgreementSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->sort->defaultOrder = ['updated_at' => SORT_DESC];

        $dataProvider = new ActiveDataProvider([
            'query' => Agreement::find(),
            'pagination' => [
                'pageSize' => 10
            ],
        ]);

        $statusDataProvider = new ActiveDataProvider([
            'query' => Status::find(),
            'pagination' => [
                'pageSize' => 50
            ],

        ]);
        $emailDataProvider = new ActiveDataProvider([
            'query' => EmailTemplate::find(),
            'pagination' => [
                'pageSize' => 50
            ],

        ]);

        $reminderDataProvider = new ActiveDataProvider([
            'query' => Reminder::find(),

            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [ // Add the 'sort' configuration
                'defaultOrder' => ['id' => SORT_ASC] // Order by 'id' ascending
            ]
        ]);

        $kcdioDataProvider = new ActiveDataProvider([
            'query' => Kcdio::find(),

            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [ // Add the 'sort' configuration
                'defaultOrder' => ['id' => SORT_ASC] // Order by 'id' ascending
            ]
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'statusDataProvider' => $statusDataProvider,
            'emailDataProvider' => $emailDataProvider,
            'reminderDataProvider' => $reminderDataProvider,
            'kcdioDataProvider' => $kcdioDataProvider,
        ]);
    }

    /**
     * Displays a single Agreement model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!Yii::$app->request->isAjax) {
            return throw new ForbiddenHttpException('You are not authorized  to access this page!');
        }
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewEmailTemplate($id)
    {
        $model = EmailTemplate::findOne($id);
        return $this->renderAjax('viewEmailTemplate', [
            'model' => $model,
        ]);
    }
    public function actionLog($id)
    {
        if (!Yii::$app->request->isAjax) {
            return throw new ForbiddenHttpException('You are not authorized  to access this page!');
        }
        $logsDataProvider = new ActiveDataProvider([
            'query' => Log::find()->where(['agreement_id' => $id]), 'pagination' => [
                'pageSize' => 100,
            ], 'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC], // Display logs by creation time in descending order
            ],
        ]);

        return $this->renderAjax('log', [
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

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionCreateReminder()
    {
        $model = new Reminder();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('updateReminder', [
            'model' => $model,
        ]);
    }
    public function actionCreateKcdio()
    {
        $model = new Kcdio();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('updateKcdio', [
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

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionStatusUpdate($id){
        $model = Status::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('statusUpdate', [
            'model' => $model,
        ]);
    }
    public function actionUpdateKcdio($id){
        $model = Kcdio::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('updateKcdio', [
            'model' => $model,
        ]);
    }
    public function actionUpdateEmailTemplate($id)
    {
        $model = EmailTemplate::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('updateEmailTemplate', [
            'model' => $model,
        ]);
    }

    public function actionUpdateReminder($id)
    {
        $model = Reminder::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('updateReminder', [
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


    public function actionDeleteReminder($id)
    {
        Reminder::findOne($id)->delete();

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
}