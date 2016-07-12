<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Регистрация';
?>

<div class="row title">
    <h1><?= $this->title ?></h1>
    <a href="<?= \yii\helpers\Url::toRoute('site/login') ?>">Вход</a>
</div>
<div class="row">
    <?php

    $form = ActiveForm::begin([
        "id" => "SignUp",
        "options" => ["class" => "horizontal-form"],
    ]); ?>

    <?= $form->field($model,"login")?>
    <?= $form->field($model,"password")->passwordInput()?>
    <?= $form->field($model,"confirm_password")->passwordInput()?>
    <?= $form->field($model,"name")?>
    <?= $form->field($model,'currency')->dropDownList([
        'UAH' => 'Гривны',
        'RUR' => 'Рубли',
    ]) ?>
    <div>
        <?= Html::submitButton("Регистрация",["class" => "btn btn-primary"]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>