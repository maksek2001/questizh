<?php

use yii\helpers\Html;

?>
<div class="alert alert-success hint" id="hint-<?= Html::encode($hint->id) ?>">
    <?= nl2br($hint->text) ?>
    <?php if ($hint->image_filename) : ?>
        <a target="_blank" href="/web/images/tasks-hints/<?= Html::encode($hint->image_filename) ?>">
            <img src="/web/images/tasks-hints/<?= Html::encode($hint->image_filename) ?>" class="hint-image" />
        </a>
    <?php endif; ?>
</div>