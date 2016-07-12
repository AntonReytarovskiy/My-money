<?php
$this->title = "Обзор";
$this->registerCssFile('css/index.css');
?>
<link href='https://fonts.googleapis.com/css?family=PT+Sans+Narrow' rel='stylesheet' type='text/css'>
<div class="money col-md-4">
    <h4>Приветствуем вас <span class="user-name"><?= Yii::$app->user->identity->name ?></span></h4>
    <h3>На вашем счету</h3>
    <h3><?= Yii::$app->user->identity->money() ?> <span class="currency"><?= Yii::$app->user->identity->currency ?></span></h3>
</div>
<div class="history col-md-8">
    <h4>История вашего бюджета</h4>
    <?php if (Yii::$app->user->identity->transactions): ?>
    <table class="table">
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
    <?php else: ?>
        <h3>У вас нету транзакций. Сделать <a href="<?= \yii\helpers\Url::toRoute('site/transactions')?>">транзакцию</a></h3>
    <?php endif; ?>
</div>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="application/javascript">
    $(function () {
        $('.chart').highcharts({
            title: {
                text: 'График вашего бюджета',
                x: -20 //center
            },
//            xAxis: {
//                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
//                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
//            },
            yAxis: {
                title: {
                    text: 'Деньги' + ' <?= Yii::$app->user->identity->currency ?>'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ' <?= Yii::$app->user->identity->currency ?>',
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Доходы',
                data: [<?= implode(',',Yii::$app->user->identity->addedList()) ?>]
            },{
                name: 'Расходы',
                data: [<?= implode(',',Yii::$app->user->identity->diminishedList()) ?>]
            }]
        });
    });
</script>
<div class="chart col-md-12">
</div>
