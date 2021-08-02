<?php

namespace app\controllers;
use app\components\Controller;
use app\components\VarDumper;
use glob\components\ActiveRecord;
use glob\helpers\Common;
use glob\models\Dis;
use glob\models\Grup;
use glob\models\Options;
use glob\models\TestStarted;
use glob\models\Users;
use glob\models\UsersInfo;
use yii\base\Exception;
use yii\db\Query;
use yii\filters\AccessControl;

/**
 * Class MainController
 * @package app\controllers
 */
class JournalController extends Controller {

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Журнал"), \Yii::$app->urlManager->createUrl("/journal/index"));
        return $p;
    }

    public function actionIndex()
    {

        $filter = \Yii::$app->request->get("filter", []);
        if ($filter['code']) {
            define("IS_EDITABLE_JOURNAL", (($filter['code'] == Options::byName("journal_code") AND \Yii::$app->user->can(Users::ROLE_SUPER) ) ? true : false));
        }

        $subjects = Dis::find();

        $s_ids = (new Query)
            ->select("dis_id")
            ->from("teacher_discipline")
            ->where([
                "teacher_id" => \Yii::$app->user->identity->profile->id
            ])->column();

        if (!\Yii::$app->user->can(Users::ROLE_SUPER)) {

            if (empty($s_ids)) {
                throw new Exception(\Yii::t("main","Вам не назначено ни одного предмета. Обратитесь в офис регистратора или к Администратору системы"));
            }

            $subjects->andWhere(["in", "id", $s_ids]);
        }


        $subjects->indexBy("id");
        $subjects = $subjects->all();

        $groups = Grup::find();
        $groups->indexBy("id");

        if ((!empty($s_ids) AND !\Yii::$app->user->can(Users::ROLE_SUPER)) OR $filter['subject_id']) {
            $g_ids = (new Query)
                ->select("grup")
                ->from("test_gr_dis")
                ->where([
                    "in",
                    "dis",
                    ($filter['subject_id'] ? [$filter['subject_id']] : $s_ids)
                ])->column();

            if (!empty($g_ids)) {
                $groups->andWhere(["in", "id", $g_ids]);
            }
        }

        $groups = $groups->all();
        if ($filter['group_id'] AND !isset($groups[$filter['group_id']])) {
            unset($filter['group_id']);
            unset($filter['s']);
            \Yii::$app->response->redirect(Common::createUrl("/journal/index", ["filter"=>$filter]));
        }

        if ($filter['subject_id'] AND $filter['group_id']) {
            $filter['s'] = $filter['s'] ? $filter['s'] : (date('m') > 8 ? (1 + (2 * ($groups[$filter['group_id']]->course - 1))) : (2 + (2 * ($groups[$filter['group_id']]->course - 1))));
        }

        $students = null;
        $tests = null;
        if ($filter['subject_id'] AND $filter['group_id']) {

            $students = UsersInfo::find()
                ->indexBy("id")
                ->andWhere([
                    "grup" => $filter['group_id']
                ])
                ->orderBy("last_name, first_name")
                ->all();

            $ids = array_keys($students);

            $tests = TestStarted::find()
                ->andWhere([
                    "dis_id" => $filter['subject_id'],
                    "smstr" => $filter['s'],
                ])
                ->andWhere([
                    "in", "ui_id", $ids
                ])
                ->orderBy("ui_id, t")
                ->all();

            \Yii::$app->data->tests = ActiveRecord::arrayAttributes($tests, [], [], true);

        }

        if ($filter['subject_id']) {
            \Yii::$app->breadCrumbs->addLink($subjects[$filter['subject_id']]->dis, Common::createUrl("/journal/index", [
                    'filter' => [
                        'subject_id' => $filter['subject_id']
                    ]
                ]));
        }

        if ($filter['group_id']) {
            \Yii::$app->breadCrumbs->addLink($groups[$filter['group_id']]->grup, Common::createUrl("/journal/index", [
                    'filter' => [
                        'subject_id' => $filter['subject_id'],
                        'group_id' => $filter['group_id']
                    ]
                ]));
        }

        if ($filter['s']) {
            \Yii::$app->breadCrumbs->addLink(\Yii::t("main","{s} семестр", [
                "s" => $filter['s']
            ]), Common::createUrl("/journal/index", [
                    'filter' => [
                        'subject_id' => $filter['subject_id'],
                        'group_id' => $filter['group_id'],
                        "s" => $filter['s']
                    ]
                ]));
        }

        \Yii::$app->data->filter = $filter;

        return $this->render("index", [
            "filter" => $filter,
            "subjects" => $subjects,
            "groups" => $groups,
            "students" => $students,
            "tests" => $tests
        ]);
    }

    public function actionCode()
    {
        if (\Yii::$app->request->get("code")) {
            \Yii::$app->response->redirect(Common::createUrl("/journal/index", [
                'filter' => array_merge(\Yii::$app->request->get('filter', []), [
                    'code' => \Yii::$app->request->get("code")
                ])
            ]));
        }
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