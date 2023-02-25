<div class="alert alert-success hint" id="hint-<?= $hint->id ?>">
    <?php echo nl2br($hint->text); ?>
    <?php if ($hint->image_filename) : ?>
        <a target="_blank" href="/web/images/tasks-hints/<?= $hint->image_filename ?>">
            <img src="/web/images/tasks-hints/<?= $hint->image_filename ?>" class="hint-image" />
        </a>
    <?php endif; ?>
</div>