<?php

namespace app\controllers;

use app\helpers\TimeHelper;
use yii;
use yii\helpers\Url;
use yii\web\Response;
use app\models\forms\quest\QuestTaskForm;
use DateTime;
use DateTimeZone;
use app\models\quest\Quest;
use app\models\quest\QuestPassingResult;
use app\models\quest\QuestTask;
use app\models\quest\TaskHint;
use yii\web\NotFoundHttpException;

class QuestController extends SiteController
{
    public $layout = 'quest';

    private $_dateTimeZone;

    public function beforeAction($action)
    {
        $this->_dateTimeZone = new DateTimeZone('Europe/Samara');

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $id = Yii::$app->request->get('id');

        $quest = Quest::findOne(['id' => $id, 'status' => Quest::STATUS_ACTIVE]);

        if (!$quest)
            throw new NotFoundHttpException();

        return $this->render('index', [
            'isGuest' => Yii::$app->user->isGuest,
            'quest' => $quest,
            'hintIntervalString' => TimeHelper::secondsToTimeString($quest->show_hint_interval),
            'questTime' => TimeHelper::secondsToTimeString($quest->max_time * 60),
            'currentPassingResult' => QuestPassingResult::findOne(['team_id' => Yii::$app->user->id, 'quest_id' => $id])
        ]);
    }

    public function actionStart($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->goHome();

        $quest = Quest::findOne(['id' => $id, 'status' => Quest::STATUS_ACTIVE]);

        if (!$quest)
            throw new NotFoundHttpException();

        $currentResult = QuestPassingResult::findOne(['team_id' => Yii::$app->user->id, 'quest_id' => $id]);

        if (!$currentResult) {
            $currentResult = new QuestPassingResult();

            $currentResult->team_id = Yii::$app->user->id;
            $currentResult->quest_id = $id;
            $currentResult->start_datetime = date(QuestPassingResult::DATETIME_DB_FORMAT);
            $currentResult->status = QuestPassingResult::STATUS_IN_PROCESS;
            $currentResult->last_completed_task_number = 0;
            $currentResult->in_rating = true;

            $currentResult->save();
        } elseif ($currentResult->status == QuestPassingResult::STATUS_COMPLETED || $currentResult->status == QuestPassingResult::STATUS_FAILED) {
            return $this->redirect(Url::to(['quest/result', 'id' => $id]));
        }

        return $this->redirect(Url::to(['quest/passing', 'id' => $id]));
    }

    public function actionPassing($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->goHome();

        $quest = Quest::findOne(['id' => $id, 'status' => Quest::STATUS_ACTIVE]);

        if (!$quest)
            throw new NotFoundHttpException();

        $currentResult = QuestPassingResult::findOne(['team_id' => Yii::$app->user->id, 'quest_id' => $id]);

        // команда ещё не начинала прохождение, т.к. не найдены результаты прохождения
        if (!$currentResult)
            return $this->redirect(Url::to(['quest/index', 'id' => $id]));

        if ($currentResult->status == QuestPassingResult::STATUS_COMPLETED || $currentResult->status == QuestPassingResult::STATUS_FAILED) {
            return $this->redirect(Url::to(['quest/result', 'id' => $id]));
        }

        // если время вышло, то квест завершается и отображается страница с результатом
        if ($this->autocompletionQuest($currentResult, $quest))
            return $this->redirect(Url::to(['quest/result', 'id' => $id]));

        $currentTask = QuestTask::findOne(['quest_id' => $id, 'number' => ($currentResult->last_completed_task_number + 1)]);

        if (!$currentTask)
            throw new NotFoundHttpException();

        $lastCompletedTaskDatetime = new DateTime($currentResult->last_completed_task_datetime, $this->_dateTimeZone);
        $hintsIds = TaskHint::findIdsForTask($currentTask->id);

        $startDateTime = new DateTime($currentResult->start_datetime, $this->_dateTimeZone);
        $endDateTime = $startDateTime->modify('+ ' . $quest->max_time . ' minutes');
        $now = new DateTime('now', $this->_dateTimeZone);

        $pastTense = $now->getTimestamp() - $lastCompletedTaskDatetime->getTimestamp();

        $countPastIntervals = floor($pastTense / $quest->show_hint_interval);

        $visibleHints = [];
        if ($countPastIntervals >= count($hintsIds)) {
            $visibleHints = TaskHint::find()->where(['in', 'id', $hintsIds])->all();
        } elseif ($countPastIntervals > 0) {
            $visibleHints = TaskHint::find()->where(['in', 'id', array_slice($hintsIds, 0, $countPastIntervals)])->all();
        }

        $model = new QuestTaskForm();
        $model->questId = $id;

        return $this->render('passing', [
            'quest' => $quest,
            'currentResult' => $currentResult,
            'tasksCount' => QuestTask::find()->where(['quest_id' => $id])->count(),
            'task' => $currentTask,
            'allHints' => TaskHint::findAll(['task_id' => $currentTask->id]),
            'visibleHints' => $visibleHints,
            'countVisibleHints' => count($visibleHints),
            'remainingTime' => 'Оставшееся время: ' . TimeHelper::secondsToTimeString($endDateTime->getTimestamp() - $now->getTimestamp()),
            'model' => $model
        ]);
    }

