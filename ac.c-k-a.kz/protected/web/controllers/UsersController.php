<?php

namespace app\controllers;

use glob\components\ActiveRecord;
use app\components\Controller;
use glob\models\Users;
use glob\models\filters\UsersFilter;
use yii\data\Pagination;
use yii\filters\AccessControl;

class UsersController extends Controller
{

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Главная"), \glob\helpers\Common::createUrl("/main/index"));
        return $p;
    }

    public function actionIndex()
    {

        $filter = new UsersFilter();
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $users = Users::find()->orderBy("state, ts DESC");
        $users->joinWith("profile");

        $users = $filter->appendFilter($users);

        $count = $users->count();
        $page = \Yii::$app->request->get("page", 1);

        $users->offset(($page*50) - 50);
        $users->limit(50);

        $pagination = new Pagination([
            'totalCount' => $count,
        ]);

        $users = $users->all();
        \Yii::$app->data->users = ActiveRecord::arrayAttributes($users, [], array_merge((new Users)->attributes(), ['jInfo', 'fio', 'roleCaption']), true);



        return $this->render("index", [
            "filter" => $filter,
            "users" => $users,
            "pagination" => $pagination
        ]);

    }

    public function actionAdd()
    {
        $user = new Users();
        $user->scenario = "registration";

        if (\Yii::$app->request->get('id')) {
            $user = Users::find()->byPk(\Yii::$app->request->get('id'))->one();
            $user->scenario = "edit";
        }

        if (\Yii::$app->request->post('Users')) {

            $user->attributes = \Yii::$app->request->post('Users');
            $user->setInfo("role", \Yii::$app->request->post('Users')['roleName']);

            if (!empty(\Yii::$app->request->post('Users')['repassword']) AND !empty(\Yii::$app->request->post('Users')['password'])) {
                $user->repassword = \Yii::$app->request->post('Users')['repassword'];
                $user->password = \Yii::$app->request->post('Users')['password'];
            }

            if ($user->save())
            {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Пользователь успешно добавлен"));
                return $this->renderJSON([
                    "redirect"=>\glob\helpers\Common::createUrl("/users/index")
                ]);
            } else {
                return $this->renderJSON($user->getErrors(), true);
            }

        }

        \Yii::$app->data->user = ActiveRecord::arrayAttributes($user, [],  ['id', 'login', 'jInfo', 'fio', 'roleCaption', 'repassword'], true);
        \Yii::$app->data->rules = $user->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $user->attributeLabels();

        return $this->render("form", [
            "user" => $user
        ]);
    }

    public function actionDelete()
    {

        if (\Yii::$app->request->get('id')) {
            $user = Users::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if ($user AND $user->delete()) {

            \Yii::$app->session->setFlash("success",\Yii::t("main","Пользователь успешно удален"));

        }

        \Yii::$app->response->redirect(str_replace("http://".$_SERVER['HTTP_HOST'], "",\Yii::$app->request->referrer));

    }

    public function actionRestore()
    {

        if (\Yii::$app->request->get('id')) {
            $user = Users::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        $user->state = 1;
        if ($user->save()) {

            \Yii::$app->session->setFlash("success",\Yii::t("main","Пользователь успешно востановлен"));

        }

        \Yii::$app->response->redirect(str_replace("http://".$_SERVER['HTTP_HOST'], "",\Yii::$app->request->referrer));

    }

    public function actionProfile()
    {
        $profile = Users::findOne(\Yii::$app->user->id);
        $profile->scenario = "profile";

        if (\Yii::$app->request->post('Users')) {

            $profile->attributes = \Yii::$app->request->post('Users');
            if (\Yii::$app->request->post('Users')['logo']) {
                $profile->logo = \Yii::$app->request->post('Users')['logo'];
            }
            if (!empty(\Yii::$app->request->post('Users')['repassword']) AND !empty(\Yii::$app->request->post('Users')['password'])) {
                $profile->repassword = \Yii::$app->request->post('Users')['repassword'];
                $profile->password = \Yii::$app->request->post('Users')['password'];
            }

            if ($profile->save())
            {
                $r = \glob\helpers\Common::createUrl("/users/profile");
                return $this->renderJSON([
                    "redirect"=>$r
                ]);
            } else {
                return $this->renderJSON($profile->getErrors(), true);
            }


        }

        \Yii::$app->data->profile = ActiveRecord::arrayAttributes($profile, [],  ['id', 'logo', 'jInfo', 'fio', 'repassword'], true);
        \Yii::$app->data->rules = $profile->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $profile->attributeLabels();

        return $this->render("profile", [
            "profile"=>$profile
        ]);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'actions' => ['profile'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => [Users::ROLE_ADMIN],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?','@']
                    ]
                    // everything else is denied by default
                ],
            ],
        ];
    }


}

?>