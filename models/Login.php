<?php
/**
 * Created by PhpStorm.
 * User: Антоша
 * Date: 01.07.2016
 * Time: 16:14
 */

namespace app\models;


use yii\base\Model;

class Login extends Model
{
    public $login;
    public $password;

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'password' => 'Пароль'
        ];
    }

    public function rules()
    {
        return [
            [['login','password'],'required','message' => 'Пожалуйста введите {attribute}'],
            ['password','existsUser'],
        ];
    }

    function existsUser($attribute,$params) {
        $account = User::find()
            ->where(["login" => $this->login])
            ->one();
        if (!$account)
            $this->addError($attribute,'Неверный логин или пароль');
        else {
            if ($account->password != hash('sha256',$this->password))
                $this->addError($attribute,'Неверный логин или пароль');
        }
    }

    public function signIn() {
        $user = User::find()->where(['login' => $this->login])->one();
        $password = hash('sha256',$this->password);
        if ($user->password == $password) {
            \Yii::$app->user->login($user);
            $user->last_login = '';
            $user->update();
            return true;
        }

        return false;
    }
}