<?php

namespace frontend\controllers;

use Carbon\Carbon;
use common\models\Activities;
use common\models\admin;
use common\models\Agreement;
use common\models\AgreementPoc;
use common\models\EmailTemplate;
use common\models\Log;
use common\models\Poc;
use common\models\search\AgreementSearch;
use Exception;
use Yii;
//use yii\base\Model;
use common\helpers\Model;
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
                            'index', 'update', 'create', 'downloader', 'log', 'add-activity', 'get-poc-info', 'get-kcdio-poc', 'delete-file'
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

        $dataProvider->query->andWhere(['status' => 100])
                            ->orWhere(['status' => 91]);
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
        $topStatuses = [2, 12, 33, 43, 51, 72, 81, 11];
        $dataProvider->query->orderBy([
            new Expression("CASE WHEN status IN (" . implode(',', $topStatuses) . ") THEN 0 ELSE 1 END"),
            'updated_at' => SORT_DESC,
        ]);
        $dataProvider->sort->defaultOrder = ['updated_at' => SORT_DESC];

        $dataProvider->query->joinWith(['agreementPoc']);
        $dataProvider->query->andWhere(['agreement_poc.pi_kcdio' => $type]);

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
        $modelsPoc = AgreementPoc::find()->where(['agreement_id' => $id])->all();
        if (!Yii::$app->request->isAjax) {
            return throw new ForbiddenHttpException('You are not authorized  to access this page!');
        }
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
            'modelsPoc' => $modelsPoc,
            'haveActivity' => $haveActivity
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
        $model = Activities::find()
            ->where(['agreement_id' => $id])
            ->orderBy(['id' => SORT_DESC])
            ->all();

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
        $modelsPoc = [new AgreementPoc()];
        $model->scenario = 'uploadCreate';

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $modelsPoc = [];

            $pocData = Yii::$app->request->post('AgreementPoc', []);
            foreach ($pocData as $index => $data) {
                $modelPoc = new AgreementPoc();
                $modelPoc->load($data, '');
                $modelsPoc[] = $modelPoc;

            }


            $status = $this->request->post('checked');
            $model->status = $status;
            $model->temp = "(" . Yii::$app->user->identity->staff_id .") ".Yii::$app->user->identity->username;


            $valid = Model::validateMultiple($modelsPoc);

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($model->save(false)) {
                        foreach ($modelsPoc as $modelPoc) {
                            $modelPoc->agreement_id = $model->id;
                            if (!($modelPoc->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                        $this->multiFileHandler($model, 'files_applicant', 'document', 'applicant_doc');
                        $this->sendEmail($model, 5);
                        $transaction->commit();
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'modelsPoc' => (empty($modelsPoc)) ? [new AgreementPoc()] : $modelsPoc
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
            return [
                'name' => $poc->name,
                'address' => $poc->address,
                'email' => $poc->email,
                'phone_number' => $poc->phone_number,
            ];
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
            $filePath = $baseUploadPath . '/' . $model->id . '/applicant/' . $fileName;

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

    function multiFileHandler($model, $attribute, $fileNamePrefix, $docAttribute)
    {
        $files = UploadedFile::getInstances($model, $attribute);
        if ($files) {
            $baseUploadPath = Yii::getAlias('@common/uploads');
            $path = $baseUploadPath. '/' . $model->id . '/applicant/';

            foreach ($files as $file) {
                $fileName = $file->baseName . '.'. $file->extension;

                $filePath = $path . $fileName;
                if (!file_exists(dirname($filePath))) {
                    mkdir(dirname($filePath), 0777, true);
                }
                $file->saveAs($filePath);
                $model->$attribute = $fileName;
            }

            $model->$docAttribute = $path;
            $model->save(false);
        }
    }
    public function actionDeleteFile($id, $filename)
    {
        $model = Agreement::findOne($id);
        $filePath = $model->applicant_doc . $filename;

        if (file_exists($filePath) && unlink($filePath)) {
            Yii::$app->session->setFlash('success', 'File deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete file.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
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

            $model->status == 91? $model->last_reminder = carbon::now()->addMonths(3)->toDateTimeString() : null;

            $this->multiFileHandler($model, 'executedAgreement', 'ExecutedAgreement', 'applicant_doc');
            $this->multiFileHandler($model, 'files_applicant', 'document', 'applicant_doc');
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
