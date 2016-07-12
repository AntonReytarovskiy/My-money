<?php
/**
 * Created by PhpStorm.
 * User: Антоша
 * Date: 07.07.2016
 * Time: 4:03
 */

namespace app\models;


use yii\base\Model;
use yii\db\Query;

class CategoryForm extends Model
{
    public $name;
    public $type;

    public function rules()
    {
        return [
            ['name','required','message' => 'Пожалуйста введите имя категории'],
            ['type','required','message' => 'Пожалуйста выберите тип категории'],
            ['name','categoryExists']
        ];
    }

    public function categoryExists($attribute,$params) {
        if (User::categoryIsExists($this->name))
            $this->addError($attribute,'Такая категория уже существует');
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя категории',
            'type' => 'Тип'
        ];
    }

    public function create() {
        $category = new Category();
        $category->name = $this->name;
        $category->type = $this->type;
        $category->standard = 0;
        $category->save();
        
        $userCategory = new UserCategory();
        $userCategory->user_id = \Yii::$app->user->id;
        $userCategory->category_id = $category->id;
        $userCategory->save();
        return $userCategory->errors;
    }
}