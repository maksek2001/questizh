<?php

namespace app\models\quest;

/**
 * Информация о прохождении квеста
 * 
 * @property int $id
 * @property int $team_id
 * @property int $quest_id
 * @property string $start_datetime
 * @property string $end_datetime
 * @property string $status
 * @property int $last_completed_task_number
 * @property string $last_completed_task_datetime
 * @property bool $in_rating
 * 
 */
class QuestPassingResult extends \yii\db\ActiveRecord
{
    public const DATETIME_DB_FORMAT = 'Y-m-d H:i:s';

    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_IN_PROCESS = 'in_process';

    public const STATUSES_MESSAGES = [
        self::STATUS_COMPLETED => [
            'status' => 'Пройден',
            'message' => 'Вы успешно прошли данный квест!'
        ],
        self::STATUS_IN_PROCESS => [
            'status' => 'В процессе',
            'message' => 'Прохождение квеста ещё не закончено, вы можете продолжить прохождение',
        ],
        self::STATUS_FAILED => [
            'status' => 'Провален',
            'message' => 'Вам не удалось пройти квест. Вы можете попробовать снова'
        ],
    ];

    public static function tableName()
    {
        return '{{quest_passing_results}}';
    }

    public static function findRatingResults(int $questId, int $limit): array
    {
        $sql = "SELECT 
                    result.quest_id,
                    result.team_id,
                    teams.name team_name,
                    result.status,
                    TIMESTAMPDIFF(SECOND, result.start_datetime, result.end_datetime) spented_time
                FROM quest_passing_results result
                INNER JOIN teams
                    ON teams.id = result.team_id
                WHERE result.quest_id = :questId AND result.status = :resultStatus AND result.in_rating = :inRating
                ORDER BY spented_time ASC
                LIMIT :limitRating";

        return static::findBySql($sql, [
            ':questId' => $questId,
            ':limitRating' => $limit,
            ':resultStatus' => self::STATUS_COMPLETED,
            ':inRating' => 1
        ])->asArray()->all();
    }

    /**
     * Поиск результата команды, который учитывается в рейтинге с поиском текущего места в рейтинге
     */
    public static function findTeamResultInRating(int $teamId, int $questId): array
    {
        $sql = "SELECT 
                    result.quest_id,
                    result.team_id,
                    teams.name team_name,
                    result.status,
                    TIMESTAMPDIFF(SECOND, result.start_datetime, result.end_datetime) spented_time,
                    ROW_NUMBER() OVER(ORDER BY spented_time ASC) position
                FROM quest_passing_results result
                INNER JOIN teams
                    ON teams.id = result.team_id
                WHERE result.quest_id = :questId 
                  AND result.status = :resultStatus 
                  AND result.in_rating = :inRating";

        $queryResult = static::findBySql($sql, [
            ':questId' => $questId,
            ':resultStatus' => self::STATUS_COMPLETED,
            ':inRating' => 1
        ])->asArray()->all();

        $key = array_search($teamId, array_column($queryResult, 'team_id'));

        return $queryResult[$key];
    }

    public static function findTeamResults(int $teamId): array
    {
        $sql = "SELECT quests.name quest_name, quests.max_time quest_max_time, results.*
                FROM quest_passing_results results
                INNER JOIN quests
                    ON results.quest_id = quests.id
                WHERE results.team_id = :teamId";

        return static::findBySql($sql, [
            ':teamId' => $teamId
        ])->asArray()->all();
    }
}
