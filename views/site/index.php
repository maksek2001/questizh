<?php

use app\models\quest\Quest;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::$app->name;
?>

<div class="main-head-content">
    <div class="main-image">
        <div class="main-text">
            <h3><strong>Городские квесты в Ижевске</strong></h3>
            <p>QuestIzh - это настоящее приключение для тебя или дружеской компании!
                Это городская игра-прогулка, в которой гидом является — твой смартфон.</p>

            <p>Узнай город ближе, выполняя задания квеста!</p>
            <p>Разгадывайте интересные загадки!</p>
            <p>Узнавайте по пути культуру, историю и факты!</p>
            <p>Попробуй свои силы в городском интеллектуальном состязании!</p>
            <p>Собирай команду друзей и врывайся в мир приключений!</p>

            <p>Хочешь учавствовать? Тогда выбирай квест и начинай прохождение в любое удобное для тебя время!</p>

            <h4>До встречи на играх!</h4>
        </div>
    </div>
</div>

<div class="active-quests">
    <h3><strong>Городские квесты</strong></h3>
    <div>
        <ul class="row grid list-unstyled">
            <?php foreach ($quests as $quest) : ?>
                <li class="quest col-sm-12 col-md-6 col-xl-6">
                    <?php if ($quest->status == Quest::STATUS_SOON) : ?>
                        <div class="flag flag-fill-red right soon">
                            <div class="flag-image"></div>
                            <span class="flag-text">Скоро</span>
                        </div>
                    <?php endif; ?>

                    <?php if ($quest->status == Quest::STATUS_ACTIVE) : ?>
                        <a href="<?= Url::toRoute(['quest/index', 'id' => $quest->id]) ?>">
                            <div class="quest-head">
                                <img src="../web/images/quests/<?= Html::encode($quest->filename) ?>" />
                                <div class="short-info">
                                    <?= Html::encode($quest->short_description) ?>
                                </div>
                            </div>
                        </a>
                    <?php else : ?>
                        <div class="quest-head">
                            <img src="../web/images/quests/<?= Html::encode($quest->filename) ?>" />
                            <div class="short-info">
                                <?= Html::encode($quest->short_description) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="quest-footer">
                        <h4 class="quest-name"><?= Html::encode($quest->name) ?></h4>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon-medium bi bi-question-diamond-fill" viewBox="0 0 16 16">
                            <path d="M9.05.435c-.58-.58-1.52-.58-2.1 0L.436 6.95c-.58.58-.58 1.519 0 2.098l6.516 6.516c.58.58 1.519.58 2.098 0l6.516-6.516c.58-.58.58-1.519 0-2.098L9.05.435zM5.495 6.033a.237.237 0 0 1-.24-.247C5.35 4.091 6.737 3.5 8.005 3.5c1.396 0 2.672.73 2.672 2.24 0 1.08-.635 1.594-1.244 2.057-.737.559-1.01.768-1.01 1.486v.105a.25.25 0 0 1-.25.25h-.81a.25.25 0 0 1-.25-.246l-.004-.217c-.038-.927.495-1.498 1.168-1.987.59-.444.965-.736.965-1.371 0-.825-.628-1.168-1.314-1.168-.803 0-1.253.478-1.342 1.134-.018.137-.128.25-.266.25h-.825zm2.325 6.443c-.584 0-1.009-.394-1.009-.927 0-.552.425-.94 1.01-.94.609 0 1.028.388 1.028.94 0 .533-.42.927-1.029.927z" />
                        </svg>
                        <?= Html::encode($quest->type) ?> ~ <?= Html::encode($quest->distance) ?> км.
                        <br>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon-medium bi bi-people-fill" viewBox="0 0 16 16">
                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                            <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z" />
                            <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z" />
                        </svg>

                        <?php if ($quest->min_count_of_persons == $quest->max_count_of_persons) : ?>
                            <?= Yii::$app->inflection->pluralize($quest->max_count_of_persons, 'человек') ?>
                        <?php else : ?>
                            <?= Html::encode($quest->min_count_of_persons) ?> - <?= Yii::$app->inflection->pluralize($quest->max_count_of_persons, 'человек') ?>
                        <?php endif; ?>

                        <?php if ($quest->status == Quest::STATUS_ACTIVE) : ?>
                            <div class="quest-submit-wrap">
                                <a class="btn btn-primary btn-rounded view-quest" href="<?= Url::toRoute(['quest/index', 'id' => $quest->id]) ?>">
                                    Пройти
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>