<?php
/**
 * Created by PhpStorm.
 * User: Антоша
 * Date: 02.07.2016
 * Time: 18:44
 */

namespace app\models;


use yii\base\Model;

class SignUp extends Model
{
    public $name;
    public $login;
    public $password;
    public $confirm_password;
    public $currency;

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'login' => 'Логин',
            'password' => 'Пароль',
            'confirm_password' => 'Повторите пароль',
            'currency' => 'Валюта'
        ];
    }

    public function rules()
    {
        return [
            [['name','login','password','confirm_password','currency'],'required','message' => 'Пожалуйста введите {attribute}'],
            ["confirm_password","compare","compareAttribute" => "password","message" => "Пароли не совпадают"],
            ['login','existsUser'],
            [['name','login'],'string','max' => 30,'message' => 'Не больше 30 символов'],
            [['password','confirm_password'],'string','max' => 30 ,'message' => 'Не больше 30 символов'],
            [['name','login','password','confirm_password'],'string','min' => 5,'message' => 'Не менее 5 символов']
        ];
    }

    function existsUser($attribute,$params) {
        $user = User::find()
            ->where(['login' => $this->login])
            ->one();
        if ($user)
            $this->addError($attribute,'Логин занят');
    }

    public function signUp() {
        $user = new User();
        $user->login = $this->login;
        $user->name = $this->name;
        $user->password = hash('sha256',$this->password);
        $user->currency = $this->currency;
        $user->save();

        \Yii::$app->user->login($user);
    }
}