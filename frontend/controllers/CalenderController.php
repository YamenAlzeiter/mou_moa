<?php

namespace frontend\controllers;

use common\components\CustomEvent;
use common\models\Agreement;


use common\models\McomDate;
use common\models\search\AgreementSearch;
use Yii;
use yii\base\Event;
use yii\db\Expression;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii2fullcalendar\models\Event as BaseEvent;


/**
 * Site controller
 */
class CalenderController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index',
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
        $userType = Yii::$app->user->identity->type;

        $searchModel = new AgreementSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->select(['agreement.mcom_date', 'agreement.id']);
        $dataProvider->query->joinWith(['agreementPoc']);

        $dataProvider->query->leftJoin('mcom_date', 'agreement.mcom_date = DATE(mcom_date.date_from)');
        $dataProvider->query->andWhere(['agreement_poc.pi_kcdio' => $userType]);

        $models = $dataProvider->getModels();
        $events = [];

        foreach ($models as $model) {
            $model->hasMatchingMcomDate = false;
            $matchingMcomDateRecord = null;

            foreach ($model->agreementPoc as $agreementPoc) {

                $matchingMcomDate = (new \yii\db\Query())
                    ->select('*')
                    ->from('mcom_date')
                    ->where(['=', new \yii\db\Expression('DATE(mcom_date.date_from)'), $model->mcom_date])
                    ->one();

                if ($matchingMcomDate) {
                    $model->hasMatchingMcomDate = true;
                    $matchingMcomDateRecord = $matchingMcomDate;
                    break;
                }
            }

            if ($model->hasMatchingMcomDate && $matchingMcomDateRecord) {
                $event = new CustomEvent();
                $event->id = 'view_'.$model->id;
                $event->editable = false;
                $event->title = 'Agreement ID: ' . $model->id;
                $event->start = $matchingMcomDateRecord['date_from'];
                $event->end = $matchingMcomDateRecord['date_until'];
                $event->description = 'PI Name: ' . $agreementPoc->pi_name . ', PI Email: ' . $agreementPoc->pi_email;
                $event->className = 'task-crayon';
                $events[] = $event;
            }
        }

        return $this->render('index', [
            'events' => $events,
            'model' => $model,

        ]);
    }
}
