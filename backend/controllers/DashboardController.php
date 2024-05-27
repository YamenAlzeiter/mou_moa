<?php

namespace backend\controllers;

use common\models\Agreement;
use common\models\AgreementPoc;
use common\models\AgreementType;
use common\models\EmailTemplate;
use common\models\Kcdio;
use common\models\Log;
use common\models\Poc;
use common\models\Reminder;
use common\models\search\AgreementSearch;
use common\models\search\PocSearch;
use common\models\Status;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
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
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['actions' =>
                        [
                            'index', 'delete-reminder', 'update-reminder','update-email-template',
                            'update-kcdio', 'status-update', 'poc-update', 'create-kcdio', 'create-reminder',
                            'view-email-template', 'create-poc','type-update','create-type',
                            'delete-type', 'delete-poc'
                        ],
                        'allow' => !Yii::$app->user->isGuest,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => ['logout' => ['post'],
                ],
            ],
        ];
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
        $pocSearchModel = new PocSearch();
        $pocDataProvider = $pocSearchModel->search($this->request->queryParams);

        $agreTypeDataProvider = new ActiveDataProvider([
            'query' => AgreementType::find(),

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
            'pocDataProvider' => $pocDataProvider,
            'agreTypeDataProvider' => $agreTypeDataProvider,
            'pocSearchModel' => $pocSearchModel,
        ]);
    }

    public function actionViewEmailTemplate($id)
    {
        $model = EmailTemplate::findOne($id);
        return $this->renderAjax('viewEmailTemplate', [
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
    public function actionCreateType()
    {
        $model = new AgreementType();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('typeUpdate', [
            'model' => $model,
        ]);
    }
    public function actionCreatePoc()
    {
        $type = Yii::$app->user->identity->type;

        if ($type == "admin" || $type == 'OIL' || $type == 'RMC') {
            $model = new Poc();

            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['index']);
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->renderAjax('createPoc', ['model' => $model,]);
        } else throw new ForbiddenHttpException("you can't access this page");

    }
    public function actionPocUpdate($id){
        $model = Poc::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('createPoc', [
            'model' => $model,
        ]);
    }
    public function actionTypeUpdate($id){
        $model = AgreementType::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('typeUpdate', [
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

    public function actionDeleteReminder($id)
    {
        Reminder::findOne($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionDeleteType($id)
    {
        AgreementType::findOne($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionDeletePoc($id)
    {
        Poc::findOne($id)->delete();

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
