<?php

namespace app\controllers;

use app\helpers\TimeHelper;
use DateTime;
use DateTimeZone;
use app\models\quest\QuestPassingResult;
use app\models\Team;
use yii\bootstrap5\ActiveForm;
use yii\web\Response;
use app\models\forms\office\TeamInfoForm;
use yii;

class OfficeController extends SiteController
{
    public $layout = 'office';

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest)
            return $this->goHome();

        $team = Team::findOne(Yii::$app->user->id);
        $model = new TeamInfoForm();

        $model->email = $team->email;
        $model->name = $team->name;

        $passingResults = QuestPassingResult::findTeamResults(Yii::$app->user->id);

        $dateTimeZone = new DateTimeZone('Europe/Samara');
        $now = new DateTime('now', $dateTimeZone);
        foreach ($passingResults as &$result) {
            $startDateTime = new DateTime($result['start_datetime'], $dateTimeZone);

            if ($result['status'] == QuestPassingResult::STATUS_IN_PROCESS) {
                $endDateTime = ($startDateTime)->modify('+ ' .  $result['quest_max_time'] . ' minutes');

                // если время квеста вышло, то завершаем его
                if ($endDateTime <= $now) {
                    $currentResult = QuestPassingResult::findOne($result['id']);

                    $currentResult->end_datetime = $endDateTime->format(QuestPassingResult::DATETIME_DB_FORMAT);
                    $result['end_datetime'] = $currentResult->end_datetime;

                    $currentResult->status = QuestPassingResult::STATUS_FAILED;
                    $result['status'] = QuestPassingResult::STATUS_FAILED;

                    $currentResult->save();
                }
            }

            if ($result['end_datetime']) {
                $endDateTime = new DateTime($result['end_datetime'], $dateTimeZone);
                $result['spented_time'] = 'Затраченное время: ' . TimeHelper::secondsToTimeString($endDateTime->getTimestamp() - $startDateTime->getTimestamp());
            }
        }

        return $this->render('index', [
            'team' => $team,
            'model' => $model,
            'passingResults' => $passingResults
        ]);
    }

    public function actionSaveInformation()
    {
        $model = new TeamInfoForm();

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => $model->updateInfo()];
        }
    }

    public function actionValidateInformation()
    {
        $model = new TeamInfoForm();
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
}
