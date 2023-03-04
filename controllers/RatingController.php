<?php

namespace app\controllers;

use app\dtos\RatingResult;
use app\models\quest\Quest;
use app\models\quest\QuestPassingResult;
use Yii;

class RatingController extends SiteController
{
    public $layout = 'rating';

    private $teamsCountLimit = 100;

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest)
            return $this->goHome();

        $quests = Quest::findAll(['status' => Quest::STATUS_ACTIVE]);

        $questIdsNames = [];

        foreach ($quests as $quest) {
            $questIdsNames[$quest->id] = $quest->name;
        }

        return $this->render('index', [
            'quests' => $questIdsNames
        ]);
    }

    public function actionLoadRating($questId)
    {
        $results = QuestPassingResult::findRatingResults($questId, $this->teamsCountLimit);

        /** @var RatingResult[] */
        $rating = [];

        // результат команды безотносительно рейтинга
        $currentTeamResult = QuestPassingResult::findOne([
            'team_id' => Yii::$app->user->id,
            'quest_id' => $questId,
        ]);

        // место в рейтинге
        $currentTeamPosition = null;

        // результат команды относительно рейтинга
        $currentTeamRatingResult = null;

        if ($results) {
            $position = 1;

            $rating[0] = RatingResult::fromArray($results[0], $position);

            if ($rating[0]->teamId == Yii::$app->user->id)
                $currentTeamPosition = $position;

            // заполнение рейтинга и подсчёт занятых мест
            for ($i = 1; $i < count($results); $i++) {
                if ($results[$i - 1]['spented_time'] != $results[$i]['spented_time']) {
                    $position++;
                    if ($position != ($i + 1))
                        $position = $i + 1;
                }

                $rating[$i] = RatingResult::fromArray($results[$i], $position);

                if ($rating[$i]->teamId == Yii::$app->user->id)
                    $currentTeamPosition = $position;
            }

            // команда не попала в выборку первых N команд (т.к. БД берутся записи с лимитом),
            // но при этом команда завершила квест и результат попадает в рейтинг
            if ($currentTeamPosition == null && $currentTeamResult->in_rating && $currentTeamResult->status == QuestPassingResult::STATUS_COMPLETED) {
                $result = QuestPassingResult::findTeamResultInRating(Yii::$app->user->id, $questId);
                if ($result) {
                    $currentTeamPosition = $result['position'];

                    $currentTeamRatingResult = RatingResult::fromArray($result, $currentTeamPosition);
                }
            }
        }

        return $this->renderPartial('rating', [
            'rating' => $rating,
            'currentTeamPosition' => $currentTeamPosition,
            'currentTeamResult' => $currentTeamResult,
            'currentTeamRatingResult' => $currentTeamRatingResult
        ]);
    }
}
