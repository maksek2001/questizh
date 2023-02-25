<?php

namespace app\models\quest;

/**
 * Задание квеста
 * 
 * @property int $id
 * @property int $quest_id
 * @property int $number
 * @property string $place_description
 * @property string $question
 * @property string $correct_answer
 * 
 */
class QuestTask extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{quest_tasks}}';
    }
}
