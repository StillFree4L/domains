<?php

namespace app\controllers;
use app\components\Controller;
use glob\components\ActiveRecord;
use glob\helpers\Common;
use glob\models\Dis;
use glob\models\Grup;
use glob\models\filters\SubjectsFilter;
use glob\models\TestStarted;
use glob\models\Users;
use glob\models\UsersInfo;
use yii\data\Pagination;
use yii\db\Query;
use yii\filters\AccessControl;

/**
 * Class MainController
 * @package app\controllers
 */
class RecorderController extends Controller {

    public $defaultAction = "tests";
    public $layout = "@app/views/recorder/layout";

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Офис-регистратор"), \Yii::$app->urlManager->createUrl("/recorder/tests"));
        return $p;
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


    public function actionTests()
    {

        $filter = \Yii::$app->request->get("filter",[]);

        $groups = Grup::find();
        $groups->indexBy("id");
        $groups = $groups->all();

        if ($filter['group_id']) {
            $filter['s'] = $filter['s'] ? $filter['s'] : (date('m') > 8 ? (1 + (2 * ($groups[$filter['group_id']]->course - 1))) : (2 + (2 * ($groups[$filter['group_id']]->course - 1))));
        }

        $subjects = Dis::find();
        if ($filter['group_id']) {
            $s_ids = (new Query())
                ->select("dis")
                ->from("test_gr_dis")
                ->where([
                    "grup" => $filter['group_id']
                ]);

            $s_ids = $s_ids->column();

            if (!empty($s_ids)) {
                $subjects->andWhere(["in", "id", $s_ids]);
            }
        }

        $subjects->orderBy("dis.dis");

        $subjects->indexBy("id");
        $subjects = $subjects->all();

        $students = null;
        $tests = null;

        if ($filter['group_id']) {
            $students = UsersInfo::find()
                ->indexBy("id")
                ->andWhere([
                    "grup" => $filter['group_id']
                ])
                ->orderBy("last_name, first_name")
                ->all();

            $ids = array_keys($students);
        }

        if ($filter['subject_id'] AND $filter['group_id']) {

            $tests = TestStarted::find()
                ->andWhere([
                    "smstr" => $filter['s'],
                ])
                ->andWhere([
                    "in", "ui_id", $ids
                ])
                ->andWhere(["in", "t", array_keys(TestStarted::getAssignTypes($groups[$filter['group_id']]->form))])
                ->orderBy("ui_id, t");

            $tests->andWhere([
                "dis_id" => $filter['subject_id']
            ]);

            $tests = $tests->all();

            \Yii::$app->data->tests = ActiveRecord::arrayAttributes($tests, [], [], true);

        }

        if ($filter['group_id']) {
            \Yii::$app->breadCrumbs->addLink($groups[$filter['group_id']]->grup, Common::createUrl("/recorder/tests", [
                'filter' => [
                    'group_id' => $filter['group_id']
                ]
            ]));
        }

        if ($filter['subject_id']) {
            \Yii::$app->breadCrumbs->addLink($subjects[$filter['subject_id']]->dis, Common::createUrl("/recorder/tests", [
                'filter' => [
                    'subject_id' => $filter['subject_id'],
                    'group_id' => $filter['group_id']
                ]
            ]));
        }

        if ($filter['s']) {
            \Yii::$app->breadCrumbs->addLink(\Yii::t("main","{s} семестр", [
                "s" => $filter['s']
            ]), Common::createUrl("/recorder/index", [
                'filter' => [
                    'group_id' => $filter['group_id'],
                    'subject_id' => $filter['subject_id'],
                    "s" => $filter['s']
                ]
            ]));
        }

        if (!empty($filter)) {
            \Yii::$app->data->filter = $filter;
        }

        if ($filter['group_id']) {
            $group = Grup::find()
                ->with([
                    "fakR",
                    "otdR",
                    "formR",
                    "specR"
                ])->byPk($filter['group_id'])->one();
        }

        return $this->render("tests", [
            "filter" => $filter,
            "subjects" => $subjects,
            "group" => $group,
            "groups" => $groups,
            "students" => $students,
            "tests" => $tests
        ]);
    }

    public function actionSubjects()
    {

        $filter = new SubjectsFilter();
        if (\Yii::$app->request->get("filter")) {
            $filter->attributes = \Yii::$app->request->get("filter");
        }

        $subjects = Dis::find()->orderBy("dis");
        $subjects = $filter->appendFilter($subjects);

        $count = $subjects->count();
        $page = \Yii::$app->request->get("page", 1);

        $subjects->offset(($page*50) - 50);
        $subjects->limit(50);

        $pagination = new Pagination([
            'totalCount' => $count,
        ]);

        $subjects = $subjects->all();

        return $this->render("subjects", [
            "subjects" => $subjects,
            "filter" => $filter,
            "pagination" => $pagination
        ]);

    }

    public function actionAccess()
    {

        $filter = \Yii::$app->request->get("filter", []);

        $students = UsersInfo::find()
            ->indexBy("id")
            ->orderBy("last_name, first_name")
            ->with("group")
            ->all();

        if (isset($filter['student_id'])) {
            $student = $students[$filter['student_id']];
            $group = Grup::findOne($student->grup);
        }

        if (isset($filter['student_id']) AND $filter['s']) {

            $assigned_subjects = (new Query())
                ->select("dis")
                ->from("test_gr_dis")
                ->where([
                    "grup" => $student->grup,
                ])
                ->andWhere("semestr = :s OR semestr = 0", [
                    ":s" => $filter['s']
                ])
                ->column();

            $subjects = Dis::find()
                ->andWhere([
                    "in",
                    "id",
                    $assigned_subjects
                ])
                ->all();

            $tests = TestStarted::find()
                ->andWhere([
                    "smstr" => $filter['s'],
                ])
                ->andWhere([
                    "ui_id" => $student->id
                ])
                ->andWhere("dis_id IS NOT NULL")
                ->andWhere(["in", "t", array_keys(TestStarted::getAssignTypes($group->form))])
                ->orderBy("dis_id, t");

            $tests = $tests->all();

        }

        \Yii::$app->data->filter = $filter;

        return $this->render("access", [
            "filter" => $filter,
            "students" => $students,
            "subjects" => isset($subjects) ? $subjects : [],
            "group" => isset($group) ? $group : null,
            "tests" => isset($tests) ? $tests : null
        ]);

    }

}
?>