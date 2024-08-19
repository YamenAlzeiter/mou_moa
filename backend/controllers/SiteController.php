<?php

namespace backend\controllers;

use common\models\User;
use phpCAS;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
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
                        'actions' => ['cas-login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string|Response
     */

//    public function actionCasLogin()
//    {
//        if (!Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//        $casParams = Yii::$app->params['cas'];
//        phpCAS::setLogger();
//        phpCAS::setVerbose(true);
//        phpCAS::client(SAML_VERSION_1_1, $casParams['host'], $casParams['port'], $casParams['casContext'], $casParams['clientServiceName']);
//        if (!empty($casParams['casServerSslCert']))
//            phpCAS::setCasServerCACert($casParams['casServerSslCert']);
//        else
//            phpCAS::setNoCasServerValidation();
//
//        phpCAS::handleLogoutRequests(true, $casParams['casRealHost']);
//        phpCAS::forceAuthentication();
//        $findUser = User::findByUsername(phpCAS::getUser());
//        if ($findUser === null) {
//            $newUser = new User();
//            $newUser->username = phpCAS::getUser();
//            $newUser->status = User::STATUS_ACTIVE;
//            $newUser->auth_key = Yii::$app->security->generateRandomString();
//            $newUser->email = isset(phpCAS::getAttributes()['mail']) ? phpCAS::getAttributes()['mail'] : '';
//            $newUser->save();
//            Yii::$app->user->login($newUser, 3600 * 24 * 30);
//        } else {
//            Yii::$app->user->login($findUser, 3600 * 24 * 30);
//        }
//
//        return $this->goBack();
//    }

    public function actionCasLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $casParams = Yii::$app->params['cas'];
        phpCAS::setLogger();
        phpCAS::setVerbose(true);
        phpCAS::client(SAML_VERSION_1_1, $casParams['host'], $casParams['port'], $casParams['casContext'], $casParams['clientServiceName']);
        if (!empty($casParams['casServerSslCert'])) {
            phpCAS::setCasServerCACert($casParams['casServerSslCert']);
        } else {
            phpCAS::setNoCasServerValidation();
        }

        phpCAS::handleLogoutRequests(true, $casParams['casRealHost']);
        phpCAS::forceAuthentication();

        $username = phpCAS::getUser();
        $email = isset(phpCAS::getAttributes()['mail']) ? phpCAS::getAttributes()['mail'] : '';
        $userType = isset(phpCas::getAttributes()['defaultgroup']) ? phpCas::getAttributes()['defaultgroup'] : '';
//        var_dump(phpCAS::getAttributes());
//        die();
//        if ($userType === 'STUDENT:UI:') {
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
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}