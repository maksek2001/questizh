<?php

use yii\helpers\Html;
use app\models\quest\QuestPassingResult;
use yii\helpers\Url;

$this->title = Yii::$app->name;
?>

<div class="quest-result">
    <img class="result-image" src="../web/images/quests/<?= $quest->filename ?>" />
    <div class="result-info">
        <?php if ($result->status == QuestPassingResult::STATUS_COMPLETED) : ?>
            <h5 class="success">
                Поздравляем вас с успешным завершением квеста
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon-large bi bi-check-lg" viewBox="0 0 16 16">
                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z" />
                </svg>
            </h5>
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon bi bi-stopwatch" viewBox="0 0 16 16">
                    <path d="M8.5 5.6a.5.5 0 1 0-1 0v2.9h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5V5.6z" />
                    <path d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64a.715.715 0 0 1 .012-.013l.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354a.512.512 0 0 1-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5zM8 3a6 6 0 1 0 .001 12A6 6 0 0 0 8 3z" />
                </svg>
                Время прохождения квеста: <?= $spentedTime ? Html::encode($spentedTime) : '' ?>
            </p>

            <?php if ($result->in_rating) : ?>
                <p>Теперь вы можете узнать своё место в рейтинге команд по данному квесту</p>
                <p>Также результат этого квеста появился в вашем личном кабинете</p>
            <?php else : ?>
                <p>Результат этого квеста появился в вашем личном кабинете</p>
            <?php endif; ?>

            <p>Мы надеемся, что этот квест был интересным для вас.</p>
            <div class="quest-result-footer">
                <a class="btn btn-secondary" href="<?= Url::home() ?>">Вернуться на главную</a>
            </div>
        <?php else : ?>
            <h5 class="fail">К сожалению вам не удалось пройти квест</h5>
            <p>Не расстраивайтесь. Вы можете начать прохождение квеста заново.</p>
            <p>
                <strong>Важно!</strong> Новая попытка не будет учитываться в рейтинге, а старая попытка будет удалена.
            </p>
            <p>
                Если у вас нет желания проходить этот квест, вы можете выбрать любой доступный квест на главной странице и пройти его.
            </p>
            <p>Желаем вам удачи в следующих квестах!</p>
            <div class="quest-result-footer">
                <a class="btn btn-secondary" href="<?= Url::home() ?>">Вернуться на главную</a>
                <a class="btn btn-primary confirm-link" data-message="Вы действительно хотите начать прохождение заново? В этом случае ваша старая попытка будет перезаписана" href="<?= Url::toRoute(['quest/restart', 'id' => $quest->id]) ?>">Начать заново</a>
            </div>
        <?php endif; ?>
    </div>
</div>