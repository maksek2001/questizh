<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::$app->name;
?>

<div class="quest-passing">
    <div class="exit-block">
        <a class="exit btn btn-danger" href="<?= Url::toRoute(['quest/exit', 'id' => $quest->id]) ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon bi bi-x-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
            </svg>
            Завершить
        </a>
    </div>
    <div class="alert alert-warning" id="timer">
        <?= Html::encode($remainingTime) ?>
    </div>
    <div class="quest-form-block">
        <ol class="progress-points">
            <?php for ($i = 1; $i <= $tasksCount; $i++) : ?>
                <?php if ($i < $task->number) : ?>
                    <li class="completed"><span>Задание <?= Html::encode($i) ?></span></li>
                <?php elseif ($i == $task->number) : ?>
                    <li class="active hovered"><span>Задание <?= Html::encode($i) ?></span></li>
                <?php else : ?>
                    <li><span>Задание <?= Html::encode($i) ?></span></li>
                <?php endif; ?>
            <?php endfor; ?>
        </ol>
        <div class="hints-selection">
            <?php foreach ($allHints as $hint) : ?>
                <?php $class = ($hint->number <= $countVisibleHints) ? 'available' : 'not-available'; ?>
                <a id="hint-link-<?= Html::encode($hint->id) ?>" class="hint-link <?= Html::encode($class) ?>" data-id="<?= Html::encode($hint->id) ?>" <?= ($class == 'not-available') ? "data-text='Эта подсказка пока недоступна'" : '' ?>>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon bi bi-info-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                    </svg>
                </a>
            <?php endforeach; ?>
            <div id="hints-block" class="hints-block">
                <?php if ($visibleHints) : ?>
                    <?php foreach ($visibleHints as $visibleHint) : ?>
                        <?= $this->render('hint.php', ['hint' => $visibleHint]); ?>
                    <?php endforeach ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="message"></div>
        <div class="task">
            <label>Описание места</label>
            <p>
                <?= Html::encode($task->place_description) ?>
            </p>
            <label>Вопрос</label>
            <p>
                <?= Html::encode($task->question) ?>
            </p>
        </div>
        <?php $form = ActiveForm::begin([
            'action' => ['quest/check-answer'],
            'id' => 'quest-task-form',
            'options' => [
                'class' => 'justify-content-center',
            ],
            'method' => 'post',
        ]); ?>

        <div class="d-none">
            <?= $form->field($model, 'questId')->textInput() ?>
        </div>

        <?= $form->field($model, 'answer')->textInput(['autocomplete' => 'off']) ?>

        <div class="submit-wrap">
            <?= Html::submitButton('Отправить ответ', ['class' => 'btn btn-primary', 'id' => 'check-answer']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$this->registerJsFile(
    '@web/js/quest.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>