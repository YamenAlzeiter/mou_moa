<?php

namespace backend\controllers;

use common\models\Agreement;

use common\models\AgreementType;
use common\models\EmailTemplate;
use common\models\Kcdio;
use common\models\Log;
use common\models\McomDate;

use common\models\Reminder;
use common\models\search\AgreementSearch;

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

                            // reminders
                            'create-reminder', 'update-reminder', 'delete-reminder',

                            // mcom dates
                            'create-mcom', 'mcom-update', 'delete-mcom',

                            // email template
                            'email-template', 'view-email-template', 'update-email-template',

                            // status
                            'status', 'status-update',

                            'others',
                        ],


                        'allow' => true,
                        'roles' => ['OSIC'],
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
    public function actionStatus(){
        $statusDataProvider = new ActiveDataProvider([
            'query' => Status::find(),
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC, // You can use SORT_DESC for descending order
                ]
            ],
        ]);

        return $this->render('vstatus', [
            'statusDataProvider' => $statusDataProvider,
        ]);
    }
    public function actionEmailTemplate()
    {
        $emailDataProvider = new ActiveDataProvider([
            'query' => EmailTemplate::find()->orderBy(['id' => SORT_ASC]), // You can use SORT_DESC for descending order
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC, // You can use SORT_DESC for descending order
                ]
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

            'reminderDataProvider' => $reminderDataProvider,
            'mcomDataProvider' => $mcomDataProvider,
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


    public function actionStatusUpdate($id){
        $model = Status::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['status']);
        }

        return $this->renderAjax('statusUpdate', [
            'model' => $model,
        ]);
    }

    public function actionUpdateEmailTemplate($id)
    {
        $model = EmailTemplate::findOne($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['email-template']);
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
