<?php

namespace frontend\controllers;

use Carbon\Carbon;
use common\helpers\Variables;
use common\models\Activities;
use common\models\Agreement;
use common\models\AgreementPoc;
use common\models\Collaboration;
use common\models\EmailTemplate;
use common\models\Log;
use common\models\McomDate;
use common\models\search\AgreementSearch;
use common\models\User;
use Exception;
use Yii;
use common\helpers\Model;
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
                            'index', 'update', 'create', 'downloader', 'log', 'add-activity', 'get-poc-info', 'get-kcdio-poc', 'delete-file','delete-activity', 'check-organization'
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
        $model = $this->findModel($id);
        $modelCol = Collaboration::findOne(['id' => $model->col_id]);
        $haveActivity = Activities::findOne(['col_id' => $model->col_id]) !== null;
        $modelsPoc = $model->getAgreementPoc()->all();

        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
            'modelsPoc' => $modelsPoc,
            'modelCol' => $modelCol,
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
        $model->col_id = $agreement->col_id;

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
            ->where(['col_id' => $id])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->renderAjax('viewActivities', [
            'model' => $model,
        ]);
    }
    public function actionDeleteActivity()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $activity = Activities::findOne($id);

        if ($activity && $activity->delete()) {
            return ['success' => true];
        } else {
            return ['success' => false];
        }
    }

    /**
     * Creates a new Agreement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Agreement();
        $colModel = new Collaboration();
        $modelsPoc = [new AgreementPoc()];
        $model->scenario = 'uploadCreate';

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $colModel->load($this->request->post());
            $modelsPoc = [];

            $pocData = Yii::$app->request->post('AgreementPoc', []);
            foreach ($pocData as $index => $data) {
                $modelPoc = new AgreementPoc();
                $modelPoc->load($data, '');
                $modelsPoc[] = $modelPoc;
            }

            $status = $this->request->post('checked');
            $model->status = $status;
            $model->temp = "(" . Yii::$app->user->identity->email . ") " . Yii::$app->user->identity->username;
            if ($model->agreement_type == 'other') {
                $model->agreement_type = $model->agreement_type_other;
            }

            // Check if the collaboration already exists
            $existingColModel = Collaboration::findOne(['col_organization' => $colModel->col_organization]);
            if ($existingColModel) {
                // Use the existing collaboration
                $model->col_id = $existingColModel->id;
            } else {
                // Create a new collaboration
                if ($colModel->save()) {
                    $model->col_id = $colModel->id;
                } else {
                    // Handle the error if the collaboration fails to save
                    Yii::$app->session->setFlash('error', 'Failed to save collaboration');
                    return $this->redirect(['create']);
                }
            }

            // Validate all models
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
                        $this->multiFileHandler($model, 'files_applicant', 'applicant_doc');
                        $this->sendEmail($model, Variables::email_init);
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
            'colModel' => $colModel,
            'modelsPoc' => (empty($modelsPoc)) ? [new AgreementPoc()] : $modelsPoc
        ]);
    }


    public function actionCheckOrganization()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $organization = Yii::$app->request->post('col_organization');

        // Using ILIKE for case-insensitive search
        $collaboration = Collaboration::find()
            ->where(['ILIKE', 'col_organization', $organization])
            ->one();

        if ($collaboration) {
            return [
                'exists' => true,
                'data' => [
                    'col_name' => $collaboration->col_name,
                    'col_phone_number' => $collaboration->col_phone_number,
                    'col_address' => $collaboration->col_address,
                    'col_email' => $collaboration->col_email,
                    'col_collaborators_name' => $collaboration->col_collaborators_name,
                    'col_wire_up' => $collaboration->col_wire_up,
                    'country' => $collaboration->country,
                ]
            ];
        } else {
            return ['exists' => false];
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

    function multiFileHandler($model, $attribute, $docAttribute)
    {



        $files = UploadedFile::getInstances($model, $attribute);
        if ($files) {
            $baseUploadPath = Yii::getAlias('@common/uploads');
            $path = $baseUploadPath. '/' . $model->id . '/applicant/';

            foreach ($files as $file) {
                $fileName = $file->baseName . '_'. date('Ymdhis') . '.' . $file->extension;

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
        // Find the email template
        $mail = EmailTemplate::findOne($template);

        // Ensure that the template exists
        if ($mail === null) {
            throw new \Exception("Email template not found.");
        }

        // Get user IDs by role
        $osc = Yii::$app->authManager->getUserIdsByRole($model->transfer_to);

        // Get the primary POC
        $modelPoc = $model->getAgreementPoc()->where(['pi_is_primary' => true])->one();

        // Check if there's a primary POC
        if ($modelPoc === null) {
            throw new \Exception("Primary POC not found.");
        }

        // Build the email body by replacing placeholders
        $body = $mail->body;
        $body = str_replace('{user}', $modelPoc->pi_name, $body);
        $body = str_replace('{reason}', $model->reason, $body);
        $body = str_replace('{id}', $model->id, $body);
        $body = str_replace('{MCOM_date}', $model->mcom_date, $body);

        // Compose the email
        $mailer = Yii::$app->mailer->compose([
            'html' => '@backend/views/email/emailTemplate.php'
        ], [
            'subject' => $mail->subject,
            'recipientName' => Yii::$app->user->identity->username,
            'body' => $body
        ])
            ->setFrom(['noReplay@iium.edy.my' => 'IIUM Memorandum Program'])
            ->setTo($modelPoc->pi_email)
            ->setSubject($mail->subject);

        // Add CC emails from $osc (user IDs)
        $ccRecipients = [];
        foreach ($osc as $admin) {
            $user = User::findOne($admin);
            if ($user != null) {
                $ccRecipients[] = $user->email;
            }
        }

        // Set CC recipients if there are any
        if (!empty($ccRecipients)) {
            $mailer->setCc($ccRecipients);
        }

        // Send the email
        return $mailer->send();
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
        $colModel = Collaboration::findOne($model->col_id);

        $nextTwoWeeks = Carbon::now()->addWeeks(2)->toDateTimeString();
        $nextTwoMonths = Carbon::now()->addMonths(2)->toDateTimeString();

        $mcomDates = McomDate::find()
            ->where(['<', 'counter', 10])
            ->andWhere(['>', 'date_from', $nextTwoWeeks])
            ->andWhere(['<', 'date_from', $nextTwoMonths])
            ->limit(3)
            ->all();


        $modelsPoc = $model->getAgreementPoc()->all();


        $oldStatus = $model->status;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $modelsPocData = Yii::$app->request->post('AgreementPoc', []);
            $colModel->load($this->request->post());
            $modelsPoc = [];

            foreach ($modelsPocData as $data) {
                $poc = isset($data['id']) ? AgreementPoc::findOne($data['id']) : new AgreementPoc();
                $poc->load($data, '');
                $modelsPoc[] = $poc;
            }

            foreach ($modelsPoc as $modelPoc) {
                $modelPoc->agreement_id = $model->id;
                $modelPoc->save();
            }


            $piDeleteIds = Yii::$app->request->post('Agreement')['pi_delete_ids'] ?? '';

            if (!empty($piDeleteIds)) {
                $piDeleteIds = explode(',', $piDeleteIds);
                if (!empty($piDeleteIds)) {
                    AgreementPoc::deleteAll(['id' => $piDeleteIds]);
                }
            }

            $model->status = $oldStatus != Variables::agreement_reminder_sent ? $this->request->post('checked') : $model->status;
            if ($model->status == Variables::agreement_executed) {
                $model->last_reminder = Carbon::now()->addMonths(3)->toDateTimeString();
            }

            $this->multiFileHandler($model, 'executedAgreement', 'applicant_doc');
            $this->multiFileHandler($model, 'files_applicant', 'applicant_doc');

            $model->temp = "(" . Yii::$app->user->identity->email . ") " . Yii::$app->user->identity->username;

            if($model->status == Variables::agreement_extended){
                $newAgreement = new Agreement();
                $newAgreement->attributes = $model->attributes;
                $newAgreement->status = Variables::agreement_executed;
                $newAgreement->col_id = $model->col_id;

                if ($newAgreement->save()) {
                    $modelsPoc = $model->getAgreementPoc()->all();

                    foreach ($modelsPoc as $modelPoc) {
                        $newPoc = new AgreementPoc();
                        $newPoc->attributes = $modelPoc->attributes;
                        $newPoc->agreement_id = $newAgreement->id;
                        $newPoc->save();
                    }

                    $oldFolder = "C:/xampp/htdocs/mou_moa/common/uploads/{$model->id}";
                    $newFolder = "C:/xampp/htdocs/mou_moa/common/uploads/{$newAgreement->id}";

                    $this->copyDirectory($oldFolder, $newFolder);

                    $newAgreement->applicant_doc = $newFolder.'/applicant/';
                    $newAgreement->dp_doc = $newFolder.'/higher/';
                    $newAgreement->save();
                }

            }

              if ($model->save()) {
                  $colModel->save();
                  if ($model->status == Variables::agreement_resubmitted) {
                      $this->sendEmail($model, Variables::email_init);
                  }elseif ($oldStatus == Variables::agreement_MCOM_KIV){
                      $this->sendEmail($model, Variables::email_agr_mcom_resubmitted);
                  }elseif($model->status == Variables::agreement_MCOM_date_set){
                      $this->sendEmail($model, Variables::email_agr_pick_mcom_date);
                  }
                  return $this->redirect(['index']);
              }

        }

        return $this->renderAjax('update', [
            'model' => $model,
            'colModel' => $colModel,
            'modelsPoc' => $modelsPoc,
            'mcomDates' => $mcomDates,
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
    /**
     * Recursively copy files and directories from one location to another.
     *
     * @param string $src Source directory
     * @param string $dst Destination directory
     */
    private function copyDirectory($src, $dst)
    {
        if (is_dir($src)) {
            @mkdir($dst, 0777, true);

            $files = scandir($src);

            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $srcPath = "$src/$file";
                    $dstPath = "$dst/$file";

                    if (is_dir($srcPath)) {
                        $this->copyDirectory($srcPath, $dstPath);
                    } else {
                        copy($srcPath, $dstPath);
                    }
                }
            }
        } elseif (file_exists($src)) {
            copy($src, $dst);
        }
    }
}
