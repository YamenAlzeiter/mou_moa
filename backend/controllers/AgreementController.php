<?php

namespace backend\controllers;

use common\models\admin;
use common\models\Agreement;
use common\models\EmailTemplate;
use common\models\search\AgreementSearch;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
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
        if(!Yii::$app->user->isGuest){
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
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

            if ($model->save()){
                $this->fileHandler($model, 'olaDraft', 'draft', 'doc_draft');
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
}
