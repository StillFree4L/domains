<?php

namespace app\controllers;
use app\components\Controller;
use glob\components\ActiveRecord;
use glob\helpers\Common;
use glob\models\Dis;
use glob\models\Grup;
use glob\models\forms\ReportsForm;
use glob\models\TestStarted;
use glob\models\Users;
use glob\models\UsersInfo;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use yii\db\Query;
use yii\filters\AccessControl;

/**
 * Class MainController
 * @package app\controllers
 */
class ReportsController extends Controller {

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Отчеты"), \Yii::$app->urlManager->createUrl("/reports/index"));
        return $p;
    }

    public function actionIndex()
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

            if ($filter['t'] == "summary") {
                $s_ids->andWhere([
                    "semestr" => $filter['s']
                ]);
            }

            $s_ids = $s_ids->column();
            $subjects->andWhere(["in", "id", $s_ids]);
        }

        if ($filter['t'] == "summary") {
            $subjects->andWhere([
                "gek" => 0
            ]);
            $subjects->orderBy("dis.gos ,dis.kurs, dis.position, dis.dis");
        } else {
            $subjects->orderBy("dis.dis");
        }

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

        if (($filter['subject_id'] AND $filter['group_id']) OR ($filter['group_id'] AND $filter['t'])) {

            $tests = TestStarted::find()
                ->andWhere([
                    "smstr" => $filter['s'],
                ])
                ->andWhere([
                    "in", "ui_id", $ids
                ])
                ->orderBy("ui_id, t");

            if ($filter['t'] == "summary") {

                $dis_ids = array_keys($subjects);

                $tests->andWhere(["in", "dis_id", $dis_ids]);
            } else {
                $tests->andWhere([
                    "dis_id" => $filter['subject_id']
                ]);
            }

            $tests = $tests->all();

            \Yii::$app->data->tests = ActiveRecord::arrayAttributes($tests, [], [], true);

        }

        if ($filter['group_id']) {
            \Yii::$app->breadCrumbs->addLink($groups[$filter['group_id']]->grup, Common::createUrl("/reports/index", [
                'filter' => [
                    'group_id' => $filter['group_id']
                ]
            ]));
        }

        if ($filter['subject_id']) {
            \Yii::$app->breadCrumbs->addLink($subjects[$filter['subject_id']]->dis, Common::createUrl("/reports/index", [
                'filter' => [
                    'subject_id' => $filter['subject_id'],
                    'group_id' => $filter['group_id']
                ]
            ]));
        }

        if ($filter['s']) {
            \Yii::$app->breadCrumbs->addLink(\Yii::t("main","{s} семестр", [
                "s" => $filter['s']
            ]), Common::createUrl("/reports/index", [
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

        if (\Yii::$app->request->get("d")) {

            Settings::setTempDir(FILES_ROOT);

            $word = new PhpWord();
            $word->setDefaultFontName('Times New Roman');
            $word->setDefaultFontSize(8);
            $section = $word->addSection(ReportsForm::getTypeWordSettings(\Yii::$app->request->get("d")));

            $html = $this->renderPartial("report_".\Yii::$app->request->get("d"), [
                "filter" => $filter,
                "group" => $group,
                "subject" => $subjects[$filter['subject_id']],
                "subjects" => $subjects,
                "students" => $students,
                "tests" => $tests
            ]);
            $html = trim(preg_replace('/\t+/', '',str_replace(array("\r", "\n"), "", $html)));

            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html);

            header("Content-Type: application/msword" );
            header("Content-Disposition: attachment; filename=".ReportsForm::getTypes(\Yii::$app->request->get("d")).".docx" );

            $objWriter = IOFactory::createWriter($word, 'Word2007');
            $objWriter->save( "php://output" );
            die();

        }

        return $this->render("index", [
            "filter" => $filter,
            "subjects" => $subjects,
            "group" => $group,
            "groups" => $groups,
            "students" => $students,
            "tests" => $tests
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

}
?>