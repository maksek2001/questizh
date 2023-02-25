<?php

use app\assets\AuthenticationAsset;
use app\widgets\Alert;
use yii\bootstrap5\Html;

AuthenticationAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <?= $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/svg+xml', 'href' => '/web/favicon.svg']); ?>
    <?= $this->registerLinkTag(['rel' => 'shortcut icon', 'type' => 'image/svg+xml', 'href' => '/web/favicon.svg']); ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <?= $this->render('common/header.php'); ?>

    <main role="main" class="flex-shrink-0">
        <div class="container-fluid main-container">
            <div class="authentication-subheader">
                <?= Html::a('РЕГИСТРАЦИЯ', 'signup', [
                    'class' => (Yii::$app->controller->action->id == 'signup') ? 'active' : ''
                ]) ?>
                &nbsp; / &nbsp;
                <?= Html::a('АВТОРИЗАЦИЯ', 'login', [
                    'class' => (Yii::$app->controller->action->id == 'login') ? 'active' : ''
                ]) ?>
            </div>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <?= $this->render('common/footer.php'); ?>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>