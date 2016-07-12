<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <link href='https://fonts.googleapis.com/css?family=PT+Sans+Narrow&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="icon"
          type="image/png"
          href="image/favicon.png">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="col-md-2 navs">
    <ul class="nav">
        <li><a href="<?= Url::toRoute('site/index')?>"><i class="glyphicon glyphicon-user"></i> Обзор</a></li>
        <li><a href="<?= Url::toRoute('site/transactions')?>"><i class="glyphicon glyphicon-usd"></i> Транзакции</a></li>
    </ul>
    <a class="logout" href="<?= Url::toRoute('site/logout') ?>">Выйти (<?= Yii::$app->user->identity->name ?>)</a>
</div>
<div class="col-md-10">
    <?= $content ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
