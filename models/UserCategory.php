<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "user_category".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property boolean $del
 *
 * @property User $user
 * @property Category $category
 */
class UserCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id'], 'required'],
            [['user_id', 'category_id'], 'integer'],
            [['del'], 'boolean'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
            'del' => 'Del',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function delete()
    {
        $this->del = 1;
        return $this->save();
    }

    public static function reset($name) {
        $category = (new Query())->select(['c.id'])
            ->from(Category::tableName().' c')
            ->innerJoin(UserCategory::tableName().' uc','uc.category_id = c.id')
            ->where(['uc.user_id' => Yii::$app->user->id,'c.name' => $name,'uc.del' => 1]);

        $tmp = (new Query())->select('id')->from(['TMP' => $category]);

        $update = (new Query())->createCommand()->update('user_category',['del' => 0],['category_id' => $tmp]);

        return $update->execute();
    }
}
