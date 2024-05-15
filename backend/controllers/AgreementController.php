<?php

namespace backend\controllers;

use Carbon\Carbon;
use common\helpers\Model;
use common\models\Activities;
use common\models\admin;
use common\models\Agreement;
use common\models\AgreementPoc;
use common\models\EmailTemplate;
use common\models\Import;
use common\models\Kcdio;
use common\models\Log;
use common\models\Poc;
use common\models\search\AgreementSearch;
use Exception;
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
        return ['access' => ['class' => AccessControl::class, 'rules' => [['actions' => ['index', 'update', 'view', 'downloader', 'log', 'get-organization', 'import-excel', 'import-excel-activity', 'view-activities', 'import', 'mcom', 'update-poc', 'create-poc', 'create', 'get-poc-info', 'get-kcdio-poc', 'delete-file'], 'allow' => !Yii::$app->user->isGuest, 'roles' => ['@'],],],], 'verbs' => ['class' => VerbFilter::class, 'actions' => ['logout' => ['post'],],],];
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
        $type != "OLA" ? $topStatuses = [10, 15, 81] : $topStatuses = [1, 21, 31, 41, 61];

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
            $path = $baseUploadPath. '/' . $model->id . '/higher/';

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
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $this->multiFileHandler($model, 'files_applicant', 'draft', 'dp_doc');
            $model->temp = "(" . Yii::$app->user->identity->type . ") " . "(" . Yii::$app->user->identity->staff_ID . ") " . Yii::$app->user->identity->username;
            if ($model->save()) {
                if (in_array($model->status, [2, 12, 31, 32, 33, 34, 41, 42, 43, 51, 72, 81])) {
                    $this->sendEmail($model, ($model->status != 2 && $model->status != 1));
                }
                return $this->redirect(['index']);
            }

        }


        return $this->renderAjax('update', ['model' => $model,]);
    }

    private function sendEmail($model, $needCC)
    {
        $mailMap = [//$model->status => emailTemplate->id//
            1 => 5,     // new application    from OSC to OLA
            2 => 4,     // not complete       from OSC to Applicant
            12 => 4,    // not complete       from OLA to OSC CC Applicant
            11 => 2,    // Pick date          from OLA
            31 => 2,    // MCOM Recommended   from OLA to Applicant CC OSC
            32 => 1,    // MCOM Reject        from OLA to Applicant CC OSC
            33 => 4,    // MCOM not Complete  from OLA to Applicant CC OSC
            34 => 2,    // MCOM Special       from OLA to Applicant CC OSC
            41 => 9,    // Approved UMC       from OLA to Applicant CC OSC
            42 => 1,    // UMC Reject         from OLA to Applicant CC OSC
            43 => 4,    // UMC not Complete   from OLA to Applicant CC OSC
            51 => 3,    // draft uploaded     from OLA to Applicant CC OSC
            72 => 7,    // draft rejected     from OLA to OSC
            81 => 8,    // final Draft uploaded from OLA to Applicant CC OSC
            121 => 11,  // MCOM Date Updated  from OLA to Applicant cc OSC
        ];

        $mail = EmailTemplate::findOne($mailMap[$model->status]);

        $osc = Admin::find()->where(['type' => 'IO'])->all();

        $ola = Admin::findOne(['type' => 'OLA']);

        $body = $mail->body;

        $poc1 = $model->pi_email_x != '' ? $model->pi_email_x : null;
        $poc2 = $model->pi_email_xx != '' ? $model->pi_email_xx : '';

        $body = str_replace('{recipientName}', $model->pi_name, $body);
        $body = str_replace('{reason}', $model->reason, $body);
//        $body = str_replace('{link}', $viewLink, $body);

        // Initialize the CC array
        $ccRecipients = [];

        // Add CCs if needed
        if ($needCC) {
            $osc = Admin::find()->where(['type' => 'IO'])->all();
            foreach ($osc as $admin) {
                $ccRecipients[] = $admin->email;
            }
        }

        // Handle optional CCs
        if ($model->pi_email_x != '') {
            $ccRecipients[] = $model->pi_email_x;
        }
        if ($model->pi_email_xx != '') {
            $ccRecipients[] = $model->pi_email_xx;
        }

        // Compose and send the email
        $mailer = Yii::$app->mailer->compose(['html' => '@backend/views/email/emailTemplate.php'], ['subject' => $mail->subject, 'recipientName' => $model->pi_name, 'reason' => $model->reason, 'body' => $body])->setFrom(['noReplay@iium.edy.my' => 'IIUM'])->setTo($model->pi_email)->setSubject($mail->subject);


        $mailer->setCc($ccRecipients);

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
                $poc =  isset($data['id']) ? AgreementPoc::findOne($data['id']) : null;
                $poc->load($data, '');
                $modelsPoc[] = $poc;
            }
                foreach ($modelsPoc as $modelPoc) {

                    $modelPoc->save(false);
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

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = 121;
            $temp = "(" . Yii::$app->user->identity->type . ") " . "(" . Yii::$app->user->identity->staff_ID . ") " . Yii::$app->user->identity->username;
            if ($model->save()) {

                $this->sendEmail($model, ($model->status != 2 && $model->status != 1));

                return $this->redirect(['index']);
            }

        }

        return $this->renderAjax('_mcom', ['model' => $model,]);
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

    public function actionLog($id)
    {


        $logsDataProvider = new ActiveDataProvider(['query' => Log::find()->where(['agreement_id' => $id]), 'pagination' => ['pageSize' => 100,], 'sort' => ['defaultOrder' => ['created_at' => SORT_DESC], // Display logs by creation time in descending order
        ],]);

        return $this->renderAjax('log', ['logsDataProvider' => $logsDataProvider,]);
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
        } else {
            $options = "<option value=''>Person In charge Not found</option>";
        }

        echo $options;
    }

    public function actionGetPocInfo($id)
    {


        if(is_numeric($id))
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

    public function importExcel($filePath, $model)
    {
        $to = $model->import_from;
        $temp = "(" . Yii::$app->user->identity->type . ") " . "(" . Yii::$app->user->identity->staff_ID . ") " . Yii::$app->user->identity->username;
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            unset($sheetData[1]);

            $batchData = [];
            foreach ($sheetData as $row) {

                if (!empty($row['A'])) {
                    $kcdioName = Kcdio::findOne(['kcdio' => $row['E']])->tag ?? 'Error';
                    $pi_details = $this->applyExcelFormula($row['K']);

                    $status = $row['L'] == "Active" ? 100 : 102;

                    $batchData[] = [$row['B'], $row['C'], $row['D'], $kcdioName, $row['G'], $row['H'], $row['I'], $pi_details, $status, $to, $temp];
                }

            }

            // Perform batch insert
            Yii::$app->db->createCommand()->batchInsert('agreement', ['agreement_type', 'col_organization', 'country', 'pi_kulliyyah', 'sign_date', 'end_date', 'collaboration_area', 'pi_details', 'status', 'transfer_to', 'temp'], $batchData)->execute();

            Yii::$app->session->setFlash('success', 'Data imported successfully.');

        } catch (Exception $e) {
            var_dump($e);
            die();
        }
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
                        if ($percent >= 40.0) {
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

}
