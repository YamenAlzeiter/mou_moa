<?php

namespace backend\controllers;

use common\models\Activities;
use common\models\admin;
use common\models\Agreement;
use common\models\EmailTemplate;
use common\models\Import;
use common\models\Kcdio;
use common\models\Log;
use common\models\search\AgreementSearch;
use common\models\User;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
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
                        'actions' => ['index', 'update', 'view', 'downloader', 'log', 'get-organization', 'import-excel', 'import-excel-activity', 'view-activities', 'import'],
                        'allow' => !Yii::$app->user->isGuest,
                        'roles' => ['@'],
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
        $dataProvider->sort->defaultOrder = ['updated_at' => SORT_DESC];
     if ($type != 'OLA'){
         $dataProvider->query->andWhere(['transfer_to' => $type]);
     }


        $dataProvider->pagination = [
            'pageSize' => 11,
        ];
        if(!Yii::$app->user->isGuest){
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else return throw new ForbiddenHttpException("You need to login in order to have access to this page");
    }

    /**
     * Displays a single Agreement model.
     * @param  int  $id  ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionView($id)
    {
        $haveActivity = Activities::findOne(['agreement_id'=> $id])!== null;
        if(!Yii::$app->user->isGuest){
            if (!Yii::$app->request->isAjax) {
                return throw new ForbiddenHttpException('You are not authorized  to access this page!');
            }
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
                'haveActivity' => $haveActivity
            ]);
        }else return throw new ForbiddenHttpException("You need to login in order to have access to this page");

    }

    public function actionViewActivities($id)
    {
        $model = Activities::find()->where(['agreement_id'=> $id])->all();
        if(!Yii::$app->user->isGuest){

            return $this->renderAjax('viewActivities', [
                'model' => $model,
            ]);
        }else return throw new ForbiddenHttpException("You need to login in order to have access to this page");

    }

    /**
     * Creates a new Agreement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */


