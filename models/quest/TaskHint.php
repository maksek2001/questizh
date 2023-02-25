<?php

namespace app\models\quest;

/**
 * Подсказки для заданий квеста
 * 
 * @property int $id
 * @property int $task_id
 * @property int $number
 * @property string $text
 * @property string $image_filename
 * 
 */
class TaskHint extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{task_hints}}';
    }

    public static function findIdsForTask(int $taskId): array
    {
        $queryResult = static::find()
            ->select('id')
            ->where(['task_id' => $taskId])
            ->orderBy(['number' => SORT_ASC])
            ->asArray()
            ->all();

        $ids = [];

        foreach ($queryResult as $row) {
            $ids[] = $row['id'];
        }

        return $ids;
    }
}
