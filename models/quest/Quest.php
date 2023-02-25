<?php

namespace app\models\quest;

/**
 * Квест
 * 
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $min_count_of_persons
 * @property int $max_count_of_persons
 * @property float $distance
 * @property string $description
 * @property string $short_description
 * @property int $max_time максимальное время прохождения в минутах
 * @property string $status
 * @property int $show_hint_interval интервал между подсказками в секундах (не должен быть равен 0)
 * @property int $submit_timeout минимальное время между отправкой ответов в секундах (не должен быть равен 0)
 * @property string $filename
 * 
 */
class Quest extends \yii\db\ActiveRecord
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_SOON = 'soon';
    public const STATUS_INACTIVE = 'inactive';

    public static function tableName()
    {
        return '{{quests}}';
    }
}
