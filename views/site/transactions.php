<?php
use app\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\money\MaskMoney;
use kartik\date\DatePicker;

$this->title = 'Транзакции';
$this->registerCssFile('https://fonts.googleapis.com/css?family=Cuprum&subset=latin,cyrillic');
$this->registerCssFile('css/transactions.css');
$this->registerJsFile('js/numberInput.js');
$this->registerJsFile('js/transactions.js');
?>

<div class="add-transaction col-md-6">
    <p>Добавить транзакцию</p>
    <div class="row">
        <?php $form = ActiveForm::begin([
            "id" => "TransactionForm",
            "options" => ["class" => "horizontal-form"],
        ]); ?>
        <div class="calendar col-md-6">
            <div class="well well-sm" style="background-color: #fff; width:245px">
                <?= $form->field($transactionModel,'date')->widget(DatePicker::className(),[
                    'name' => 'date',
                    'type' => DatePicker::TYPE_INLINE,
                    'value' => 'Tue, 23-Feb-1982',
                    'language' => 'ru',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd'
                    ],
                    'options' => [
                        // you can hide the input by setting the following
                        // 'class' => 'hide'
                    ]
                ]); ?>
            </div>
        </div>
        <div class="form col-md-6">
            <?= $form->field($transactionModel, 'type')->dropDownList([
                0 => 'Расход',
                1 => 'Доход'
            ], ['id'=>'type-id','prompt' => 'Выбирите тип транзакции']); ?>

            <?= $form->field($transactionModel, 'categoryId')->widget(DepDrop::classname(), [
                'options' => ['id'=>'category-id'],
                'pluginOptions'=>[
                    'loadingText' => 'Загрузка',
                    'depends'=>['type-id'],
                    'placeholder' => 'Выберите категорию',
                    'url' => Url::toRoute('/site/transactions'),
                ]
            ]); ?>

            <?php
            if (Yii::$app->user->identity->currency == 'UAH')
                $sidnCurrency = '₴ ';
            else $sidnCurrency = '₽ ';

            echo $form->field($transactionModel, 'money')->widget(MaskMoney::classname(), [
                'pluginOptions' => [
                    'prefix' => "$sidnCurrency",
                    'allowNegative' => false,
                ]
            ]); ?>

            <?= $form->field($transactionModel,'note')->textarea() ?>

            <div>
                <?= Html::submitButton("Добавить",["class" => "btn btn-success"]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?php if (Yii::$app->user->identity->transactions): ?>
    <div class="row transactions">
            <table class="table table-hover" id="transaction-table">
                <thead>
                <tr class="fixed">
                    <th>Категория</th>
                    <th>Деньги</th>
                    <th>Заметка</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody >
                <?php foreach (Yii::$app->user->identity->transactions as $transaction): ?>
                    <?php if ($transaction->type): ?>
                        <tr class="added">
                            <td><?= $transaction->category->name ?></td>
                            <td>+ <?= $transaction->money ?></td>
                            <td><?= $transaction->note ?></td>
                            <td><?= $transaction->date ?></td>
                        </tr>
                    <?php else: ?>
                        <tr class="diminished">
                            <td><?= $transaction->category->name ?></td>
                            <td>- <?= $transaction->money ?></td>
                            <td><?= $transaction->note ?></td>
                            <td><?= $transaction->date ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
    </div>
    <div class="row">
        <div class="container-fluid">
            <button class="btn btn-danger btn-block" id="delete-transaction">Удалить</button>
        </div>
    </div>
    <?php endif; ?>
</div>
<div class="category col-md-6">
    <p>Добавить категорию</p>
        <?php
        $form = ActiveForm::begin([
            "id" => "CategoryForm",
            "options" => ["class" => "horizontal-form"],
        ]); ?>

        <?= $form->field($categoryModel,'name') ?>
        <?= $form->field($categoryModel,'type')->dropDownList([
            0 => 'Расходы',
            1 => 'Доходы'
        ]) ?>

        <div>
            <?= Html::submitButton("Добавить",["class" => "btn btn-success"]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    <?php if (Yii::$app->user->identity->userCategories): ?>
        <div class="category-list">
            <table class="table table-hover" id="category-table">
                <thead>
                <tr class="fixed">
                    <th>Имя</th>
                    <th>Тип</th>
                    <th>Удалить</th>
                </tr>
                </thead>
                <tbody >
                <?php foreach (Yii::$app->user->identity->userCategories as $userCategory): ?>
                    <tr>
                        <td class="category-name"><?= $userCategory->category->name ?></td>
                        <td class="category-type"><?php
                                if ($userCategory->category->type)
                                    echo 'Доходы';
                                else echo 'Расходы';
                            ?></td>
                        <td><button class="btn btn-danger btn-sm delete-category">Удалить</button></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>