<?php

namespace backend\controllers;


use Carbon\Carbon;
use common\helpers\Model;
use common\helpers\Variables;
use common\models\Activities;
use common\models\Agreement;
use common\models\AgreementPoc;
use common\models\Collaboration;
use common\models\EmailTemplate;
use common\models\Import;
use common\models\Log;
use common\models\LookupCdKcdiom;
use common\models\McomDate;
use common\models\search\AgreementSearch;
use common\models\search\DashboardSearch;
use common\models\User;
use Exception;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\filters\AccessControl;
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
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['OSIC'], // Admins can access all actions
                        ],
                        [
                            'allow' => true,
                            'roles' => ['OLA'],
                        ],
                        [
                            'allow' => false,
                            'roles' => ['OLA'],
                            'actions' => [
                                 'import-excel', 'import-excel-activity', 'update-poc', 'bulk-delete', 'dashboard', 'update-record'],
                        ],
                        [
                            'allow' => true,
                            'roles' => ['IO' , 'OIL', 'RMC'],
                        ],
                        [
                            'allow' => false,
                            'roles' => ['IO' , 'OIL', 'RMC'],
                            'actions' => ['mcom', 'create-poc', 'create', 'get-poc-info', 'get-kcdio-poc', 'bulk-delete', 'update-record'],
                        ],
                        [
                            'allow' => false,
                        ],
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
        $dataProvider = $searchModel->search($this->request->queryParams);

        $type = array_values(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))[0]->name;
        $dataProvider->sort->defaultOrder = ['updated_at' => SORT_DESC];

        // Define the statuses to be always on top
        !Yii::$app->user->can('OLA') ? $topStatuses = [10, 15, 81] : $topStatuses = [1, 21, 31, 41, 61, 121];

        if (!Yii::$app->user->can('OLA') &&
            !Yii::$app->user->can('OSIC'))
        {
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
        $model = $this->findModel($id);
        $haveActivity = Activities::findOne(['col_id' => $model->col_id]) !== null;
        $modelsPoc = AgreementPoc::find()->where(['agreement_id' => $id])->all();
        $modelCol = Collaboration::findone(['id' =>$model->col_id]);
        if (!Yii::$app->user->isGuest) {

            return $this->renderAjax('view', [
                'model' => $model,
                'modelCol' => $modelCol,
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
            $existingColModel = Collaboration::find()
                ->where(['ILIKE', 'col_organization', $colModel->col_organization])
                ->one();
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
                        $this->multiFileHandler($model, 'files_applicant','document', 'applicant_doc');
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

    function multiFileHandler($model, $attribute, $fileNamePrefix, $docAttribute)
    {
        //31k folder in upload
        $files = UploadedFile::getInstances($model, $attribute);
        if ($files) {
            $baseUploadPath = Yii::getAlias('@common/uploads');
            $path = $baseUploadPath . '/' . $model->id . '/higher/';

            foreach ($files as $file) {
                $fileName = $file->baseName . '_'. date('Ymdhis') . '.' . $file->extension;

                $filePath = $path . $fileName ;
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

    public function actionViewActivities($id)
    {
        $model = Activities::find()->where(['col_id' => $id])->all();
        return $this->renderAjax('viewActivities', ['model' => $model,]);
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

        if ($this->request->isPost && $model->load($this->request->post())){
            $this->multiFileHandler($model, 'files_applicant', 'draft', 'dp_doc');
            $model->temp = "(" . array_values(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))[0]->name . ") "  . Yii::$app->user->identity->username;

            // Attempt to save the model
            if ( $model->save()) {
                    $this->sendEmail($model, ($model->status != Variables::agreement_not_complete_osc));
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

    public function actionUpdateRecord($id)
    {
        $model = $this->findModel($id);
        $colModel = Collaboration::findOne($model->col_id);
        $modelsPoc = $model->getAgreementPoc()->all();

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

            if ($model->status == Variables::agreement_executed) {
                $model->last_reminder = Carbon::now()->addMonths(3)->toDateTimeString();
            }

            $this->multiFileHandler($model, 'files_dp', 'draft', 'dp_doc');

            $model->temp = "(" . Yii::$app->user->identity->email . ") " . Yii::$app->user->identity->username;

            if ($model->status == Variables::agreement_extended) {
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

                    $newAgreement->applicant_doc = $newFolder . '/applicant/';
                    $newAgreement->dp_doc = $newFolder . '/higher/';
                    $newAgreement->save();
                }

            }

            if ($model->save() && $colModel->save()) {
                $this->sendEmail($model, ($model->status != 2 && $model->status != 1));
                return $this->redirect(['index']);
            }

        }
        return $this->renderAjax('update-record', [
            'model' => $model,
            'colModel' => $colModel,
            'modelsPoc' => $modelsPoc,
        ]);
    }

    private function sendEmail($model, $needCC)
    {
        $log = Log::find()
            ->where(['agreement_id' => $model->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->one();
        if($log->old_status == $log->new_status && $log->changes != null){
            $mailMap = [
                $model->status =>[
                    'template' => Variables::email_agr_changed_updated,
                    'cc' => 'OSC',
                ],
            ];
        }else{
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
                Variables::agreement_approved_circulation => [
                    'template' => Variables::email_agr_approved_circulation,
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
                Variables::agreement_approved_via_power => [
                    'template' => Variables::email_agr_mcom_approved_power,
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
        }

        if (!isset($mailMap[$model->status])) {
            return; // Exit early if there's no template for this status
        }

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
        $body = str_replace('{expiry_date}', $model->agreement_expiration_date, $body);
        $body = str_replace('{circulation}', $model->circulation, $body);
        $body = str_replace('{applicant}', $log->created_by, $body);
        $body = str_replace('{changes}', $log->changes, $body);

        // Initialize the CC array
        $ccRecipients = [];

        // Get the CC group from the map
        $ccGroup = $mailMap[$model->status]['cc'];

        // Determine the actual CC recipients based on the CC group
        if (!empty($ccGroup)) {
            if ($ccGroup === 'OSC') {
                // Determine the specific OSC type based on `directed_to`
                $oscType = $model->transfer_to; // IO, RMC, OIL, etc.
                $ccAdmins = Yii::$app->authManager->getUserIdsByRole($oscType);
                foreach ($ccAdmins as $admin) {
                    $user = User::findOne($admin);
                    if($user != null){
                        $ccRecipients[] = $user->email;
                    }
                }
            } else {
                // Handle other CC groups (e.g., OLA)
                $ccGroups = explode(', ', $ccGroup);
                foreach ($ccGroups as $group) {
                    $ccAdmins = Yii::$app->authManager->getUserIdsByRole($group);
                    foreach ($ccAdmins as $admin) {
                        $user = User::findOne($admin);
                        if($user != null){
                            $ccRecipients[] = $user->email;
                        }
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
            ->setFrom(['noReply@iium.edu.my' => 'Memorandum Program | '. array_values(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))[0]->name ])
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
            $model->temp = "(" . array_values(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))[0]->name . ") "  . Yii::$app->user->identity->username;
            if ($model->save()) {

                $this->sendEmail($model, ($model->status != 2 && $model->status != 1));

                return $this->redirect(['index']);
            }

        }

        return $this->renderAjax('_mcom', ['model' => $model, 'mcomDates' => $mcomDates]);
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
            'query' => Log::find()
                ->where(['agreement_id' => $id])
                ->andWhere([
                    'or',
                    ['!=', 'old_status', new \yii\db\Expression('new_status')],
                    ['and', ['old_status' => null], ['is not', 'new_status', null]]
                ]),
            'pagination' => [
                'pageSize' => 99,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        return $this->renderAjax('log', [
            'logsDataProvider' => $logsDataProvider,
        ]);
    }


    public function actionDownloader($filePath)
    {
        Yii::$app->response->sendFile($filePath);
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

    public function importExcel($filePath, $model)
    {
        $to = array_values(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))[0]->name;
        $temp = "(" . $to . ") " . "(" . Yii::$app->user->identity->email . ") " . Yii::$app->user->identity->username;
        $insertedRowIds = []; // Array to store inserted row IDs

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            unset($sheetData[1]); // Remove header row

            foreach ($sheetData as $row) {
                if (!empty($row['A'])) {

                    // Lookup for kcdioName and handle possible null
                    $kcdioName = LookupCdKcdiom::find()
                        ->where(['kcdiom_desc' => $row['D']])
                        ->orWhere(['abb_code' => $row['D']])
                        ->one();


                    $status = $row['S'] == "Active" ? 100 : 102;
                    $col_agreement = new Collaboration();

                    $col_agreement->col_organization = $row['B'];
                    $col_agreement->country = $row['C'];
                    $col_agreement->col_name = $row['L'];
                    $col_agreement->col_email = $row['M'];
                    $col_agreement->col_address = $row['N'];
                    $col_agreement->col_collaborators_name = $row['O'];
                    $col_agreement->col_wire_up = $row['P'];

                    if ($col_agreement->save(false)) {

                        $agreement = new Agreement();
                        $agreement->col_id = $col_agreement->id;
                        $agreement->agreement_type = $row['A'];

                        $agreement->project_title = $row['E'];
                        $agreement->grant_fund = $row['F'];
                        $agreement->member = $row['G'];
                        $agreement->agreement_sign_date = $row['Q'];
                        $agreement->agreement_expiration_date = $row['R'];
                        $agreement->status = $status;
                        $agreement->transfer_to = $to;
                        $agreement->temp = $temp;

                        if ($agreement->save()) {

                            $agreementPoc = new AgreementPoc();
                            $agreementPoc->agreement_id = $agreement->id;
                            $agreementPoc->pi_name = $row['H'];
                            $agreementPoc->pi_email = $row['I'];
                            $agreementPoc->pi_address = $row['J'];
                            $agreementPoc->pi_phone = $row['K'];
                            $agreementPoc->pi_kcdio = $row['D'];
                            $agreementPoc->pi_is_primary = true;
                            $agreementPoc->save(false);


                        } else {
                            Yii::error('Failed to save Agreement: ' . print_r($agreement->errors, true));
                            $transaction->rollBack(); // Rollback transaction on error
                            return $insertedRowIds; // Return IDs of successfully inserted rows
                        }
                    } else {
                        Yii::error('Failed to save Collaboration: ' . print_r($col_agreement->errors, true));
                    }
                }
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Data imported successfully.');

        } catch (Exception $e) {
            $transaction->rollBack(); // Rollback transaction on exception
            Yii::error('Exception during import: ' . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Error importing data.');
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
        //example
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
                    $agreements = Collaboration::find()->all();

                    $similarities = []; // Map to store similarities

                    foreach ($agreements as $agreement) {
                        $similarity = similar_text($academy_name, $agreement->col_organization, $percent);
                        if ($percent >= 100.0) {
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
                        $row['B'],  //Staff Email
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
            Yii::$app->db->createCommand()->batchInsert('activities', ['col_id',//agreement_ID

                'name',//row c
                'staff_email',//row d
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
            Yii::$app->session->setFlash('error', 'Unsuccessful Import.' . $e);
        }
    }

    public function actionDeleteFile($id, $filePath)
    {
        $model = Agreement::findOne($id);


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

    public function actionDashboard()
    {
        $searchModel = new DashboardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $chartData = $searchModel->getChartData();

        return $this->render('dashboard', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'chartData' => $chartData,
        ]);
    }
}
