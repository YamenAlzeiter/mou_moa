<?php

namespace backend\controllers;

use common\models\Activities;
use common\models\admin;
use common\models\Agreement;
use common\models\Countries;
use common\models\EmailTemplate;
use common\models\Log;
use common\models\search\AgreementSearch;
use common\models\User;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\data\ActiveDataProvider;
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
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
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
        $dataProvider->pagination = [
            'pageSize' => 10,
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
        if (!Yii::$app->request->isAjax) {
            return throw new ForbiddenHttpException('You are not authorized  to access this page!');
        }
        $model = $this->findModel($id);
        $status = $model->status;
        $this->fileHandler($model, 'olaDraft', 'draft', 'doc_draft');
        $this->fileHandler($model, 'oscDraft', 'draftOSC', 'doc_newer_draft');
        $this->fileHandler($model, 'finalDraft', 'FinalDraft', 'doc_final');
        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()){

                if($model->status == 2
                    || $model->status == 12 || $model->status == 11
                    || $model->status == 32 || $model->status == 33
                    || $model->status == 42 || $model->status == 43 || $model->status == 51){
                    $this->sendEmail($model, $model->status != 2);
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
                'pageSize' => 15,
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


    public function actionAddActivity($id = '')
    {
        $model = new Activities();
        $model->agreement_id = $id;

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                return $this->redirect('index');
            }
        }

        return $this->renderAjax('addActivity', [
            'model' => $model,
        ]);
    }



    public function actionTest($id)
    {

        $model = Activities::findOne(['id' => $id]);
        $model->scenario = 'section-1';
        if ($this->request->isPost && $model->load($this->request->post())) {


            if ( $model->save()){

                return $this->redirect(['index']);
            }

        }

        return $this->renderAjax('test', [
            'model' => $model,
        ]);
    }


    public function actionDownloader($filePath)
    {
        Yii::$app->response->sendFile($filePath);
    }

    private function sendEmail($model, $needCC){
        $mailMap =[
            //$model->status => emailTemplate->id//
            2  => 4, // not complete       from OSC
            12 => 4, // not complete       from OLA
            11 => 2, // Pick date          from OLA
            32 => 1, // MCOM Reject        from OLA
            33 => 4, // MCOM not Complete  from OLA
            42 => 1, // UMC Reject         from OLA
            43 => 4, // UMC not Complete   from OLA
            51 => 3, // draf uploaded      from OLA
        ];

        $mail = EmailTemplate::findOne($mailMap[$model->status]);

        $osc =  Admin::find()
            ->where(['type' => 'IO'])
            ->all();


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
        ])->setFrom(['noReplay@iium.edy.my' => 'IIUM'])->setTo($model->pi_email)->setSubject($mail->subject);

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

    public function actionImportExcel()
    {
        $inputFile = Yii::getAlias('@backend/uploads/moumoa.xlsx');

        try {
            $spreadsheet = IOFactory::load($inputFile);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            unset($sheetData[1]);

            $batchData = [];
            foreach ($sheetData as $row) {

                // Assuming your Excel columns are in the same order as your database columns
                $batchData[] = [
                    $row['B'],
                    $row['C'],
                    $row['D'],
                    $row['E'],
                    $row['G'],
                    $row['H'],
                    $row['I'],
                    $row['J'],
                    $row['K'],
                    $row['L'],
                ];
            }

            // Perform batch insert
            Yii::$app->db->createCommand()->batchInsert('agreement',
                ['agreement_type', 'col_organization', 'country',
                    'pi_kulliyyah', 'sign_date', 'end_date', 'collaboration_area',
                    'pi_details', 'col_details', 'status'], $batchData)->execute();

            Yii::$app->session->setFlash('success', 'Data imported successfully.');

            // You can redirect the user to another page or render a view here
            return $this->redirect(['index']); // Redirect to index or wherever you want
        } catch (Exception $e) {
            var_dump($e);
          die();
        }
    }

}
