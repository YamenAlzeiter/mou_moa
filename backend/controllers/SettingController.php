<?php

namespace backend\controllers;

use common\models\Agreement;
use common\models\AgreementPoc;
use common\models\AgreementType;
use common\models\EmailTemplate;
use common\models\Kcdio;
use common\models\Log;
use common\models\McomDate;
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
 * SettingController implements the CRUD actions for Agreement model.
 */
class SettingController extends Controller
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
                    [
                        'actions' => [
                            'index',

                            // kcdio
                            'kcdio', 'create-kcdio', 'update-kcdio',

                            // reminders
                            'create-reminder', 'update-reminder', 'delete-reminder',

                            // poc
                            'poc', 'create-poc', 'delete-poc', 'poc-update',

                            // agreement type
                            'create-type', 'type-update', 'delete-type',

                            // mcom dates
                            'create-mcom', 'mcom-update', 'delete-mcom',

                            // email template
                            'email-template', 'view-email-template', 'update-email-template',

                            // status
                            'status', 'status-update',

                            'others',
                        ],


                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {

                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->is_admin;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
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
    public function actionStatus(){
        $statusDataProvider = new ActiveDataProvider([
            'query' => Status::find(),
            'pagination' => [
                'pageSize' => 50
            ],

        ]);

        return $this->render('vstatus', [
            'statusDataProvider' => $statusDataProvider,
        ]);
    }
    public function actionEmailTemplate()
    {
        $emailDataProvider = new ActiveDataProvider([
            'query' => EmailTemplate::find(),
            'pagination' => [
                'pageSize' => 50
            ],

        ]);
        return $this->render('vemailTemplate', [
            'emailDataProvider' => $emailDataProvider,
        ]);
    }

    public function actionOthers()  {

        $reminderDataProvider = new ActiveDataProvider([
            'query' => Reminder::find(),

            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [ // Add the 'sort' configuration
                'defaultOrder' => ['id' => SORT_ASC] // Order by 'id' ascending
            ]
        ]);

        $agreTypeDataProvider = new ActiveDataProvider([
            'query' => AgreementType::find(),

            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [ // Add the 'sort' configuration
                'defaultOrder' => ['id' => SORT_ASC] // Order by 'id' ascending
            ]
        ]);

        $mcomDataProvider = new ActiveDataProvider([
            'query' => McomDate::find(),

            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [ // Add the 'sort' configuration
                'defaultOrder' => ['id' => SORT_ASC] // Order by 'id' ascending
            ]
        ]);
        return $this->render('vother', [
            'agreTypeDataProvider' => $agreTypeDataProvider,
            'reminderDataProvider' => $reminderDataProvider,
            'mcomDataProvider' => $mcomDataProvider,
        ]);

    }
    public function actionPoc(){
        $pocSearchModel = new PocSearch();
        $pocDataProvider = $pocSearchModel->search($this->request->queryParams);

        return $this->render('vpoc', [
            'pocDataProvider' => $pocDataProvider,
            'pocSearchModel' => $pocSearchModel,
        ]);

    }
    public function actionKcdio(){
        $kcdioDataProvider = new ActiveDataProvider([
            'query' => Kcdio::find(),

            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [ // Add the 'sort' configuration
                'defaultOrder' => ['id' => SORT_ASC] // Order by 'id' ascending
            ]
        ]);
        return $this->render('vkcdio', [
            'kcdioDataProvider' => $kcdioDataProvider,
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
                return $this->redirect(['others']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('updateReminder', [
            'model' => $model,
        ]);
    }
    public function actionCreateMcom()
    {
        $model = new McomDate();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['others']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('updateMcom', [
            'model' => $model,
        ]);
    }
    public function actionCreateKcdio()
    {
        $model = new Kcdio();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['others']);
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
                return $this->redirect(['others']);
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

            $model = new Poc();

            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    return $this->redirect(['vpoc']);
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->renderAjax('createPoc', ['model' => $model,]);

    }
    public function actionPocUpdate($id){
        $model = Poc::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['vpoc']);
        }

        return $this->renderAjax('createPoc', [
            'model' => $model,
        ]);
    }
    public function actionTypeUpdate($id){
        $model = AgreementType::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['others']);
        }

        return $this->renderAjax('typeUpdate', [
            'model' => $model,
        ]);
    }
    public function actionStatusUpdate($id){
        $model = Status::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['status']);
        }

        return $this->renderAjax('statusUpdate', [
            'model' => $model,
        ]);
    }
    public function actionUpdateKcdio($id){
        $model = Kcdio::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['vkcdio']);
        }

        return $this->renderAjax('updateKcdio', [
            'model' => $model,
        ]);
    }
    public function actionUpdateEmailTemplate($id)
    {
        $model = EmailTemplate::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['viewEmailTemplate']);
        }

        return $this->renderAjax('updateEmailTemplate', [
            'model' => $model,
        ]);
    }

    public function actionUpdateReminder($id)
    {
        $model = Reminder::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['others']);
        }

        return $this->renderAjax('updateReminder', [
            'model' => $model,
        ]);
    }
    public function actionMcomUpdate($id)
    {
        $model = McomDate::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['others']);
        }

        return $this->renderAjax('updateMcom', [
            'model' => $model,
        ]);
    }
    public function actionDeleteReminder($id)
    {
        Reminder::findOne($id)->delete();

        return $this->redirect(['others']);
    }
    public function actionDeleteMcom($id)
    {
        McomDate::findOne($id)->delete();

        return $this->redirect(['others']);
    }
    public function actionDeleteType($id)
    {
        AgreementType::findOne($id)->delete();

        return $this->redirect(['others']);
    }
    public function actionDeletePoc($id)
    {
        Poc::findOne($id)->delete();

        return $this->redirect(['vpoc']);
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
