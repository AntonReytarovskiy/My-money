<?php

namespace app\controllers;

use app\models\Category;
use app\models\CategoryForm;
use app\models\Login;
use app\models\SignUp;
use app\models\TransactionForm;
use app\models\User;
use app\models\UserCategory;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            "access" => [
                "class" => AccessControl::className(),
                "denyCallback" => function () {
                    $this->redirect(["site/login"]);
                },
                "rules" => [
                    [
                        "actions" => ["logout"],
                        "allow" => true,
                        "roles" => ["@"]
                    ],
                    [
                        "actions" => ["login"],
                        "allow" => true,
                        "roles" => ["?"]
                    ],
                    [
                        "actions" => ["index"],
                        "allow" => true,
                        "roles" => ["@"]
                    ],
                    [
                        "actions" => ["signup"],
                        "allow" => true,
                        "roles" => ["?"]
                    ],
                    [
                        "actions" => ["test"],
                        "allow" => true,
                        "roles" => ["?", "@"]
                    ],
                    [
                        "actions" => ["transactions"],
                        "allow" => true,
                        "roles" => ["@"]
                    ],
                    [
                        "actions" => ["value"],
                        "allow" => true,
                        "roles" => ["@", '?']
                    ],
                ]
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = 'forms';
        $model = new Login();
        if ($model->load(Yii::$app->request->post()) && $model->validate())
            if ($model->signIn())
                $this->goHome();

        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        $this->goHome();
    }

    public function actionSignup()
    {
        $this->layout = 'forms';
        $model = new SignUp();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->signUp();
            return $this->goHome();
        } else {
            return $this->render('signUp', ['model' => $model]);
        }
    }

    public function actionTest()
    {
        $category = UserCategory::reset('test');
        echo '<pre>';
        print_r($category);
        echo '</pre>';
    }

    public function actionTransactions()
    {
        $transactionModel = new TransactionForm();
        $categoryModel = new CategoryForm();

        if (Yii::$app->request->isAjax) {
            //Удаления транзакции
            if (isset($_POST['transactionIndex'])) {
                return Yii::$app->user->identity->transactions[Yii::$app->request->post('transactionIndex')]->delete();
            }
            //Удаления категории
            else if (isset($_POST['categoryIndex'])) {
                return Yii::$app->user->identity->userCategories[Yii::$app->request->post('categoryIndex')]->delete();
            }
            //deepdrop
            else if (Yii::$app->request->post('depdrop_parents')) {
                $typeId = Yii::$app->request->post('depdrop_parents');
                $category = Category::userCategoryList($typeId);
                return Json::encode(['output' => $category, 'select' => '']);
            }
        }
        //Добавления категорий
        else if (Yii::$app->request->post('CategoryForm') && $categoryModel->load(Yii::$app->request->post()) && $categoryModel->validate()) {
            if (UserCategory::reset($categoryModel->name))
                $this->redirect(Url::toRoute('site/transactions'));
            else {
                $categoryModel->create();
                $this->redirect(Url::toRoute('site/transactions'));
            }

        }
        //Добавления транзакции
        else if (Yii::$app->request->post('TransactionForm') && $transactionModel->load(Yii::$app->request->post()) && $transactionModel->validate()) {
            if (!$transactionModel->create())
                $this->redirect(Url::toRoute('site/transactions'));
        }

         return $this->render('transactions', ['transactionModel' => $transactionModel, 'categoryModel' => $categoryModel]);
    }
}

