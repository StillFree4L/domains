<?php

namespace app\controllers;

use app\components\Controller;
use glob\components\ActiveRecord;
use glob\models\Dis;
use glob\models\Grup;
use glob\models\Users;
use yii\db\Query;
use yii\filters\AccessControl;

class SubjectsController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => [Users::ROLE_REGISTRATION],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?']
                    ]
                    // everything else is denied by default
                ],
            ],
        ];
    }

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Офис-регистратор"), \Yii::$app->urlManager->createUrl("/recorder/tests"));
        return $p;
    }

    public function actionAdd()
    {

        $model = new Dis();

        if (\Yii::$app->request->get('id')) {
            $model = Dis::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if (\Yii::$app->request->post('Dis')) {

            $model->attributes = \Yii::$app->request->post('Dis');
            if ($model->save())
            {
                \Yii::$app->session->setFlash("ok",\Yii::t("main","Предмет успешно добавлен"));
                return $this->renderJSON([
                    "redirect"=>\glob\helpers\Common::createUrl("/recorder/subjects")
                ]);
            } else {
                return $this->renderJSON($model->getErrors(), true);
            }

        }

        \Yii::$app->data->model = ActiveRecord::arrayAttributes($model, [], [], true);
        \Yii::$app->data->rules = $model->filterRulesForBackboneValidation();
        \Yii::$app->data->attributeLabels = $model->attributeLabels();


        return $this->render("add", [
            "model" => $model
        ]);
    }



    public function actionAssign()
    {

        $model = new Dis();
        if (\Yii::$app->request->get('id')) {
            $model = Dis::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        \Yii::$app->data->subject = ActiveRecord::arrayAttributes($model, [],  [], true);

        $assigned_groups = (new Query())
            ->select("grup, semestr")
            ->indexBy("grup")
            ->from("test_gr_dis")
            ->where([
                "dis" => $model->id
            ])->all();

        $groups = Grup::find()->orderBy("grup")->all();

        $assigned_teachers = (new Query)
            ->select("teacher_id")
            ->from("teacher_discipline")
            ->where([
                "dis_id" => $model->id
            ])->column();

        $teachers = Users::find()->andWhere([
            "role_id" => Users::ROLE_TEACHER
        ])
            ->joinWith([
                "profile"
            ])
            ->orderBy("users_info.last_name, users_info.first_name")
            ->all();

        return $this->render("assign", [
            "assigned_groups" => $assigned_groups,
            "groups" => $groups,
            "assigned_teachers" => $assigned_teachers,
            "teachers" => $teachers
        ]);

    }

    public function actionDelete()
    {

        if (\Yii::$app->request->get('id')) {
            $subject = Dis::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        if ($subject AND $subject->delete()) {

            \Yii::$app->session->setFlash("success",\Yii::t("main","Предмет успешно удален"));

        }

        \Yii::$app->response->redirect(str_replace("http://".$_SERVER['HTTP_HOST'], "",\Yii::$app->request->referrer));

    }

    public function actionToggle()
    {

        $model = new Dis();
        if (\Yii::$app->request->get('id')) {
            $model = Dis::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        switch (\Yii::$app->request->post("type")) {

            case "group":

                $assigned_group = (new Query())
                    ->indexBy("grup")
                    ->from("test_gr_dis")
                    ->where([
                        "dis" => $model->id,
                        "grup" => \Yii::$app->request->post("id")
                    ])->one();

                    if ($assigned_group) {

                        $delete = (new Query())
                            ->createCommand()
                            ->delete("test_gr_dis", [
                                "id" => $assigned_group['id']
                            ])->execute();

                    } else {

                        $insert = (new Query())
                            ->createCommand()
                            ->insert("test_gr_dis", [
                                "dis" => $model->id,
                                "grup" => \Yii::$app->request->post("id")
                            ])->execute();

                    }

                break;

            case "semestr" :

                $assigned_group = (new Query())
                    ->indexBy("grup")
                    ->from("test_gr_dis")
                    ->where([
                        "dis" => $model->id,
                        "grup" => \Yii::$app->request->post("id")
                    ])->one();

                if ($assigned_group) {

                    $update = (new Query())
                        ->createCommand()
                        ->update("test_gr_dis",
                            [
                                "semestr" => \Yii::$app->request->post('semestr')
                            ],
                            [
                                "id" => $assigned_group['id']
                        ])->execute();

                }

                break;

            case "teacher":

                $assigned_teacher = (new Query())
                    ->indexBy("teacher_id")
                    ->from("teacher_discipline")
                    ->where([
                        "dis_id" => $model->id,
                        "teacher_id" => \Yii::$app->request->post("id")
                    ])->one();

                if ($assigned_teacher) {

                    $delete = (new Query())
                        ->createCommand()
                        ->delete("teacher_discipline", [
                            "id" => $assigned_teacher['id']
                        ])->execute();

                } else {

                    $insert = (new Query())
                        ->createCommand()
                        ->insert("teacher_discipline", [
                            "dis_id" => $model->id,
                            "teacher_id" => \Yii::$app->request->post("id")
                        ])->execute();

                }

                break;

        }

        return $this->renderJSON([]);

    }

}

?>