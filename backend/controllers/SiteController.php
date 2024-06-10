<?php

namespace backend\controllers;

use common\models\AdminLoginForm;
use common\models\LoginCas;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
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
                        'actions' => ['login', 'error'],
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
//    public function actionLogin()
//    {
//        if (!\Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }
//
//        $model = new LoginCas();
//        if ($model->login()) {
//
//            $jar = new CookieJar;
//
//            $client = new Client(array(
//                'cookies' => $jar,
//                'verify' => false
//            ));
//
//            $params = Yii::$app->params['mstr'];
//            $url = $params['loginUrl'];
//
//
//            $client->request('POST', $url, [
//                'timeout' => 30,
//                'form_params' => [
//                    'username' => 'view',
//                    'password' => '',
//                    'loginMode' => 1,
//                ],
//                // 'debug' => fopen('php://stderr', 'w')
//            ]);
//
//
//            $jarArray = $jar->toArray();
//
//            if (isset($_COOKIE['iSession'])) {
//                unset($_COOKIE['iSession']);
//                setcookie('iSession', null, -1, '/');
//                // return true;
//                // print_r($_COOKIE['iSession']);
//                // exit;
//            }
//
//            if (isset($_COOKIE['JSESSIONID'])) {
//                unset($_COOKIE['JSESSIONID']);
//                setcookie('JSESSIONID', null, -1, '/');
//            }
//
//            if(isset($jarArray[0])) {
//                setcookie($jarArray[0]['Name'], $jarArray[0]['Value'], time() + (86400 * 30), $jarArray[0]['Path'], $jarArray[0]['Domain'], 0);
//            }
//            if(isset($jarArray[1])) {
//                setcookie($jarArray[1]['Name'], $jarArray[1]['Value'], time() + (86400 * 30), $jarArray[1]['Path'], $jarArray[1]['Domain'], 0);
//            }
//
//            return $this->goBack();
//        } else {
//            return $this->render('login', [
//                'model' => $model,
//            ]);
//        }
//    }
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new AdminLoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
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