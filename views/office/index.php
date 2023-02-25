<?php

use app\models\quest\QuestPassingResult;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Личный кабинет " . Yii::$app->name;
?>

<div class="office-container">
    <div class="team-information">
        <div class="panel-info">
            <div class="team-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon-team bi bi-people-fill" viewBox="0 0 16 16">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                    <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z" />
                    <path d="M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z" />
                </svg>
                <div class="info">
                    <h5><strong id="team-name"><?= Html::encode($team->name) ?></strong></h5>
                </div>
            </div>
        </div>

        <div class="contacts-info panel-info">
            <strong class="block-name">Основная информация</strong>
            <?php $form = ActiveForm::begin([
                'action' => ['office/save-information'],
                'enableAjaxValidation' => true,
                'validationUrl' => ['office/validate-information'],
                'id' => 'contact-info-form',
                'options' => [
                    'class' => 'justify-content-center info-form'
                ],
                'method' => 'post'
            ]); ?>

            <?= $form->field($model, 'name')->textInput() ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <div class="submit-wrap">
                <?= Html::submitButton('Сохранить данные', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="team-results">
        <div class="panel-info">
            <h4 class="results-header">Квесты</h4>
            <?php if ($passingResults) : ?>
                <?php foreach ($passingResults as $result) : ?>
                    <div class="passing-result <?= $result['status'] ?>">
                        <div class="result-header">
                            <a class="quest-name" href="<?= Url::toRoute(['quest/index', 'id' => $result['quest_id']]) ?>">
                                <?= $result['quest_name'] ?>
                            </a>
                            <span class="status <?= $result['status'] ?>" data-text="<?= QuestPassingResult::STATUSES_MESSAGES[$result['status']]['message'] ?>">
                                <?= QuestPassingResult::STATUSES_MESSAGES[$result['status']]['status'] ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon-small bi bi-info-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="result-info mb-0 mt-2">
                            <?php if ($result['status'] == QuestPassingResult::STATUS_IN_PROCESS) : ?>
                                <div class="text-center">
                                    <a class="btn btn-primary" href="<?= Url::toRoute(['quest/start', 'id' => $result['quest_id']]) ?>">
                                        Продолжить прохождение
                                    </a>
                                </div>
                            <?php elseif ($result['status'] == QuestPassingResult::STATUS_FAILED) : ?>
                                <div class="text-center">
                                    <a class="btn btn-primary confirm-link" href="<?= Url::toRoute(['quest/restart', 'id' => $result['quest_id']]) ?>" data-message="Вы действительно готовы начать прохождение? Обращаем ваше внимание на то, что время квеста ограничено и начать его заново будет нельзя">
                                        Начать заново
                                    </a>
                                </div>
                            <?php else : ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon bi bi-stopwatch" viewBox="0 0 16 16">
                                    <path d="M8.5 5.6a.5.5 0 1 0-1 0v2.9h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5V5.6z" />
                                    <path d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64a.715.715 0 0 1 .012-.013l.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354a.512.512 0 0 1-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5zM8 3a6 6 0 1 0 .001 12A6 6 0 0 0 8 3z" />
                                </svg>
                                <?= $result['spented_time'] ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="alert alert-warning text-center">
                    На данный момент вы не прошли ни одного квеста
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>