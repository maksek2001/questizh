<?php

namespace app\models\forms\quest;

use Yii;
use app\models\quest\QuestPassingResult;
use app\models\quest\QuestTask;
use yii\base\Model;

class QuestTaskForm extends Model
{
    /** @var int */
    public $questId;

    /** @var string */
    public $answer;

    public function attributeLabels()
    {
        return [
            'questId' => '',
            'answer' => 'Ваш ответ'
        ];
    }

    public function rules()
    {
        return [
            [['questId', 'answer'], 'required', 'message' => 'Обязательное поле!']
        ];
    }

    public function checkAnswer(): bool
    {
        if (!$this->validate())
            return false;

        $currentResults = QuestPassingResult::findOne(['team_id' => Yii::$app->user->id, 'quest_id' => $this->questId]);

        $currentTask = QuestTask::findOne(['quest_id' => $this->questId, 'number' => ($currentResults->last_completed_task_number + 1)]);

        if ($this->answer != $currentTask->correct_answer)
            return false;

        $currentResults->last_completed_task_number += 1;
        $currentResults->last_completed_task_datetime = date(QuestPassingResult::DATETIME_DB_FORMAT);

        if ($currentResults->last_completed_task_number == QuestTask::find()->where(['quest_id' => $this->questId])->count()) {
            $currentResults->status = QuestPassingResult::STATUS_COMPLETED;
            $currentResults->end_datetime = $currentResults->last_completed_task_datetime;
        }

        $currentResults->save();

        return true;
    }
}
