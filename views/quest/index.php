<?php

use app\models\quest\QuestPassingResult;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::$app->name . ': квест "' . $quest->name . '"';
?>

<div class="quest-start">
    <img class="quest-image" src="../web/images/quests/<?= Html::encode($quest->filename) ?>" />
    <div class="quest-info">
        <h4><?= Html::encode($quest->name) ?></h4>

        <div class="quest-properties">
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon bi bi-question-diamond-fill" viewBox="0 0 16 16">
                    <path d="M9.05.435c-.58-.58-1.52-.58-2.1 0L.436 6.95c-.58.58-.58 1.519 0 2.098l6.516 6.516c.58.58 1.519.58 2.098 0l6.516-6.516c.58-.58.58-1.519 0-2.098L9.05.435zM5.495 6.033a.237.237 0 0 1-.24-.247C5.35 4.091 6.737 3.5 8.005 3.5c1.396 0 2.672.73 2.672 2.24 0 1.08-.635 1.594-1.244 2.057-.737.559-1.01.768-1.01 1.486v.105a.25.25 0 0 1-.25.25h-.81a.25.25 0 0 1-.25-.246l-.004-.217c-.038-.927.495-1.498 1.168-1.987.59-.444.965-.736.965-1.371 0-.825-.628-1.168-1.314-1.168-.803 0-1.253.478-1.342 1.134-.018.137-.128.25-.266.25h-.825zm2.325 6.443c-.584 0-1.009-.394-1.009-.927 0-.552.425-.94 1.01-.94.609 0 1.028.388 1.028.94 0 .533-.42.927-1.029.927z" />
                </svg>
                <?= Html::encode($quest->type) ?> ~ <?= Html::encode($quest->distance) ?> км.
            </p>
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon bi bi-people-fill" viewBox="0 0 16 16">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                    <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z" />
                    <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z" />
                </svg>
                <?php if ($quest->min_count_of_persons == $quest->max_count_of_persons) : ?>
                    <?= Html::encode($quest->max_count_of_persons) ?>
                <?php else : ?>
                    <?= Html::encode($quest->min_count_of_persons) ?>-<?= Html::encode($quest->max_count_of_persons) ?>
                <?php endif; ?>
                <?php echo Yii::$app->i18n->messageFormatter->format(
                    '{n, plural, one{человек} few{человека} many{человек} other{человека}}',
                    ['n' => $quest->max_count_of_persons],
                    Yii::$app->language
                ); ?>
            </p>
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon bi bi-stopwatch" viewBox="0 0 16 16">
                    <path d="M8.5 5.6a.5.5 0 1 0-1 0v2.9h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5V5.6z" />
                    <path d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64a.715.715 0 0 1 .012-.013l.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354a.512.512 0 0 1-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5zM8 3a6 6 0 1 0 .001 12A6 6 0 0 0 8 3z" />
                </svg>
                Ограничение по времени: <?= Html::encode($questTime) ?>
            </p>
        </div>

        <p><?= nl2br($quest->description) ?></p>

        <?php if (!$currentPassingResult && $hintIntervalString != '') : ?>
            <div class="alert alert-warning">
                <strong>В рейтинге учитывается только первая попытка!</strong>
                <br>
                Обращаем ваше внимание на то, что подсказки будут показываться вам раз в <?= $hintIntervalString ?>
                <br>
                Кнопки для просмотра подсказок будут находиться в правой верхней части формы.
            </div>
        <?php endif; ?>
        <div class="submit-wrap">
            <?php if ($currentPassingResult) : ?>
                <?php if ($currentPassingResult->status == QuestPassingResult::STATUS_IN_PROCESS) : ?>
                    <a class="btn btn-primary" href="<?= Url::toRoute(['quest/start', 'id' => $quest->id]) ?>">
                        Продолжить прохождение
                    </a>
                <?php else : ?>
                    <a class="btn btn-secondary" href="<?= Url::toRoute(['quest/result', 'id' => $quest->id]) ?>">
                        Посмотреть результат
                    </a>
                <?php endif; ?>
            <?php else : ?>
                <?php if ($isGuest) : ?>
                    <a class="btn btn-success" href="<?= Url::toRoute(['authentication/login']) ?>">
                        Авторизоваться
                    </a>
                <?php else : ?>
                    <a class="btn btn-primary confirm-link" href="<?= Url::toRoute(['quest/start', 'id' => $quest->id]) ?>" data-message="Вы действительно готовы начать прохождение? Обращаем ваше внимание на то, что время квеста ограничено">
                        Начать прохождение
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>