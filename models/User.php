<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $name
 * @property string $login
 * @property string $password
 * @property string $currency
 * @property string $last_login
 *
 * @property Transaction[] $transactions
 * @property UserCategory[] $userCategories
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'login', 'password', 'currency'], 'required'],
            [['last_login'], 'safe'],
            [['name', 'login'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 64],
            [['currency'], 'string', 'max' => 3],
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
            'login' => 'Login',
            'password' => 'Password',
            'currency' => 'Currency',
            'last_login' => 'Last Login',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['user_id' => 'id'])->where(['del' => 0]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCategories()
    {
        return $this->hasMany(UserCategory::className(), ['user_id' => 'id'])->where(['del' => 0]);
    }

    public static function categoryIsExists($name) {
        $user_category = (new Query())->select(['c.id'])
            ->from(Category::tableName().' c')
            ->innerJoin(UserCategory::tableName().' uc','c.id = uc.category_id')
            ->where(['c.del' => 0,'uc.del' => 0,'user_id' => Yii::$app->user->id]);

        $categoryList = (new Query())->select(['c.id','c.name'])
            ->from(Category::tableName().' c')
            ->where(['standard' => 1,'c.del' => 0])
            ->orWhere(['id' => $user_category])
            ->andFilterWhere(['like','c.name',$name]);
        
        return $categoryList->all() == true;
    }
    public function money() {
        $money = Transaction::find()->where(['type' => 1,'user_id' => Yii::$app->user->id,'del' => 0])->sum('money') - Transaction::find()->where(['type' => 0,'user_id' => Yii::$app->user->id,'del' => 0])->sum('money');
        return $money;
    }

    public function addedList() {
        $t =  Transaction::find()->select('money')->where(['type' => 1,'user_id' => Yii::$app->user->id,'del' => 0])->all();
        $moneyArray = array();
        foreach ($t as $value)
            array_push($moneyArray,floatval($value->money));

        return $moneyArray;
    }

    public function diminishedList() {
        $t =  Transaction::find()->select('money')->where(['type' => 0,'user_id' => Yii::$app->user->id,'del' => 0])->all();
        $moneyArray = array();
        foreach ($t as $value)
            array_push($moneyArray,floatval($value->money));

        return $moneyArray;
    }

    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
