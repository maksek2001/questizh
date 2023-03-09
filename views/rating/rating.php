<?php

use app\models\quest\QuestPassingResult;
use yii\helpers\Html;

const POSITIONS_MEDALS = [
    1 => 'gold',
    2 => 'silver',
    3 => 'bronze'
];

?>

<?php if ($rating) : ?>
    <?php if ($currentTeamPosition) : ?>
        <div class="current-position">
            Ваше место: <?= Html::encode($currentTeamPosition) ?>
        </div>
    <?php endif; ?>
    <div class="help-links-block mb-2 p-1">
        <?php if (!$currentTeamPosition) : ?>
            <a class="help-link" data-type="out-of-rating">
                Почему нашей команды нет в рейтинге?
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
                </svg>
            </a>
        <?php endif; ?>
    </div>
    <div class="help mb-1">
        <?php if ($currentTeamResult) : ?>
            <div class="alert out-of-rating alert-warning text-center">
                Вашей команды нет в данном рейтинге,
                <?php if ($currentTeamResult->status == QuestPassingResult::STATUS_COMPLETED) : ?>
                    потому что вы не прошли квест с первой попытки
                <?php else : ?>
                    потому что вы не прошли этот квест
                <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="alert out-of-rating alert-warning text-center">Ваша команда не проходила данный квест</div>
        <?php endif; ?>
    </div>
    <table class="table table-dark results-table table-striped sticky-header">
        <thead>
            <tr>
                <th scope="col">Место</th>
                <th scope="col">Название команды</th>
                <th scope="col">Время прохождения</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < count($rating) && $i < 3; $i++) : ?>
                <tr class="<?= ($rating[$i]->position == $currentTeamPosition) ? 'current-team' : '' ?>">
                    <th scope="row">
                        <em class="medal medal-<?= Html::encode(POSITIONS_MEDALS[$rating[$i]->position]) ?>"></em>
                    </th>
                    <td><?= Html::encode($rating[$i]->teamName) ?></td>
                    <td><?= Html::encode($rating[$i]->spentedTime) ?></td>
                </tr>
            <?php endfor; ?>
            <?php for ($i = 3; $i < count($rating); $i++) : ?>
                <tr class="<?= ($rating[$i]->position == $currentTeamPosition) ? 'current-team' : '' ?>">
                    <th scope="row"><span><?= Html::encode($rating[$i]->position) ?></span></th>
                    <td><?= Html::encode($rating[$i]->teamName) ?></td>
                    <td><?= Html::encode($rating[$i]->spentedTime) ?></td>
                </tr>
            <?php endfor; ?>
            <?php if ($currentTeamPosition > count($rating)) : ?>
                <tr class="table-spacer">
                    <th scope="row">...</th>
                    <td>...</td>
                    <td>...</td>
                </tr>
                <tr class="current-team out-of-rating">
                    <th scope="row"><span><?= Html::encode($currentTeamRatingResult->position) ?></span></th>
                    <td><?= Html::encode($currentTeamRatingResult->teamName) ?></td>
                    <td><?= Html::encode($currentTeamRatingResult->spentedTime) ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php else : ?>
    <div class="alert alert-warning">
        Список участников данного квеста пуст
    </div>
<?php endif; ?>