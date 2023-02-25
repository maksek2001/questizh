<?php

namespace app\dtos;

use app\helpers\TimeHelper;

class RatingResult
{
    /** @var int */
    public $position;

    /** @var int */
    public $teamId;

    /** @var string */
    public $teamName;

    /** 
     * @var string затраченное время в виде строки 
     * (сортировка таких объектов не будет требоваться и для вывода удобнее использовать строку) 
     */
    public $spentedTime;

    /**
     * Создание объекта "Результат рейтинга"
     * 
     * @param array $resultsArray результат в виде массива
     * @param int $positionInRating позиция в рейтинге
     */
    public static function fromArray(array $result, int $positionInRating): RatingResult
    {
        $ratingResult = new RatingResult();

        $ratingResult->position = $positionInRating;
        $ratingResult->teamId = $result['team_id'];
        $ratingResult->teamName = $result['team_name'];
        $ratingResult->spentedTime = TimeHelper::secondsToTimeString($result['spented_time']);

        return $ratingResult;
    }
}
