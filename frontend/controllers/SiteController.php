<?php

namespace frontend\controllers;

use common\models\search\AgreementSearch;
use common\models\User;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\SignupForm;
use frontend\models\VerifyEmailForm;
use phpCAS;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\ContactForm;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['cas-login','error', 'index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if(!Yii::$app->user->isGuest){
            $this->redirect('agreement/index');
        }else{
            $this->layout ='blank';
            return $this->render('index');
        }

    }
    /**
     * Lists all Agreement models.
     *
     * @return string
     * @return Response
     */
    public function actionPublicIndex()
    {
        $this->layout ='blank';
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
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionCasLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $casParams = Yii::$app->params['cas'];
        phpCAS::setLogger();
        phpCAS::setVerbose(true);
        phpCAS::client(SAML_VERSION_1_1, $casParams['host'], $casParams['port'], $casParams['casContext'], $casParams['clientServiceNameForApplicant']);
        if (!empty($casParams['casServerSslCert'])) {
            phpCAS::setCasServerCACert($casParams['casServerSslCert']);
        } else {
            phpCAS::setNoCasServerValidation();
        }

        phpCAS::handleLogoutRequests(true, $casParams['casRealHost']);
        phpCAS::forceAuthentication();

        $username = phpCAS::getUser();
        $email = isset(phpCAS::getAttributes()['mail']) ? phpCAS::getAttributes()['mail'] : '';
        $defaultGroup = isset(phpCAS::getAttributes()['defaultgroup']) ? phpCAS::getAttributes()['defaultgroup'] : '';
//        var_dump(phpCAS::getAttributes());
//        die();
//        if (strpos($defaultGroup, 'stud') !== false) {
//            throw new ForbiddenHttpException('You do not have permission to access this page.');
//        }
        $findUser = User::findOne(['email' => $email]);

        if ($findUser === null) {
            $newUser = new User();
            $newUser->username = $username;
            $newUser->status = User::STATUS_ACTIVE;
            $newUser->auth_key = Yii::$app->security->generateRandomString();
            $newUser->email = $email;
            $newUser->type = isset(phpCAS::getAttributes()['kcdi']) ? phpCAS::getAttributes()['kcdi'] : '';
            $newUser->save();
            Yii::$app->user->login($newUser, 3600 * 24 * 30);
        } else {
            Yii::$app->user->login($findUser, 3600 * 24 * 30);
        }

        return $this->goBack();
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
//    public function actionContact()
//    {
//        $model = new ContactForm();
//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
//                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
//            } else {
//                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
//            }
//
//            return $this->refresh();
//        }
//
//        return $this->render('contact', [
//            'model' => $model,
//        ]);
//    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
//    public function actionAbout()
//    {
//        return $this->render('about');
//    }
    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
