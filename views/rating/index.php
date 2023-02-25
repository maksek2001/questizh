<?php

use kartik\select2\Select2;

$this->title = "Рейтинг команд " . Yii::$app->name;
?>

<div class="rating-container">
    <?php if ($quests) : ?>
        <?= Select2::widget([
            'id' => 'quest-select',
            'name' => 'status',
            'data' => $quests,
            'value' => array_key_first($quests),
            'hideSearch' => count($quests) < 10,
            'options' => ['placeholder' => 'Выберите квест'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
        <div id="rating-results" class="rating-results"></div>
    <?php else : ?>
        <div class="alert alert-warning">
            Квесты не найдены
        </div>
    <?php endif; ?>
</div>