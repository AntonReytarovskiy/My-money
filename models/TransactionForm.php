<?php
/**
 * Created by PhpStorm.
 * User: Антоша
 * Date: 05.07.2016
 * Time: 2:46
 */

namespace app\models;


use yii\base\Model;

class TransactionForm extends Model
{
    public $categoryId;
    public $type;
    public $money;
    public $note;
    public $date;

    public function attributeLabels()
    {
        return [
            'categoryId' => 'Категория',
            'type' => 'Тип транзакции',
            'money' => 'Сума',
            'note' => 'Заметка',
            'date' => 'Дата'
        ];
    }

    public function rules()
    {
        return [
            [['categoryId'],'required','message' => 'Пожалуйста выберите категорию'],
            ['type','required','message' => 'Пожалуйста выберите тип транзакции'],
            ['money','required','message' => 'Пожалуйста введите суму транзакции'],
            ['date','required','message' => 'Пожалуйста выберите дату транзакции'],
            ['note','string','max' => 1000],
            ['money','moneyNoZero'],
            ['money','noMoney']
        ];
    }

    public function noMoney($attribute) {
        if ($this->type == 0) {
            if (\Yii::$app->user->identity->money() <= 0 || $this->money > \Yii::$app->user->identity->money())
                $this->addError($attribute,'Денег нет, ну вы держитесь там');
        }
    }

    public function moneyNoZero($attribute) {
        if ($this->money <= 0)
            $this->addError($attribute,'Пожалуйста введите суму транзакции');
    }

    public function create() {
        $transaction = new Transaction();
        $transaction->category_id = $this->categoryId;
        $transaction->type = $this->type;
        $transaction->money = $this->money;
        $transaction->note = $this->note;
        $transaction->user_id = \Yii::$app->user->id;
        $transaction->date = $this->date;
        $transaction->save();
        return $transaction->errors;
    }
}