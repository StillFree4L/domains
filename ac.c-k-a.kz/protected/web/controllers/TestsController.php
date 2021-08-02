<?php

namespace app\controllers;
use app\components\Controller;
use glob\components\ActiveRecord;
use glob\helpers\Common;
use glob\models\Dis;
use glob\models\Grup;
use glob\models\TestQuestions;
use glob\models\TestStarted;
use glob\models\Users;
use glob\models\UsersInfo;
use yii\base\Exception;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Class MainController
 * @package app\controllers
 */
class TestsController extends Controller {

    public function beforeAction($action) {
        $p = parent::beforeAction($action);

        if (\Yii::$app->user->identity->checkLock()) {
            throw new Exception("Учетная запись заблокирована. Данный студент в текущий момент проходит тест");
        }

        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Тестирование"), \Yii::$app->urlManager->createUrl("/tests/index"));
        return $p;
    }

    public function actionIndex()
    {

        $ui_id = \Yii::$app->user->identity->profile->id;
        $filter = \Yii::$app->request->get("filter");
        $group = \Yii::$app->user->identity->profile->group;
        if (\Yii::$app->user->can(Users::ROLE_SUPER)) {
            $groups = Grup::find()
                ->indexBy("id")
                ->all();
            if ($filter['group_id']) {
                $group = $groups[$filter['group_id']];
                $students = UsersInfo::find()
                    ->indexBy("id")
                    ->orderBy("last_name,first_name")
                    ->andWhere([
                        "grup" => $filter['group_id']
                    ])->all();
            }
            if ($filter['student_id']) {
                $ui_id = $filter['student_id'];
            }
        }

        if ($group) {
            $filter['s'] = $filter['s'] ? $filter['s'] : (date('m') > 8 ? (1 + (2 * ($group->course - 1))) : (2 + (2 * ($group->course - 1))));
        }

        $tests = TestStarted::find();
        $tests
            ->joinWith([
                "dis"
            ])
            ->orderBy("dis.dis, testdate DESC, t")
            ->andWhere([
                "finished" => 0,
                "smstr" => $filter['s'],
                "ui_id" => $ui_id
            ])
            ->andWhere(["in", "t", [1,2,6,5,7,8]]);
        $tests = $tests->all();

        if ($group) {
            $s_ids = (new Query())
                ->select("dis")
                ->from("test_gr_dis")
                ->where([
                    "grup" => $group->id
                ]);
            $s_ids->andWhere("semestr = :s OR semestr = 0", [
                ":s" => $filter['s']
            ]);
            $s_ids = $s_ids->column();
            if (!empty($s_ids)) {
                $subjects = Dis::find();
                $subjects->andWhere(["in", "id", $s_ids]);
                $subjects->orderBy("dis");
                $subjects = $subjects->all();
            }
        }

        $passed_tests = TestStarted::find();
        $passed_tests
            ->orderBy("testdate DESC, t")
            ->andWhere([
                "finished" => 1,
                "smstr" => $filter['s'],
                "ui_id" => $ui_id
            ]);
        $passed_tests = $passed_tests->all();

        /*if (!$tests AND !$passed_tests AND !\Yii::$app->user->can(Users::ROLE_SUPER)) {
            throw new Exception(\Yii::t("main", "Вам не назначено ниодного теста"));
        }*/

        \Yii::$app->data->filter = $filter;

        return $this->render("index", [
            "groups" => $groups,
            "group" => $group,
            "students" => $students,
            "filter" => $filter,
            "tests" => $tests,
            "passed_tests" => $passed_tests,
            "subjects" => $subjects
        ]);
    }

    public function actionStart()
    {

        $test = TestStarted::find()->byPk(\Yii::$app->request->get("id"))
            ->andWhere([
                "finished" => 0,
            ])
            ->andWhere(["in", "t", [1,2,6,5,7,8]])
            ->one();
        if (!$test) \Yii::$app->response->redirect(Common::createUrl("/tests/index"));
        if (!$test->can()) throw new Exception("Нет доступа");
        if ($test->started == 1) \Yii::$app->response->redirect(Common::createUrl("/tests/process", ["id"=>$test->id]));

        if (empty($test->jInfo['questions'])) {

            $questions = TestQuestions::find()
                ->distinct(true)
                ->indexBy("id")
                ->limit($test->qcount)
                ->orderBy("RAND()")
                ->andWhere([
                    "dis_id" => $test->dis_id
                ])
                ->all();

            if ($questions AND count($questions) == $test->qcount) {
                $test->setInfo("questions", array_keys($questions));
                $test->testtime = time();
                $test->started = 1;
                if ($test->save()) {

                    \Yii::$app->response->redirect(Common::createUrl("/tests/process", ["id"=>$test->id]));

                } else {
                    throw new Exception("Ошибка базы данных. Обратитесь к администратору");
                }

            } else {
                throw new Exception("Не хватает вопросов, чтобы составить тест");
            }

        }

    }

    public function actionProcess()
    {

        $test = TestStarted::find()->byPk(\Yii::$app->request->get("id"))
            ->andWhere([
                "finished" => 0,
                "started" => 1
            ])
            ->andWhere(["in", "t", [1,2,6,5,7,8]])
            ->one();
        if (!$test) \Yii::$app->response->redirect(Common::createUrl("/tests/index"));

        if ($test->isExpired) {
            \Yii::$app->response->redirect(Common::createUrl("/tests/finish", ["id"=>$test->id]));
        }

        $questions = TestQuestions::find()
            ->with(["testAnswers","correctCount"])
            ->andWhere(["in", "id", $test->jInfo['questions']])
            ->all();

        \Yii::$app->data->test = ActiveRecord::arrayAttributes($test, [], array_merge((new TestStarted())->attributes(), ["timeLeft", "jInfo"]), true);
        \Yii::$app->data->questions = ActiveRecord::arrayAttributes($questions, [
            "testAnswers" => [
                "fields" => ["id", "answer", "mark"]
            ],
        ], array_merge((new TestQuestions())->attributes(), ["correct_count"]), true);

        if (\Yii::$app->user->identity->role_id == Users::ROLE_STUDENT) {
            \Yii::$app->user->identity->lock();
        }

        \Yii::$app->params['in_test'] = 1;

        return $this->render("process", [
            "test" => $test,
            "questions" => $questions
        ]);

    }

    public function actionFinish()
    {
        $test = TestStarted::find()->byPk(\Yii::$app->request->get("id"))
            ->andWhere([
                "started" => 1
            ])
            ->andWhere(["in", "t", [1,2,5,6,7,8]])
            ->one();
        if (!$test) \Yii::$app->response->redirect(Common::createUrl("/tests/index"));

        if (!$test->finished) {
            $test->finish();
            \Yii::$app->user->identity->clearLock();
        }

        \Yii::$app->data->test = ActiveRecord::arrayAttributes($test, [], [], true);

        return $this->render("finished", [
            "test" => $test
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
                        'roles' => [Users::ROLE_STUDENT],
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