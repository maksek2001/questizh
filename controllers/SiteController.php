<?php

namespace app\controllers;

use app\models\quest\Quest;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SiteController extends Controller
{

    public $layout = 'main';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
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

    public function beforeAction($action)
    {
        date_default_timezone_set('Europe/Samara');

        Yii::$app->name = 'QuestIzh';

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $quests = Quest::find()->where(['status' => Quest::STATUS_ACTIVE])
            ->orWhere(['status' => Quest::STATUS_SOON])
            ->all();

        return $this->render('index', [
            'quests' => $quests
        ]);
    }
    
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            if ($exception->statusCode == 404)
                return $this->render('error-404', ['exception' => $exception]);
            else
                return $this->render('error', ['exception' => $exception]);
        }
    }
}
