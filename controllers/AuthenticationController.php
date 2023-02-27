<?php

namespace app\controllers;

use Yii;
use app\models\forms\authentication\LoginForm;
use yii\helpers\Url;
use app\models\forms\authentication\SignupForm;

class AuthenticationController extends SiteController
{
    public $layout = 'authentication';

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest)
            return $this->goHome();

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                return $this->redirect(['office/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Введён неверный логин или пароль!');
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest)
            return $this->goHome();

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->signup()) {
                Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались!');
                return $this->redirect('login');
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось зарегистрироваться!');
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
}
