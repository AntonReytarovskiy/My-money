<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property boolean $type
 * @property boolean $standard
 * @property boolean $del
 *
 * @property Transaction[] $transactions
 * @property UserCategory[] $userCategories
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type', 'standard', 'del'], 'boolean'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'standard' => 'Standard',
            'del' => 'Del',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::className(), ['category_id' => 'id']);
    }

    public static function userCategoryList($typeId) {
        $user_category = (new Query())->select(['c.id'])
            ->from(Category::tableName().' c')
            ->innerJoin(UserCategory::tableName().' uc','c.id = uc.category_id')
            ->where(['c.type' => $typeId,'c.del' => 0,'uc.del' => 0,'user_id' => Yii::$app->user->id]);

        $categoryList = (new Query())->select(['c.id','c.name'])
            ->from(Category::tableName().' c')
            ->where(['standard' => 1,'c.del' => 0,'type' => $typeId])
            ->orWhere(['id' => $user_category]);

        return $categoryList->all();
    }
}