//    public function actionCreate()
//    {
//        $model = new Agreement();
//
//        if ($this->request->isPost) {
//            if ($model->load($this->request->post()) && $model->save()) {
//                return $this->redirect(['view', 'id' => $model->id]);
//            }
//        } else {
//            $model->loadDefaultValues();
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//        ]);
//    }


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
        $status = $model->status;


        if ($this->request->isPost && $model->load($this->request->post())) {
            $this->fileHandler($model, 'olaDraft', 'draft', 'doc_draft');
            $this->fileHandler($model, 'oscDraft', 'draftOSC', 'doc_newer_draft');
            $this->fileHandler($model, 'finalDraft', 'FinalDraft', 'doc_final');
            if ($model->save()){

                if(    $model->status == 1  || $model->status == 2
                    || $model->status == 12 || $model->status == 11
                    || $model->status == 32 || $model->status == 33
                    || $model->status == 42 || $model->status == 43
                    || $model->status == 51 || $model->status == 41
                    || $model->status == 72 || $model->status == 81){
                    $this->sendEmail($model, ($model->status != 2 && $model->status != 1) );
                }
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
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

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

        return $this->renderAjax
        ('log', [
            'logsDataProvider' => $logsDataProvider,
        ]);
    }

    /**
     * Creates a new Agreement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
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

    public function actionDownloader($filePath)
    {
        Yii::$app->response->sendFile($filePath);
    }

    private function sendEmail($model, $needCC){
        $mailMap =[
            //$model->status => emailTemplate->id//
            1  => 5, // new application    from OSC to OLA
            2  => 4, // not complete       from OSC to Applicant
            12 => 4, // not complete       from OLA to OSC CC Applicant
            11 => 2, // Pick date          from OLA
            32 => 1, // MCOM Reject        from OLA to Applicant CC OSC
            33 => 4, // MCOM not Complete  from OLA to Applicant CC OSC
            41 => 9, // Approved UMC       from OLA to Applicant CC OSC
            42 => 1, // UMC Reject         from OLA to Applicant CC OSC
            43 => 4, // UMC not Complete   from OLA to Applicant CC OSC
            51 => 3, // draft uploaded     from OLA to Applicant CC OSC
            72 => 7, // draft rejected     from OLA to OSC
            81 => 8, // final Draft uploaded from OLA to Applicant CC OSC
        ];

        $mail = EmailTemplate::findOne($mailMap[$model->status]);

        $osc =  Admin::find()
            ->where(['type' => 'IO'])
            ->all();

        $ola = Admin::findOne(['type' => 'OLA']);

        $body = $mail->body;

        $body = str_replace('{recipientName}', $model->pi_name, $body);
        $body = str_replace('{reason}', $model->reason, $body);
//        $body = str_replace('{link}', $viewLink, $body);

        $mailer = Yii::$app->mailer->compose([
            'html' => '@backend/views/email/emailTemplate.php'
        ],[
            'subject' => $mail->subject,
            'recipientName' => $model->pi_name,
            'reason' => $model->reason,
            'body' => $body
        ])->setFrom(['noReplay@iium.edy.my' => 'IIUM'])->setTo($model->status ==  1? $ola->email : $model->pi_email)->setSubject($mail->subject);

        if ($needCC) {
            foreach ($osc as $admin)

                $ccRecipients[] = $admin->email;

            $mailer->setCc($ccRecipients);
        }

        $mailer->send();
    }

    function fileHandler($model, $attribute, $fileNamePrefix, $docAttribute)
    {

        $file = UploadedFile::getInstance($model, $attribute);
        if ($file) {

            $baseUploadPath = Yii::getAlias('@common/uploads');
            $inputName = preg_replace('/[^a-zA-Z0-9]+/', '_', $file->name);
            $fileName = $model->id.'_'.$fileNamePrefix.'.'.$file->extension;
            $filePath = $baseUploadPath.'/'.$model->id.'/'.$fileName;

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
    public function actionGetOrganization()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $kcdio = Yii::$app->request->post('kcdio');
        $userType = Yii::$app->request->post('userType');

        $organizations = Agreement::find()
            ->where(['transfer_to' => $userType, 'pi_kulliyyah' => $kcdio])
            ->all();

        $options = '<option value="">Select Organization</option>';
        foreach ($organizations as $organization) {
            $options .= '<option value="'.$organization->col_organization.'">'.$organization->pi_kulliyyah.' - '. $organization->col_organization.'</option>';
        }

        return ['html' => $options];
    }

    public function actionImport(){
        $model = new Import();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $file = UploadedFile::getInstance($model, 'importedFile');
            if ($file) {

                $baseUploadPath = Yii::getAlias('@common/uploads');
                $inputName = preg_replace('/[^a-zA-Z0-9]+/', '_', $file->name);
                $fileName = 'import_'.'.'.$file->extension;
                $filePath = $baseUploadPath.'/'.$model->id.'/'.$fileName;

                // Create directory if not exists
                if (!file_exists(dirname($filePath))) {
                    mkdir(dirname($filePath), 0777, true);
                }

                $file->saveAs($filePath);

            }
            if ($model->save()){
                $model->type == "Agreement" ? $this->importExcel($filePath, $model) : $this->importExcelActivity($filePath);
                return $this->redirect(['index']);
            }

        }
        return $this->renderAjax('import', ['model' => $model]);

    }

    public function importExcel($filePath, $model)
    {

        $to = $model->import_from;
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            unset($sheetData[1]);

            $batchData = [];
            foreach ($sheetData as $row) {

                if (!empty($row['A'])) {
                    $kcdioName = Kcdio::findOne(['kcdio' => $row['E']])->tag ?? $row['G'];
                    $pi_details = $this->applyExcelFormula($row['K']);
                    $col_details = $this->applyExcelFormula($row['J']);

                    // Assuming your Excel columns are in the same order as your database columns
                    $batchData[] = [
                        $row['B'],
                        $row['C'],
                        $row['D'],
                        $kcdioName,
                        $row['G'], //kulliyyah
                        $row['H'],
                        $row['I'],
                        $col_details,
                        $pi_details,
                        $row['L'],
                        $to,
                    ];
                }
            }
            // Perform batch insert
            Yii::$app->db->createCommand()->batchInsert('agreement',
                ['agreement_type', 'col_organization', 'country',
                    'pi_kulliyyah', 'sign_date', 'end_date', 'collaboration_area',
                    'pi_details', 'col_details', 'status', 'transfer_to'], $batchData)->execute();

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
        $input_string = "ALUMNI - International Islamic Fiqh Academy, Saudi Arabia (19/09/2025)";
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

                if (!empty($row['A'])){

                    $academy_name = substr(explode('-', $row['F'])[1], 1,
                        strpos(explode('-', $row['F'])[1], ',') - 1);

                    $agreement_id = Agreement::findOne(['col_organization' => $academy_name])->id ?? null;

                    $batchData[] = [
                        $agreement_id,
                        $row['C'], //Name:
                        $row['D'], //Staff No
                        $row['E'], //KCDIOs
                        $row['G'], //Implementation Activities
                    ];
                }

            }

            // Perform batch insert
            Yii::$app->db->createCommand()->batchInsert('activities',
                ['agreement_id', 'name', 'staff_number',
                    'kcdio', 'activity_type'], $batchData)->execute();

            Yii::$app->session->setFlash('success', 'Data imported successfully.');

            // You can redirect the user to another page or render a view here
            return $this->redirect(['index']); // Redirect to index or wherever you want
        } catch (Exception $e) {

        }
    }

}