    public function actionExit($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->goHome();

        $currentResult = QuestPassingResult::findOne(['team_id' => Yii::$app->user->id, 'quest_id' => $id]);

        if (!$currentResult)
            throw new NotFoundHttpException();

        $currentResult->end_datetime = date(QuestPassingResult::DATETIME_DB_FORMAT);
        $currentResult->status = QuestPassingResult::STATUS_FAILED;

        $currentResult->save();

        return $this->redirect(Url::to(['quest/result', 'id' => $id]));
    }

    public function actionRestart($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->goHome();

        $quest = Quest::findOne(['id' => $id, 'status' => Quest::STATUS_ACTIVE]);

        if (!$quest)
            throw new NotFoundHttpException();

        $currentResult = QuestPassingResult::findOne(['team_id' => Yii::$app->user->id, 'quest_id' => $id]);

        if (!$currentResult)
            return $this->redirect(Url::to(['quest/start', 'id' => $id]));

        if ($currentResult->status == QuestPassingResult::STATUS_FAILED) {
            $currentResult->delete();

            $currentResult = new QuestPassingResult();

            $currentResult->team_id = Yii::$app->user->id;
            $currentResult->quest_id = $id;
            $currentResult->status = QuestPassingResult::STATUS_IN_PROCESS;
            $currentResult->last_completed_task_number = 0;
            $currentResult->in_rating = false;

            $currentResult->save();

            return $this->redirect(Url::to(['quest/passing', 'id' => $id]));
        }

        return $this->redirect(Url::to(['quest/result', 'id' => $id]));
    }

    public function actionResult($id)
    {
        if (Yii::$app->user->isGuest)
            return $this->goHome();

        $quest = Quest::findOne(['id' => $id, 'status' => Quest::STATUS_ACTIVE]);

        if (!$quest)
            throw new NotFoundHttpException();

        $result = QuestPassingResult::findOne(['team_id' => Yii::$app->user->id, 'quest_id' => $id]);

        if (!$result)
            throw new NotFoundHttpException();

        // если таймер сделал редирект на эту страницу, то автоматически завершаем квест
        $this->autocompletionQuest($result, $quest);

        $startDateTime = new DateTime($result->start_datetime, $this->_dateTimeZone);

        // квест ещё не завершён и время не истекло, то вернём обратно на страницу прохождения
        if ($result->end_datetime == null)
            return $this->redirect(Url::to(['quest/passing', 'id' => $id]));

        $endDateTime = new DateTime($result->end_datetime, $this->_dateTimeZone);
        $spentedTime = ($endDateTime->diff($startDateTime))->format('%h ч. %i мин. %s сек.');

        return $this->render('result', [
            'result' =>  $result,
            'quest' => $quest,
            'spentedTime' => $spentedTime,
        ]);
    }

    //-----------------------------------------------------------------------------------------------------------------------//
    //--------------------------------------------- Методы, вызываемые асинхронно -------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//

