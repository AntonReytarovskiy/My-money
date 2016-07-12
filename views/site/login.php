<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Вход';
?>

<div class="row title">
    <h1><?= $this->title ?></h1>
    <a href="<?= \yii\helpers\Url::toRoute('site/signup') ?>">Регистрация</a>
</div>
<div class="row">
    <?php

    $form = ActiveForm::begin([
        "id" => "Login",
        "options" => ["class" => "horizontal-form"],
    ]); ?>

    <?= $form->field($model,"login")?>
    <?= $form->field($model,"password")->passwordInput()?>

    <div>
        <?= Html::submitButton("Вход",["class" => "btn btn-primary"]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>