<?php

namespace backend\controllers;

use Carbon\Carbon;
use common\helpers\Model;
use common\helpers\Variables;
use common\models\Activities;
use common\models\admin;
use common\models\Agreement;
use common\models\AgreementPoc;
use common\models\EmailTemplate;
use common\models\Import;
use common\models\Kcdio;
use common\models\Log;
use common\models\McomDate;
use common\models\Poc;
use common\models\search\AgreementSearch;
use Exception;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
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
                    ['actions' =>
                        [
                            'index', 'update', 'view', 'downloader', 'log',
                            'get-organization', 'import-excel', 'import-excel-activity', 'view-activities',
                            'import', 'mcom', 'update-poc', 'create-poc', 'create',
                            'get-poc-info', 'get-kcdio-poc', 'delete-file', 'generate-pdf','bulk-delete', 'dashboard'
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
        $searchModel = new AgreementSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $type = Yii::$app->user->identity->type;

        $dataProvider->sort->defaultOrder = ['updated_at' => SORT_DESC];

        // Define the statuses to be always on top
        $type != "OLA" ? $topStatuses = [10, 15, 81] : $topStatuses = [1, 21, 31, 41, 61, 121];

        if ($type != 'OLA' && $type != 'admin') {
            $dataProvider->query->andWhere(['transfer_to' => $type]);
        }

        $dataProvider->query->orderBy([new Expression("CASE WHEN status IN (" . implode(',', $topStatuses) . ") THEN 0 ELSE 1 END"), 'updated_at' => SORT_DESC,]);

        $dataProvider->pagination = ['pageSize' => 11,];

        if (!Yii::$app->user->isGuest) {
            return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider,]);
        } else {
            throw new ForbiddenHttpException("You need to login in order to have access to this page");
        }
    }


    /**
     * Displays a single Agreement model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionView($id)
    {
        $haveActivity = Activities::findOne(['agreement_id' => $id]) !== null;
        $modelsPoc = AgreementPoc::find()->where(['agreement_id' => $id])->all();
        if (!Yii::$app->user->isGuest) {

            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
                'haveActivity' => $haveActivity,
                'modelsPoc' => $modelsPoc,]);
        } else {
            return throw new ForbiddenHttpException("You need to login in order to have access to this page");
        }

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
     * Creates a new Agreement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */


    public function actionCreate()
    {
        if (Yii::$app->user->identity->type == 'OLA') {
            $model = new Agreement();
            $modelsPoc = [new AgreementPoc()];
            $model->scenario = 'createSpecial';
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
                $model->status == 91 ? $model->last_reminder = carbon::now()->addMonths(3)->toDateTimeString() : null;
                $model->temp = "(" . Yii::$app->user->identity->type . ") " . "(" . Yii::$app->user->identity->staff_ID . ") " . Yii::$app->user->identity->username;

                $valid = Model::validateMultiple($modelsPoc);

                if ($valid) {
                    $transaction = Yii::$app->db->beginTransaction();
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
        } else throw new forbiddenHttpException("You are not authorized to be in this page");
    }

    function multiFileHandler($model, $attribute, $fileNamePrefix, $docAttribute)
    {
        $files = UploadedFile::getInstances($model, $attribute);
        if ($files) {
            $baseUploadPath = Yii::getAlias('@common/uploads');
            $path = $baseUploadPath . '/' . $model->id . '/higher/';

            foreach ($files as $file) {
                $fileName = $file->baseName . '.' . $file->extension;

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

    public function actionCreatePoc()
    {
        $type = Yii::$app->user->identity->type;

        if ($type == "IO" || $type == 'OIL' || $type == 'RMC') {
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

    public function actionViewActivities($id)
    {


        $model = Activities::find()->where(['agreement_id' => $id])->all();
        if (!Yii::$app->user->isGuest) {

            return $this->renderAjax('viewActivities', ['model' => $model,]);
        } else {
            return throw new ForbiddenHttpException("You need to login in order to have access to this page");
        }

    }

    /**
     * Updates an existing Agreement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  int  $id  ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $this->multiFileHandler($model, 'files_applicant', 'draft', 'dp_doc');
            $model->temp = "(" . Yii::$app->user->identity->type . ") " . "(" . Yii::$app->user->identity->staff_ID . ") " . Yii::$app->user->identity->username;

            // Attempt to save the model
            if ($model->save()) {

                    $this->sendEmail($model, ($model->status != 2));

                return $this->redirect(['index']);
            } else {
                // If model save fails, log the error and show a user-friendly message
                Yii::error("Error saving model: " . print_r($model->errors, true), __METHOD__);
                Yii::$app->session->setFlash('error', 'There was an error saving your data. Please check the input and try again.');
            }
        } elseif ($this->request->isPost) {
            // If model load fails, log the error and show a user-friendly message
            Yii::error("Error loading model: " . print_r($model->errors, true), __METHOD__);
            Yii::$app->session->setFlash('error', 'There was an error processing your request. Please try again.');
        }

        return $this->renderAjax('update', ['model' => $model]);
    }


    private function sendEmail($model, $needCC)
    {

        $mailMap = [
            Variables::agreement_init => [
                'template' => Variables::email_agr_complete_osc,
                'cc' => 'OLA'
            ],
            Variables::agreement_approved_osc => [
                'template' => Variables::email_agr_complete_osc,
                'cc' => 'OLA'
            ],
            Variables::agreement_not_complete_osc => [
                'template' => Variables::email_agr_not_complete,
                'cc' => ''
            ],
            Variables::agreement_approved_ola => [
                'template' => Variables::email_agr_review_complete_ola,
                'cc' => 'OSC'
            ],
            Variables::agreement_not_complete_ola => [
                'template' => Variables::email_agr_review_not_complete_ola,
                'cc' => 'OSC'
            ],
            Variables::agreement_MCOM_date_changed => [
                'template' => Variables::email_agr_mcom_date_change,
                'cc' => 'OSC'
            ],
            Variables::agreement_MCOM_approved => [
                'template' => Variables::email_agr_mcom_approve,
                'cc' => 'OSC'
            ],
            Variables::agreement_MCOM_reject => [
                'template' => Variables::email_agr_mcom_reject,
                'cc' => 'OSC'
            ],
            Variables::agreement_MCOM_KIV => [
                'template' => Variables::email_agr_mcom_kiv,
                'cc' => 'OSC'
            ],
            Variables::agreement_UMC_approve => [
                'template' => Variables::email_umc_approve,
                'cc' => 'OSC'
            ],
            Variables::agreement_UMC_KIV => [
                'template' => Variables::email_umc_kiv,
                'cc' => 'OSC'
            ],
            Variables::agreement_UMC_reject => [
                'template' => Variables::email_umc_reject,
                'cc' => 'OSC'
            ],
            Variables::agreement_draft_uploaded_ola => [
                'template' => Variables::email_draft_upload_ola,
                'cc' => 'OLA'
            ],
            Variables::agreement_draft_approved_ola => [
                'template' => Variables::email_draft_approve,
                'cc' => 'OLA'
            ],
            Variables::agreement_draft_rejected_ola => [
                'template' => Variables::email_draft_not_complete,
                'cc' => 'OLA'
            ],
            Variables::agreement_draft_approve_final_draft => [
                'template' => Variables::email_draft_approve,
                'cc' => 'OLA'
            ],
            Variables::agreement_executed => [
                'template' => Variables::email_agr_executed,
                'cc' => 'OSC'
            ],
            Variables::agreement_rejected => [
                'template' => Variables::email_agr_reject,
                'cc' => 'OSC'
            ],
        ];


        // Find the email template based on the status
        $mail = EmailTemplate::findOne($mailMap[$model->status]['template']);

        // Get non-primary POCs
        $pocs = $model->getAgreementPoc()->where(['or', ['pi_is_primary' => false], ['pi_is_primary' => null]])->all();

        // Get the primary POC
        $modelPoc = $model->getAgreementPoc()->where(['pi_is_primary' => true])->one();

        // Fallback if no primary POC found
        if (!$modelPoc) {
            throw new \Exception('Primary POC not found.');
        }

        $body = $mail->body;

        $body = str_replace('{user}', $modelPoc->pi_name, $body);
        $body = str_replace('{reason}', $model->reason, $body);
        $body = str_replace('{id}', $model->id, $body);
        $body = str_replace('{MCOM_date}', $model->mcom_date, $body);
        $body = str_replace('{UMC_date}', $model->umc_date, $body);
        $body = str_replace('{principle}', $model->principle, $body);
        $body = str_replace('{advice}', $model->advice, $body);
        $body = str_replace('{execution_date}', $model->execution_date, $body);
        $body = str_replace('{expiry_date}', $model->end_date, $body);

        // Initialize the CC array
        $ccRecipients = [];

        // Get the CC group from the map
        $ccGroup = $mailMap[$model->status]['cc'];

        // Determine the actual CC recipients based on the CC group
        if (!empty($ccGroup)) {
            if ($ccGroup === 'OSC') {
                // Determine the specific OSC type based on `directed_to`
                $oscType = $model->transfer_to; // IO, RMC, OIL, etc.
                $ccAdmins = Admin::find()->where(['type' => $oscType])->all();
                foreach ($ccAdmins as $admin) {
                    $ccRecipients[] = $admin->email;
                }
            } else {
                // Handle other CC groups (e.g., OLA)
                $ccGroups = explode(', ', $ccGroup);
                foreach ($ccGroups as $group) {
                    $ccAdmins = Admin::find()->where(['type' => $group])->all();
                    foreach ($ccAdmins as $admin) {
                        $ccRecipients[] = $admin->email;
                    }
                }
            }
        }

        // Add POCs to CC recipients
        foreach ($pocs as $poc) {
            $ccRecipients[] = $poc->pi_email;
        }

        // Compose and send the email
        $mailer = Yii::$app->mailer->compose([
            'html' => '@backend/views/email/emailTemplate.php'
        ], [
            'subject' => $mail->subject,
            'recipientName' => $modelPoc->pi_name,
            'reason' => $model->reason,
            'body' => $body
        ])
            ->setFrom(['noReply@iium.edu.my' => 'Memorandum Program '.Yii::$app->user->identity->type])
            ->setTo($modelPoc->pi_email)
            ->setSubject($mail->subject);

        if (!empty($ccRecipients)) {
            $mailer->setCc($ccRecipients);
        }

        $mailer->send();
    }


        public function actionUpdatePoc($id)
    {
        $model = $this->findModel($id);
        $modelsPoc = AgreementPoc::find()
            ->where(['agreement_id' => $id])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        if ($this->request->isPost) {
            $modelsPocData = Yii::$app->request->post('AgreementPoc', []);
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
            return $this->redirect(['index']);

        }

        return $this->renderAjax('_poc', [
            'model' => $model,
            'modelsPoc' => $modelsPoc,
        ]);
    }


    public function actionMcom($id)
    {
        $model = $this->findModel($id);

        $nextTwoWeeks = Carbon::now()->addWeeks(2)->toDateTimeString();
        $nextTwoMonths = Carbon::now()->addMonths(2)->toDateTimeString();

        $mcomDates = McomDate::find()
            ->where(['<', 'counter', 10])
            ->andWhere(['>', 'date_from', $nextTwoWeeks])
            ->andWhere(['<', 'date_from', $nextTwoMonths])
            ->limit(3)
            ->all();


        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 121;
            $model->temp = "(" . Yii::$app->user->identity->type . ") " . "(" . Yii::$app->user->identity->staff_ID . ") " . Yii::$app->user->identity->username;
            if ($model->save()) {

                $this->sendEmail($model, ($model->status != 2 && $model->status != 1));

                return $this->redirect(['index']);
            }

        }

        return $this->renderAjax('_mcom', ['model' => $model, 'mcomDates' => $mcomDates]);
    }

    /**
     * Creates a new Agreement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */


//    public function actionAddActivity($id = '')
//    {
//        $agreement = $this->findModel($id);
//        $model = new Activities();
//        $model->agreement_id = $id;
//
//        if ($this->request->isPost && $model->load($this->request->post())) {
//            if ($model->save()) {
//                return $this->redirect('index');
//            }
//        }
//
//        return $this->renderAjax('addActivity', [
//            'model' => $model,
//            'agreement' => $agreement,
//        ]);
//    }

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

    public function actionBulkDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $ids = Yii::$app->request->post('ids', []);
        if (empty($ids)) {
            return ['success' => false, 'message' => 'No items selected.'];
        }

        foreach ($ids as $id) {
            $model = $this->findModel($id);
            if ($model !== null) {
                // Delete associated files
                $folderPath = Yii::getAlias('@common/uploads/' . $id);
                if (is_dir($folderPath)) {
                    $this->deleteDirectory($folderPath);
                }

                // Delete the model
                $model->delete();
            }
        }

        return ['success' => true, 'message' => 'Selected items have been deleted.'];
    }

    protected function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir) || is_link($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                chmod($dir . DIRECTORY_SEPARATOR . $item, 0777);

                if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                    return false;
                }
            }
        }

        return rmdir($dir);
    }


    public function actionLog($id)
    {


        $logsDataProvider = new ActiveDataProvider([
            'query' => Log::find()->where(['agreement_id' => $id]),
            'pagination' => ['pageSize' => 100,],
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC], // Display logs by creation time in descending order
            ],]);

        $logModel = Agreement::findOne($id);

        return $this->renderAjax('log', [
            'logsDataProvider' => $logsDataProvider,
            'logModel' => $logModel
        ]);
    }


    public function actionDownloader($filePath)
    {
        Yii::$app->response->sendFile($filePath);
    }

    public function actionGetKcdioPoc($id)
    {
        $poc = Poc::find()->where(['kcdio' => $id])->all();

        if ($poc) {
            $options = "<option value=''>Select POC</option>";
            foreach ($poc as $apoc) {
                $options .= "<option value='" . $apoc->id . "'>" . $apoc->name . "</option>";
            }
        }

        echo $options;
    }

    public function actionGetPocInfo($id)
    {


        if (is_numeric($id))
            $poc = Poc::findOne($id);
        else
            $poc = Poc::find()->where(['name' => $id])->one();

        if ($poc) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'name' => $poc->name,
                'address' => $poc->address,
                'email' => $poc->email,
                'phone_number' => $poc->phone_number,
            ];
        } else {
            return ['error' => 'POC not found'];
        }
    }


    public function actionImport()
    {

        $model = new Import();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $file = UploadedFile::getInstance($model, 'importedFile');
            if ($file) {

                $baseUploadPath = Yii::getAlias('@common/uploads');
                $inputName = preg_replace('/[^a-zA-Z0-9]+/', '_', $file->name);
                $fileName = 'import_' . '.' . $file->extension;
                $filePath = $baseUploadPath . '/' . $model->id . '/' . $fileName;

                // Create directory if not exists
                if (!file_exists(dirname($filePath))) {
                    mkdir(dirname($filePath), 0777, true);
                }

                $file->saveAs($filePath);

            }
            if ($model->save()) {
                $model->type == "Agreement" ? $this->importExcel($filePath, $model) : $this->importExcelActivity($filePath);
                return $this->redirect(['index']);
            }

        }
        return $this->renderAjax('import', ['model' => $model]);

    }

    public function importExcel1($filePath, $model)
    {
        // to import an excel file to the system, the excel file need to be in this format

        //#1 columns should follow the order in the for loop
        //#2 details of person in charge should be in this order name, kcdio, address, phone, email between each one |
        $to = $model->import_from;
        $temp = "(" . Yii::$app->user->identity->type . ") " . "(" . Yii::$app->user->identity->staff_ID . ") " . Yii::$app->user->identity->username;
        $insertedRowIds = []; // Array to store inserted row IDs
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            unset($sheetData[1]);

            foreach ($sheetData as $row) {
                if (!empty($row['A'])) {
                    $kcdioName = Kcdio::findOne(['kcdio' => $row['E']])->tag ?? 'Error';
                    $pi_details = $this->applyExcelFormula($row['K']);

                    $parts = explode('|', $row['K']);


                    $status = $row['L'] == "Active" ? 100 : 102;

                    $agreement = new Agreement();
                    $agreement->agreement_type = $row['B'];
                    $agreement->col_organization = $row['C'];
                    $agreement->country = $row['D'];
                    $agreement->champion = $kcdioName;
                    $agreement->sign_date = $row['G'];
                    $agreement->end_date = $row['H'];
                    $agreement->status = $status;
                    $agreement->transfer_to = $to;
                    $agreement->temp = $temp;

                    // Save the agreement
                    if ($agreement->save()) {
                        $agreementPoc = new AgreementPoc();
                        $agreementPoc->agreement_id = $agreement->id;
                        foreach ($parts as $index => $part) {
                            if ($index == 0) {
                                $agreementPoc->pi_name = $part;
                            } elseif ($index == 1) {
                                $agreementPoc->pi_kcdio = $part;
                            } elseif ($index == 2) {
                                $agreementPoc->pi_address = $part;
                            } elseif ($index == 3) {
                                $agreementPoc->pi_phone = $part;
                            } elseif ($index == 4) {
                                $agreementPoc->pi_email = $part;
                            }
                        }
                        $agreementPoc->save();
                    } else {
                        Yii::error('Failed to save agreement: ' . print_r($agreement->errors, true));
                    }
                }
            }

            Yii::$app->session->setFlash('success', 'Data imported successfully.');

        } catch (Exception $e) {
            var_dump($e);
            die();
        }

        return $insertedRowIds;
    }

    public function importExcel($filePath, $model){
        // to import an excel file to the system, the excel file need to be in this format

        //#1 columns should follow the order in the for loop
        //#2 details of person in charge should be in this order name, kcdio, address, phone, email between each one |
        $to = $model->import_from;
        $temp = "(" . Yii::$app->user->identity->type . ") " . "(" . Yii::$app->user->identity->staff_ID . ") " . Yii::$app->user->identity->username;
        $insertedRowIds = []; // Array to store inserted row IDs
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            unset($sheetData[1]);

            foreach ($sheetData as $row) {
                if (!empty($row['A'])) {
                    $kcdioName = Kcdio::findOne(['kcdio' => $row['D']])->tag ?? 'Error';

                    $status = $row['S'] == "Active" ? 100 : 102;

                    $agreement = new Agreement();
                    $agreement->agreement_type = $row['A'];
                    $agreement->col_organization = $row['B'];
                    $agreement->country = $row['C'];
                    $agreement->champion = $row['D'];
                    $agreement->project_title = $row['E'];
                    $agreement->grant_fund = $row['F'];
                    $agreement->member = $row['G'];
                    $agreement->col_name = $row['L'];
                    $agreement->col_email = $row['M'];
                    $agreement->col_address = $row['N'];
                    $agreement->col_collaborators_name = $row['O'];
                    $agreement->col_wire_up = $row['P'];
                    $agreement->sign_date = $row['Q'];
                    $agreement->end_date = $row['R'];

                    $agreement->status = $status;
                    $agreement->transfer_to = $to;
                    $agreement->temp = $temp;

                    // Save the agreement
                    if ($agreement->save()) {
                        $agreementPoc = new AgreementPoc();
                        $agreementPoc->agreement_id = $agreement->id;
                        $agreementPoc->pi_name = $row['H'];
                        $agreementPoc->pi_email = $row['I'];
                        $agreementPoc->pi_address = $row['J'];
                        $agreementPoc->pi_phone = $row['K'];
                        $agreementPoc->pi_kcdio = $row['D'];
                        $agreementPoc->save();
                    } else {
                        Yii::error('Failed to save agreement: ' . print_r($agreement->errors, true));
                    }
                }
            }

            Yii::$app->session->setFlash('success', 'Data imported successfully.');

        } catch (Exception $e) {
            var_dump($e);
            die();
        }

        return $insertedRowIds;
    }


    private function applyExcelFormula($value)
    {
        // Replace line breaks in each cell value
        return str_replace(["\r\n", "\n", "\r"], "</br>", $value);
    }

    public function importExcelActivity($filePath)
    {

        $input_string = "ALUMNI - International Islamic Fiqh Academy Saudi Arabia (19/09/2025)";

        $parts = explode('-', $input_string);

        // Extracting the desired part
        $second_part = $parts[1];

        // Extracting "International Islamic Fiqh Academy"
        $academy_name = substr($second_part, 0, strpos($second_part, ','));


        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            unset($sheetData[1]);

            $batchData = [];
            foreach ($sheetData as $row) {

                if (!empty($row['A'])) {

                    $academy_name = substr(explode('-', $row['F'])[1], 1, strpos(explode('-', $row['F'])[1], ',') - 1);
                    // Fetch all Agreements, likely needs optimization for efficiency
                    $agreements = Agreement::find()->all();

                    $similarities = []; // Map to store similarities

                    foreach ($agreements as $agreement) {
                        $similarity = similar_text($academy_name, $agreement->col_organization, $percent);
                        if ($percent >= 70.0) {
                            $similarities[$agreement->id] = $percent; // Store similarity percentage
                        }
                    }

                    // Find the agreement with the highest similarity percentage
                    $maxSimilarity = 0;
                    $agreement_id = null;
                    foreach ($similarities as $agreementId => $similarityPercent) {
                        if ($similarityPercent > $maxSimilarity) {
                            $maxSimilarity = $similarityPercent;
                            $agreement_id = $agreementId;
                        }
                    }
                    $credited_name_of_student = $this->applyExcelFormula($row['K']);
                    $non_credited_name_of_student = $this->applyExcelFormula($row['R']);


                    $batchData[] = [$agreement_id,//ID

                        $row['C'],  //Name
                        $row['D'],  //Staff No
                        $row['E'],  //KCDIOs
                        $row['G'],  //Implementation Activities

                        $row['I'],  //Credited Type
                        $row['J'],  //Credited Number of Students
                        $credited_name_of_student,  //Credited Name of Students
                        $row['L'],  //Credited Semester
                        $row['M'],  //Credited Year

                        $row["P"],  //Non-credited Type
                        $row['Q'],  //Non-credited Number of Students
                        $non_credited_name_of_student,  //Non-credited Name of Students
                        $row['S'],  //Non-credited Name of Program

                        $row['V'],  //Inbound Number of Staff Involved
                        $row['W'],  //Inbound Name of Staff Involved
                        $row['X'],  //Inbound Department Office

                        $row['AA'],  //Outbound Number of Staff Involved
                        $row['AB'], //Outbound Name of Staff Involved

                        $row['AE'], //SCWT Name of Program
                        $row['AF'], //Date of the Program
                        $row['AG'], //Venue of the Program
                        $row['AH'], //Number of Participants
                        $row['AI'], //Name of Participants

                        $row['AL'], //Research Title

                        $row['AO'], //Title of Publication
                        $row['AP'], //Publisher

                        $row['AS'], //Name of Consultancy
                        $row['AT'], //Duration of Project

                        $row['AW'], //Other Activity, Please Specify
                        $row['AX'], //Other Activity, Date

                        $row['BA'], //No Activity, Justification
                    ];

                }

            }

            // Perform batch insert
            Yii::$app->db->createCommand()->batchInsert('activities', ['agreement_id',//agreement_ID

                'name',//row c
                'staff_number',//row d
                'kcdio',//row e
                'activity_type',//row g

                'type',//row i
                'number_students',//row j
                'name_students',//credited name of students
                'semester',//row l
                'year',//row m

                'non_type',//row p
                'non_number_students',//row q
                'non_name_students',//non credited name of student
                'non_program_name',//row s

                'in_number_of_staff',//row v
                'in_staffs_name',//row w
                'in_department_office',//row x

                'out_number_of_staff',//row aa
                'out_staffs_name',//row ab

                'scwt_name_of_program',//row ae
                'date_of_program',//row af
                'program_venue',//row ag
                'participants_number',//row ah
                'name_participants_involved',//row ai

                'research_title',//row al

                'publication_title',//row ao
                'publisher',//row ap

                'consultancy_name',//row as
                'project_duration',//row at

                'other',//row aw
                'date',//row ax

                'justification',//row ba

            ], $batchData)->execute();

            Yii::$app->session->setFlash('success', 'Data Imported Successfully.');

            // You can redirect the user to another page or render a view here
            return $this->redirect(['index']); // Redirect to index or wherever you want
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', 'Unsuccessful Import.');
        }
    }

    public function actionDeleteFile($id, $filename)
    {
        $model = Agreement::findOne($id);
        $filePath = $model->dp_doc . $filename;

        if (file_exists($filePath) && unlink($filePath)) {
            Yii::$app->session->setFlash('success', 'File deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete file.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    public function actionGeneratePdf($id)
    {
        // Fetch data for PDF
        $logsDataProvider = new ActiveDataProvider([
            'query' => Log::find()->where(['agreement_id' => $id]),
            'pagination' => ['pageSize' => 100],
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);
        $model = Agreement::findOne($id);

        // Render PDF content
        $content = $this->renderPartial('pdf_template', [
            'logsDataProvider' => $logsDataProvider,
            'model' => $model
        ]);

        // Path to CSS files
        $cssFiles = [
            Yii::getAlias('@webroot/css/styles.css'),
        ];

        // Initialize mPDF
        $mpdf = new Mpdf();

        // Write CSS to mPDF
        foreach ($cssFiles as $cssFile) {
            if (file_exists($cssFile)) {
                $cssContent = file_get_contents($cssFile);
                $mpdf->WriteHTML($cssContent, HTMLParserMode::HEADER_CSS);
            }
        }

        // Write HTML content to mPDF
        $mpdf->WriteHTML($content, HTMLParserMode::HTML_BODY);

        // Output the PDF
        $mpdf->Output();
        exit;
    }

    public function actionDashboard(){
        $searchModel = new AgreementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Get data for charts
        $agreementCountsByCountry = $searchModel->getAgreementCountsByCountry();
        $executedAgreementsCount = $searchModel->getExecutedAgreementsCount();
        $expiredAgreementsCount = $searchModel->getExpiredAgreementsCount();

        // Prepare data for charts
        $countryChartData = [
            'categories' => array_column($agreementCountsByCountry, 'country'),
            'series' => array_column($agreementCountsByCountry, 'count'),
        ];

        $pieChartData = [
            'categories' => ['Executed', 'Expired'],
            'series' => [$executedAgreementsCount, $expiredAgreementsCount],
        ];

        return $this->render('dashboard', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'countryChartData' => $countryChartData,
            'pieChartData' => $pieChartData,
        ]);
    }


}