    public function actionCheckAnswer()
    {
        $model = new QuestTaskForm();

        if ($model->load(Yii::$app->request->post())) {
            $canSubmit = true;

            $quest = Quest::findOne($model->questId);
            $lastSubmitDateTime = Yii::$app->session->get('lastSubmitDatetime');
            $now = new DateTime('now', $this->_dateTimeZone);

            if ($lastSubmitDateTime != null) {
                $lastSubmitDateTime->modify('+ ' . $quest->submit_timeout . ' seconds');
                $canSubmit = $lastSubmitDateTime <= $now;
            }

            Yii::$app->response->format = Response::FORMAT_JSON;

            if (!$canSubmit)
                return [
                    'success' => false,
                    'message' => 'Вы пока не можете отправить ответ на проверку.'
                ];

            if ($model->checkAnswer())
                return [
                    'success' => true,
                    'message' => 'Абсолютно верно'
                ];

            Yii::$app->session->set('lastSubmitDatetime', $now);

            return [
                'success' => false,
                'message' => 'Ответ неправильный. Подумайте ещё.'
            ];
        }
    }

    public function actionTimer($id)
    {
        $quest = Quest::findOne($id);
        $currentResult = QuestPassingResult::findOne(['team_id' => Yii::$app->user->id, 'quest_id' => $id]);

        $startDateTime = new DateTime($currentResult->start_datetime, $this->_dateTimeZone);
        $endDateTime = $startDateTime->modify("+ $quest->max_time minutes");

        $now = new DateTime('now', $this->_dateTimeZone);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'remainingTime' => $endDateTime->getTimestamp() - $now->getTimestamp()
        ];
    }

    public function actionGetQuestPassingInfo($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $quest = Quest::findOne($id);
        $currentResult = QuestPassingResult::findOne(['team_id' => Yii::$app->user->id, 'quest_id' => $id]);
        $currentTask = QuestTask::findOne(['quest_id' => $id, 'number' => ($currentResult->last_completed_task_number + 1)]);

        $now = new DateTime('now');
        $lastCompletedTaskDatetime = new DateTime($currentResult->last_completed_task_datetime, $this->_dateTimeZone);
        $hintsIds = TaskHint::findIdsForTask($currentTask->id);

        $pastTense = $now->getTimestamp() - $lastCompletedTaskDatetime->getTimestamp();

        // количество прошедших интервалов (имеются ввиду интервалы между подсказками)
        $countPastIntervals = floor($pastTense / $quest->show_hint_interval);

        $countHints = count($hintsIds);
        $futureHints = [];

        if ($countPastIntervals < $countHints) {
            // id подсказок, которые нужно будет отобразить
            $futureHintsIds = array_slice($hintsIds, $countPastIntervals, $countHints);

            // время до следующей подсказки
            $remainingTime = $quest->show_hint_interval - ($pastTense % $quest->show_hint_interval);

            // ближайшая подсказка
            $futureHints[] = ['id' => $futureHintsIds[0], 'remainingTime' => $remainingTime];

            for ($i = 1; $i < count($futureHintsIds); $i++) {
                $futureHints[] = ['id' => $futureHintsIds[$i], 'remainingTime' => $quest->show_hint_interval];
            }
        }

        return [
            'submitTimeout' => $quest->submit_timeout,
            'futureHints' => $futureHints
        ];
    }

    public function actionShowHint($id)
    {
        $hint = TaskHint::findOne($id);

        return $this->renderPartial('hint', [
            'hint' => $hint
        ]);
    }

    //-----------------------------------------------------------------------------------------------------------------------//
    //--------------------------------------------------  PRIVATE METHODS  --------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------//

    /**
     * Метод для автоматического завершения квеста
     * Этот метод завершит квест, если истекло время прохождения
     * 
     * @param QuestPassingResult $passingResult результат прохождения квеста
     * @param Quest $quest текущий квест
     * @return bool возвращает true, если прохождение было завершено и false в противоположном случае
     */
    private function autocompletionQuest(QuestPassingResult $passingResult, Quest $quest): bool
    {
        if ($passingResult->status == QuestPassingResult::STATUS_IN_PROCESS) {
            $startDateTime = new DateTime($passingResult->start_datetime, $this->_dateTimeZone);
            $endDateTime = $startDateTime->modify('+ ' . $quest->max_time . ' minutes');

            $now = new DateTime('now', $this->_dateTimeZone);

            if ($endDateTime <= $now) {
                $passingResult->end_datetime = $endDateTime->format(QuestPassingResult::DATETIME_DB_FORMAT);
                $passingResult->status = QuestPassingResult::STATUS_FAILED;

                return $passingResult->save();
            }
        }

        return false;
    }
}
