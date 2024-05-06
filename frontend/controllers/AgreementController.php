<?php

namespace frontend\controllers;

use common\models\Activities;
use common\models\admin;
use common\models\Agreement;
use common\models\EmailTemplate;
use common\models\Log;
use common\models\Poc;
use common\models\search\AgreementSearch;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
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
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index', 'update', 'create', 'downloader', 'log', 'add-activity', 'get-poc-info', 'get-kcdio-poc'
                        ],
                        'allow' => !Yii::$app->user->isGuest,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['public-index', 'view-activities', 'view'],
                        'allow' => true,
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
     * @return Response
     */
    public function actionPublicIndex()
    {
        $searchModel = new AgreementSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);


        $dataProvider->sort->defaultOrder = ['id' => SORT_DESC];

        $dataProvider->query->andWhere(['status' => 100])->orWhere(['status' => 91]);
        $dataProvider->pagination = [
            'pageSize' => 11,
        ];
        if (Yii::$app->user->isGuest) {
            return $this->render('publicIndex', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->redirect('agreement/index');
        }
    }

    /**
     * Lists all Agreement models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AgreementSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $type = Yii::$app->user->identity->type;
        $topStatuses = [2, 12, 33, 43, 81, 11];
        $dataProvider->query->orderBy([
            new Expression("CASE WHEN status IN (" . implode(',', $topStatuses) . ") THEN 0 ELSE 1 END"),
            'updated_at' => SORT_DESC,
        ]);
        $dataProvider->sort->defaultOrder = ['updated_at' => SORT_DESC];

        $dataProvider->query->andWhere(
            ['or',
                ['pi_kulliyyah' => $type],
                ['pi_kulliyyah_extra' => $type],
                ['pi_kulliyyah_extra2' => $type]
            ]
        );

        $dataProvider->pagination = [
            'pageSize' => 11,
        ];
        if (!Yii::$app->user->isGuest) {
            return $this->render('index', [
                'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            ]);
        } else {
            return throw new ForbiddenHttpException("You need to login in order to have access to this page");
        }
    }


    /**
     * Displays a single Agreement model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $haveActivity = Activities::findOne(['agreement_id' => $id]) !== null;

        if (!Yii::$app->request->isAjax) {
            return throw new ForbiddenHttpException('You are not authorized  to access this page!');
        }
        return $this->renderAjax('view', [
            'model' => $this->findModel($id), 'haveActivity' => $haveActivity
        ]);


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
                'pageSize' => 99,
            ], 'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC], // Display logs by creation time in descending order
            ],
        ]);

        return $this->renderAjax('log', [
            'logsDataProvider' => $logsDataProvider,
        ]);
    }

    public function actionAddActivity($id)
    {
        $agreement = $this->findModel($id);
        $model = new Activities();
        $model->agreement_id = $id;

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                return $this->redirect('index');
            }
        }

        return $this->renderAjax('addActivity', [
            'model' => $model,
            'agreement' => $agreement,
        ]);
    }

    public function actionViewActivities($id)
    {
        $model = Activities::find()->where(['agreement_id' => $id])->all();


        return $this->renderAjax('viewActivities', [
            'model' => $model,
        ]);


    }

    /**
     * Creates a new Agreement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Agreement();
        $model->scenario = 'uploadCreate';
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $status = $this->request->post('checked');
                $model->status = $status;
                $model->temp = "(" . Yii::$app->user->identity->staff_id .") ".Yii::$app->user->identity->username;
                if ($model->save(false)) {
                    $this->fileHandler($model, 'fileUpload', 'document', 'doc_applicant');
                    $this->sendEmail($model, 5);
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
    public function actionGetKcdioPoc($id)
    {
        $poc = Poc::find()->where(['kcdio' => $id])->all();


        if ($poc) {
            $options = "<option>Select POC</option>";
            foreach ($poc as $apoc) {
                $options .= "<option value='" . $apoc->id . "'>" . $apoc->name . "</option>";
            }
        } else $options = "<option>Person In charge Not found</option>";

        echo $options;
    }

    public function actionGetPocInfo($id)
    {
        $poc = Poc::findOne($id);

        if ($poc) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['name' => $poc->name, 'kulliyyah' => $poc->kcdio, // Adjust this according to your attribute name
                'email' => $poc->email, 'phone_number' => $poc->phone_number,];
        } else {
            // Handle the case where no POC is found with the given ID
            return ['error' => 'POC not found'];
        }
    }

    function fileHandler($model, $attribute, $fileNamePrefix, $docAttribute)
    {
        $file = UploadedFile::getInstance($model, $attribute);
        if ($file) {

            $baseUploadPath = Yii::getAlias('@common/uploads');
            $inputName = preg_replace('/[^a-zA-Z0-9]+/', '_', $file->name);
            $fileName = $model->id . '_' . $fileNamePrefix . '.' . $file->extension;
            $filePath = $baseUploadPath . '/' . $model->id . '/' . $fileName;

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

    private function sendEmail($model, $template)
    {
        $mail = EmailTemplate::findOne($template);

        $osc = Admin::findOne(['type' => $model->transfer_to]);

        if ($osc != null) {
            $body = $mail->body;
            $mailer = Yii::$app->mailer->compose([
                'html' => '@backend/views/email/emailTemplate.php'
            ], [
                'subject' => $mail->subject,
                'recipientName' => $osc->username,
                'body' => $body
            ])->setFrom(['noReplay@iium.edy.my' => 'IIUM'])->setTo($osc->email)->setSubject($mail->subject);
            $mailer->send();
        }

    }

    /**
     * Updates an existing Agreement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldStatus = $model->status;
        if ($this->request->isPost && $model->load($this->request->post())) {


            $model->status = $oldStatus != 110 ? $this->request->post('checked') : $model->status;
            $this->fileHandler($model, 'executedAgreement', 'ExecutedAgreement', 'doc_executed');
            $this->fileHandler($model, 'fileUpload', 'document', 'doc_applicant');
            $model->temp = "(" . Yii::$app->user->identity->staff_id .") ".Yii::$app->user->identity->username;
            if ($model->save()) {

                if ($model->status == 15) $this->sendEmail($model, 6);
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
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDownloader($filePath)
    {
        Yii::$app->response->sendFile($filePath);
    }
}
