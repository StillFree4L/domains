<?php


namespace app\controllers;
use app\components\Controller;
use glob\components\ActiveRecord;
use glob\helpers\Common;
use glob\models\Cases;
use glob\models\Dis;
use glob\models\Grup;
use glob\models\Users;
use glob\models\UsersInfo;
use yii\db\Query;
use yii\filters\AccessControl;

/**
 * Class MainController
 * @package app\controllers
 */
class CasesController extends Controller
{

    public function beforeAction($action)
    {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main", "Кейсы"), \Yii::$app->urlManager->createUrl("/cases/index"));
        return $p;
    }

    public function actionIndex()
    {

        $filter = \Yii::$app->request->get("filter", []);

        //$subjects = Dis::find();

        $types = [
            \glob\models\Cases::TYPE_CASE => \Yii::t("main","Кейс"),
            \glob\models\Cases::TYPE_UMDK => \Yii::t("main","УМДК"),
            \glob\models\Cases::TYPE_SCHEDULE => \Yii::t("main","Расписание"),
        ];

        if (!\Yii::$app->user->can(Users::ROLE_ADMIN)) {

            /*if (\Yii::$app->user->identity->profile->is_teacher) {
                $s_ids = (new Query)
                    ->select("dis_id")
                    ->from("teacher_discipline")
                    ->where([
                        "teacher_id" => \Yii::$app->user->identity->profile->id
                    ])->column();

                if (empty($s_ids)) {
                    throw new \Exception(\Yii::t("main", "Вам не назначено ни одного предмета. Обратитесь в офис регистратора или к Администратору системы"));
                }

                $subjects->andWhere(["in", "id", $s_ids]);
            }*/

            if (\Yii::$app->user->can(Users::ROLE_STUDENT)) {

                /*$s_ids = (new Query)
                    ->select("dis")
                    ->from("test_gr_dis")
                    ->where([
                        "grup" => \Yii::$app->user->identity->profile->grup
                    ])->column();

                if (empty($s_ids)) {
                    throw new \Exception(\Yii::t("main", "Вам не назначено ни одного предмета. Обратитесь в офис регистратора или к Администратору системы"));
                }

                $subjects->andWhere(["in", "id", $s_ids]);*/

            }

        }


        /*$subjects->indexBy("id");
        $subjects = $subjects->all();*/

        $teacher_id = null;
        $cases = [];
        //if ($filter['subject_id']) {

            /*if (\Yii::$app->user->can(Users::ROLE_ADMIN)) {

                $t_ids = (new Query())
                    ->select("teacher_id")
                    ->from("teacher_discipline")
                    ->where([
                        "dis_id" => $filter['subject_id']
                    ])->column();

                if (empty($t_ids)) {
                    throw new \Exception(\Yii::t("main", "На данный предмет не назначено ниодного преподавателя"));
                }

                $teachers = UsersInfo::find();
                $teachers->andWhere("is_teacher = 1");
                $teachers->andWhere(["in", "id", $t_ids]);
                $teachers->orderBy("last_name, first_name");
                $teachers = $teachers->all();
                $teacher_id = $filter['teacher_id'];
            } else if (\Yii::$app->user->identity->profile->is_teacher) {
                $teacher_id = \Yii::$app->user->identity->profile->id;
            }
            */

            if ($filter['s'] AND $filter['type']) {

                \Yii::$app->breadCrumbs->addLink($types[$filter['type']], Common::createUrl("/cases/index", [
                    'filter' => [
                        'type' => $filter['type']
                    ]
                ]));

                \Yii::$app->breadCrumbs->addLink($filter['s'].\Yii::t("main"," семестр"), Common::createUrl("/cases/index", [
                    'filter' => [
                        'type' => $filter['type'],
                        's' => $filter['s']
                    ]
                ]));

                $cases = Cases::find()
                    ->andWhere([
                        //"subject_id" => $filter['subject_id'],
                        "semestr" => $filter['s'],
                        "type" => $filter['type']
                    ]);

                /*if ($teacher_id) {
                    $cases
                        ->andWhere([
                            "teacher_id" => $teacher_id
                        ]);
                } else */

                if (\Yii::$app->user->identity->role_id == Users::ROLE_STUDENT) {

                    $c_ids = (new Query)
                        ->select("case_id")
                        ->from("cases_groups")
                        ->where([
                            "group_id" => \Yii::$app->user->identity->profile->grup
                        ])->column();

                    $cases
                        ->andWhere([
                            "in", "id", $c_ids
                        ]);

                }

                $cases = $cases->orderBy("ts DESC")
                    ->all();
            }

        //}

        \Yii::$app->data->filter = $filter;
        \Yii::$app->data->cases = ActiveRecord::arrayAttributes($cases, [], array_merge((new Cases())->attributes(), ["infoJson"]), true);

        return $this->render("index", [
            "filter" => $filter,
            //"subjects" => $subjects,
            //"teachers" => $teachers ? $teachers : null,
            "cases" => $cases,
            "types" => $types
        ]);
    }

    public function actionAssign()
    {

        $model = new Cases();
        if (\Yii::$app->request->get('id')) {
            $model = Cases::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        \Yii::$app->data->case = ActiveRecord::arrayAttributes($model, [],  [], true);

        $assigned_groups = (new Query)
            ->select("group_id")
            ->from("cases_groups")
            ->where([
                "case_id" => $model->id
            ])->column();

        /*$g_ids = (new Query())
            ->select("grup")
            ->from("test_gr_dis")
            ->where([
                "dis" => $model->subject_id
            ])->column();*/

        $groups = Grup::find()
            //->andWhere(["in", "id", $g_ids])
            ->orderBy("grup")
            ->all();

        return $this->render("assign", [
            "assigned_groups" => $assigned_groups,
            "groups" => $groups
        ]);

    }

    public function actionToggle()
    {

        $model = new Cases();
        if (\Yii::$app->request->get('id')) {
            $model = Cases::find()->byPk(\Yii::$app->request->get('id'))->one();
        }

        switch (\Yii::$app->request->post("type")) {

            case "group":

                $assigned_group = (new Query())
                    ->indexBy("group_id")
                    ->from("cases_groups")
                    ->where([
                        "case_id" => $model->id,
                        "group_id" => \Yii::$app->request->post("id")
                    ])->one();

                if ($assigned_group) {

                    $delete = (new Query())
                        ->createCommand()
                        ->delete("cases_groups", [
                            "id" => $assigned_group['id']
                        ])->execute();

                } else {

                    $insert = (new Query())
                        ->createCommand()
                        ->insert("cases_groups", [
                            "case_id" => $model->id,
                            "group_id" => \Yii::$app->request->post("id"),
                            "ts" => time()
                        ])->execute();

                }

                break;

        }

        return $this->renderJSON([]);

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
                        'actions' => ['index'],
                        'roles' => [Users::ROLE_STUDENT, Users::ROLE_TEACHER],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['toggle','assign'],
                        'roles' => [Users::ROLE_TEACHER],
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

}

?>