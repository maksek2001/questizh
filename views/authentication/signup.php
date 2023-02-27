<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\widgets\Alert;

$this->title = 'Регистрация';
?>

<div class="form-block">
    <h3><?= Html::encode($this->title) ?></h3>

    <p>Пожалуйста, заполните поля для регистрации</p>

    <?php Alert::begin(); ?>
    <?php Alert::end(); ?>

    <?php $form = ActiveForm::begin([
        'id' => 'signup-form',
        'options' => [
            'class' => 'justify-content-center'
        ],
        'method' => 'post'
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>
</div>