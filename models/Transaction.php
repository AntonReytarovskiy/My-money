<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property integer $id
 * @property boolean $type
 * @property integer $user_id
 * @property integer $category_id
 * @property string $money
 * @property string $note
 * @property string $date
 * @property boolean $del
 *
 * @property Category $category
 * @property User $user
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'del'], 'boolean'],
            [['user_id', 'category_id', 'money', 'date'], 'required'],
            [['user_id', 'category_id'], 'integer'],
            [['money'], 'number'],
            [['note'], 'string'],
            [['date'], 'safe'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
            'money' => 'Money',
            'note' => 'Note',
            'date' => 'Date',
            'del' => 'Del',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function delete() {
        $this->del = 1;
        return $this->save();
    }
}
