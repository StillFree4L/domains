<?php

namespace glob\models;

use glob\components\ActiveRecord;
use Yii;

/**
 * This is the model class for table "test_started".
 *
 * @property integer $id
 * @property integer $ui_id
 * @property integer $dis_id
 * @property integer $started
 * @property integer $finished
 * @property integer $ball
 * @property integer $answ
 * @property integer $testtime
 * @property integer $testdate
 * @property integer $access
 * @property integer $smstr
 * @property integer $t
 * @property integer $qcount
 * @property integer $additional
 * @property string $info
 *
 * @property TestProcess[] $testProcesses
 */
class TestStarted extends \glob\components\ActiveRecord
{

    public static function getTypes($form = 1)
    {

        $types = [
            1 => [
                "8" => \Yii::t("main","Текущий контроль"),
                "9" => \Yii::t("main","Текущий контроль 1"),
                "10" => \Yii::t("main","Текущий контроль 2"),
                "4" => \Yii::t("main","Курсовая работа"),
                "6" => \Yii::t("main","Рубежный контроль 1"),
                "7" => \Yii::t("main","Рубежный контроль 2"),
                "3" => \Yii::t("main","Рейтинг допуск"),
                "1" => \Yii::t("main","Экзамен"),
                "5" => \Yii::t("main","ВОУД")
            ],
            2 => [
                "8" => \Yii::t("main","Текущий контроль"),
                "4" => \Yii::t("main","Курсовая работа"),
                "2" => \Yii::t("main","Рубежный контроль"),
                "3" => \Yii::t("main","Рейтинг допуск"),
                "1" => \Yii::t("main","Экзамен"),
                "5" => \Yii::t("main","ВОУД")
            ],
            3 => [
                "8" => \Yii::t("main","Текущий контроль"),
                "4" => \Yii::t("main","Курсовая работа"),
                "2" => \Yii::t("main","Рубежный контроль"),
                "3" => \Yii::t("main","Рейтинг допуск"),
                "1" => \Yii::t("main","Экзамен"),
                "5" => \Yii::t("main","ВОУД")
            ]
        ];

        return $types[$form];

    }

    public static function getAssignTypes($form = 1)
    {

        $types = [
            1 => [
                "1" => \Yii::t("main","Экзамен"),
                "8" => \Yii::t("main","Текущий контроль"),
                "6" => \Yii::t("main","Рубежный контроль 1"),
                "7" => \Yii::t("main","Рубежный контроль 2")
            ],
            2 => [
                "1" => \Yii::t("main","Экзамен"),
                "8" => \Yii::t("main","Текущий контроль"),
                "2" => \Yii::t("main","Рубежный контроль")
            ],
            3 => [
                "1" => \Yii::t("main","Экзамен"),
                "8" => \Yii::t("main","Текущий контроль"),
                "2" => \Yii::t("main","Рубежный контроль"),
            ]
        ];

        return $types[$form];

    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_started';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ui_id', 'dis_id', 'started', 'finished', 'ball', 'answ', 'testtime', 'testdate', 'access', 'smstr', 't', 'qcount', 'additional'], 'integer'],
            [['info'], 'safe'],
            [['smstr'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'ui_id' => Yii::t('main', 'Ui ID'),
            'dis_id' => Yii::t('main', 'Dis ID'),
            'started' => Yii::t('main', 'Started'),
            'finished' => Yii::t('main', 'Finished'),
            'ball' => Yii::t('main', 'Ball'),
            'answ' => Yii::t('main', 'Answ'),
            'testtime' => Yii::t('main', 'Testtime'),
            'testdate' => Yii::t('main', 'Testdate'),
            'access' => Yii::t('main', 'Access'),
            'smstr' => Yii::t('main', 'Smstr'),
            't' => Yii::t('main', 'T'),
            'qcount' => Yii::t('main', 'Qcount'),
            'additional' => Yii::t('main', 'Additional'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestProcesses()
    {
        return $this->hasMany(TestProcess::className(), ['work_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDis()
    {
        return $this->hasOne(Dis::className(), ['id' => 'dis_id']);
    }

    public function getCanEdit()
    {

        if (!\Yii::$app->user->can(Users::ROLE_TEACHER)) return false;
        if ($this->t == 3) return false;

        if (in_array($this->t, ["4","8","9","10"])) {
            return true;
        }

        if (\Yii::$app->user->can(Users::ROLE_SUPER) AND IS_EDITABLE_JOURNAL AND !$this->isNewRecord)
        {
            return true;
        }

        return false;
    }

    public function getBallTextColor()
    {
        return self::ballTextColor($this->ball);
    }

    public function getTimeLeft()
    {
        return (90*$this->qcount) - (time() - $this->testtime);
    }

    public static function ballTextColor($ball)
    {
        if ($ball >= 90) {
            return "text-success";
        }
        if ($ball >= 70) {
            return "text-primary";
        }
        if ($ball >= 60) {
            return "text-warning";
        }
        if ($ball >= 50) {
            return "text-warning";
        }

        return "text-danger";
    }

    public static function getRDTypes($form)
    {
        $types = [
            1 => [
                9,6,10,7
            ],
            2 => [
                8,2
            ],
            3 => [
                8,2
            ]
        ];
        return $types[$form];
    }
    public static function getMaxBalls($tests, $form) {

        $max = [];
        if ($tests) {
            foreach ($tests as $it) {
                if ($it->ball > $max[$it->t]) {
                    $max[$it->t] = $it->ball;
                }
            }
        }

        $summ = 0;
        foreach (self::getRDTypes($form) as $type) {
            $summ += $max[$type];
        }

        $max[3] = ceil($summ/count(self::getRDTypes($form)));

        return $max;

    }

    public static function getDeployedMark($percent) {
        $ovm = intval($percent);
        if ($ovm) {
            switch ($ovm) {
                case $ovm>=95 AND $ovm<=100:
                    $ovmW = "A";
                    $ovmB = "4";
                    $ovmT = \Yii::t("main","Отл.");
                    break;
                case $ovm>=90 AND $ovm<=94:
                    $ovmW = "A-";
                    $ovmB = "3.67";
                    $ovmT = \Yii::t("main","Отл.");
                    break;
                case $ovm>=85 AND $ovm<=89:
                    $ovmW = "B+";
                    $ovmB = "3.33";
                    $ovmT = \Yii::t("main","Хор.");
                    break;
                case $ovm>=80 AND $ovm<=84:
                    $ovmW = "B";
                    $ovmB = "3";
                    $ovmT = \Yii::t("main","Хор.");
                    break;
                case $ovm>=75 AND $ovm<=79:
                    $ovmW = "B-";
                    $ovmB = "2.67";
                    $ovmT = \Yii::t("main","Хор.");
                    break;
                case $ovm>=70 AND $ovm<=74:
                    $ovmW = "C+";
                    $ovmB = "2.33";
                    $ovmT = \Yii::t("main","Хор.");
                    break;
                case $ovm>=65 AND $ovm<=69:
                    $ovmW = "C";
                    $ovmB = "2";
                    $ovmT = \Yii::t("main","Удов.");
                    break;
                case $ovm>=60 AND $ovm<=64:
                    $ovmW = "C-";
                    $ovmB = "1.67";
                    $ovmT = \Yii::t("main","Удов.");
                    break;
                case $ovm>=55 AND $ovm<=59:
                    $ovmW = "D";
                    $ovmB = "1.33";
                    $ovmT = \Yii::t("main","Удов.");
                    break;
                case $ovm>=50 AND $ovm<=54:
                    $ovmW = "D-";
                    $ovmB = "1";
                    $ovmT = \Yii::t("main","Удов.");
                    break;
                case $ovm>=0 AND $ovm<=49:
                    $ovmW = "F";
                    $ovmB = "0";
                    $ovmT = \Yii::t("main","Неуд.");
                    break;
            }
        }

        $res['percent'] = $ovm;
        $res['char'] = $ovmW;
        $res['ball'] = $ovmB;
        $res['classic'] = $ovmT;

        return $res;
    }

    public static function findStarted()
    {
        return self::find()->andWhere([
            "started" => 1,
            "finished" => 0,
            "ui_id" => \Yii::$app->user->identity->profile->id
        ]);
    }

    public function insertAccess($attributes = []) {
        if (in_array($attributes['act'], ["recorder","access","accessByStudent"]) AND \Yii::$app->user->can(Users::ROLE_REGISTRATION)) {
            return true;
        }
        if ($attributes['act'] == "journal" AND \Yii::$app->user->can(Users::ROLE_TEACHER)) {
            return true;
        }
        return false;
    }

    public function insertRequest($attributes)
    {
        if ($attributes['act'] == "journal") {
            if ($attributes['marks']) {
                foreach ($attributes['marks'] as $m) {

                    if ($m['id']) {
                        $mark = TestStarted::findOne($m['id']);
                        if ($mark) {
                            $mark->ball = $m['ball'];
                            $mark->finished = 1;
                        }
                    } else {
                        $mark = TestStarted::find();
                        $mark = $mark->andWhere([
                            "t" => $m['t'],
                            "smstr" => $m['smstr'],
                            "dis_id" => $m['dis_id'],
                            "ui_id" => $m['ui_id']
                        ])->one();

                        if ($mark) {
                            $mark->ball = $m['ball'];
                            $mark->finished = 1;
                        } else if (!$mark) {
                            $mark = new TestStarted();
                            $mark->ui_id = $m['ui_id'];
                            $mark->t = $m['t'];
                            $mark->smstr = $m['smstr'];
                            $mark->dis_id = $m['dis_id'];
                            $mark->testdate = strtotime($m['date']);
                            $mark->finished = 1;
                            $mark->ball = $m['ball'];
                        }
                    }

                    if ($mark AND $mark->canEdit) {
                        if (!$mark->save()) {
                            print_r($mark->getErrors());
                        }
                    }

                }
            }
        }

        if ($attributes['act'] == "recorder") {

            if (!empty($attributes['students'])) {

                if (empty($attributes['testdate'])) {
                    $this->addError("ui_id", \Yii::t("main","Не указана дата теста"));
                    return false;
                }

                $transaction = $this->getDb()->beginTransaction();

                foreach ($attributes['students'] as $student) {

                    $test = new TestStarted();
                    $test->ui_id = $student;
                    $test->testdate = strtotime($attributes['testdate']);
                    $test->t = $attributes['t'];
                    $test->dis_id = $attributes['dis_id'];
                    $test->smstr = $attributes['smstr'];
                    $test->qcount = $attributes['qcount'];

                    if (!$test->save()) {
                        $this->addError("id", \Yii::t("main","При сохранении возникла ошибка. Обратитесь к администратору"));
                        $transaction->rollBack();
                        return false;
                    }

                }

                $transaction->commit();

            } else {
                $this->addError("ui_id", \Yii::t("main","Не выбрано ниодного студента"));
                return false;
            }

        }

        if ($attributes['act'] == "access") {

            if (!empty($attributes['students'])) {

                $tests = TestStarted::find();
                $tests->andWhere(['in', 'ui_id', $attributes['students']]);

                if (!empty($attributes['testdate'])) {
                    $tests->andWhere([
                        "testdate" => strtotime($attributes['testdate'])
                    ]);
                }

                $tests->andWhere([
                    "t" => $attributes['t'],
                    "smstr" => $attributes['smstr'],
                    "dis_id" => $attributes['dis_id']
                ]);

                $tests = $tests->all();

                $transaction = $this->getDb()->beginTransaction();

                if ($tests) {

                    foreach ($tests as $test) {

                        $test->access = $attributes['access'];

                        if (!$test->save()) {
                            $this->addError("ui_id", \Yii::t("main","При сохранении возникла ошибка. Обратитесь к администратору"));
                            $transaction->rollBack();
                            return false;
                        }

                    }

                } else {
                    if (!$attributes['testdate']) {
                        $this->addError("ui_id", \Yii::t("main", "Тестов не назначено"));
                    } else {
                        $this->addError("ui_id", \Yii::t("main", "Тестов на \"{$attributes['testdate']}\" не назначено"));
                    }
                    $transaction->rollBack();
                    return false;
                }

                $transaction->commit();

            } else {
                $this->addError("ui_id", \Yii::t("main","Не выбрано ниодного студента"));
                return false;
            }

        }

        if ($attributes['act'] == "accessByStudent") {

            if (!empty($attributes['subjects'])) {

                $transaction = $this->getDb()->beginTransaction();
                foreach ($attributes['subjects'] as $t => $subjects) {

                    $tests = TestStarted::find();
                    $tests->andWhere(['in', 'dis_id', array_keys($subjects)]);

                    $tests->andWhere([
                        "t" => $t,
                        "smstr" => $attributes['semestr'],
                        "ui_id" => $attributes['ui_id']
                    ]);

                    $tests = $tests->all();

                    if ($tests) {

                        foreach ($tests as $test) {

                            $test->access = $subjects[$test->dis_id] ? 1 : 0;

                            if (!$test->save()) {
                                $this->addError("ui_id", \Yii::t("main", "При сохранении возникла ошибка. Обратитесь к администратору"));
                                $this->addErrors($test->getErrors());
                                $transaction->rollBack();
                                return false;
                            }
                        }

                    } else {
                        $this->addError("ui_id", \Yii::t("main", "Тестов не назначено"));
                        $transaction->rollBack();
                        return false;
                    }
                }

                $transaction->commit();

            } else {
                $this->addError("ui_id", \Yii::t("main","Не выбрано ниодного студента"));
                return false;
            }

        }

        return [];

    }

    public function can()
    {
        if ($this->ui_id == \Yii::$app->user->identity->profile->id OR \Yii::$app->user->can(Users::ROLE_SUPER))
        {
            if (in_array($this->t, [1,2,6,7,5,8])) {
                return true;
            }
        }
        return false;
    }

    public function getIsExpired()
    {
        if (($this->testtime+(90*$this->qcount))<time()) {
            return true;
        }
    }

    public function finish()
    {

        if ($this->finished == 1 OR $this->started == 0) return false;

        $questions = TestQuestions::find()
            ->indexBy("id")
            ->with(["testAnswers" => function($query) {
                return $query->indexBy("mark");
            }])
            ->andWhere(["in", "id", $this->jInfo['questions']])
            ->all();

        $correct_answers = 0;
        $ball = 0;
        foreach ($questions as $question) {

            $answs = $this->jInfo['answers'][$question->id];
            if ($answs) {
                $correct = 0;
                $wrong = 0;
                $correct_all = 0;
                foreach ($question->testAnswers as $answer) {
                    if ($answer->correct == 1 AND in_array($answer->mark, $answs)) {
                        $correct++;
                    }

                    if ($answer->correct == 0 AND in_array($answer->mark, $answs)) {
                        $wrong++;
                    }

                    if ($answer->correct == 1) {
                        $correct_all++;
                    }

                }
                $template = $question->score_templateA;
                if (is_array($template)) {
                    if ($wrong+$correct <= $correct_all)
                    {
                        $type = "count";
                        if (isset($template['type'])) { $type = $template['type']; }
                        switch ($type) {
                            case "count" :
                                if (isset($template[$correct]))
                                {
                                    $correct_answers++;
                                    $ball = $ball + $template[$correct];
                                } else if (isset($template["*"]) AND $correct == $correct_all)
                                {
                                    $correct_answers++;
                                    $ball = $ball + $template["*"];
                                }
                                break;
                            case "percent" :
                                $pct = ($correct * 100) / $correct_all;
                                foreach ($template as $pcnt=>$val) {
                                    if ($pcnt != "type") {
                                        $pcnt = explode("-",$pcnt);
                                        if (!isset($pcnt[1])) {
                                            if ($pct >= $pcnt[0]) {
                                                $correct_answers++;
                                                $ball = $ball+$val;
                                            }
                                        } else {
                                            if ($pct <= $pcnt[1] AND $pct >= $pcnt[0]) {
                                                $correct_answers++;
                                                $ball = $ball+$val;

                                            }
                                        }
                                    }
                                }

                                break;
                        }
                    }
                }

            }

        }

        $qcount = $this->qcount;
        if ($this->t == "5") {
            $qcount = $qcount*2;
        }

        $ball = ceil(($ball*100)/$qcount);
        if ($ball>0) {
            if ($ball<Options::byName("min_ball")) {
                $ball = mt_rand(Options::byName("min_ball") - 3,Options::byName("min_ball") + 3);
                $correct_answers = ceil($qcount*($ball/100));
            }
        }

        if ($ball>Options::byName("max_ball")) {

            $ball = mt_rand(Options::byName("max_ball") - 3,Options::byName("max_ball") + 3);
            $correct_answers = ceil($qcount*($ball/100));

        }

        $this->ball = $ball;
        $this->answ = $correct_answers;
        $this->finished = 1;
        if ($this->save()) {
            return true;
        }
        return false;

    }

    public function updateAccess($attributes = []) {
        return true;
    }
    public function updateRequest($attributes) {

        $test = TestStarted::find()->byPk($attributes['id'])
            ->andWhere([
                "finished" => 0,
                "started" => 1
            ])
            ->one();

        if ($test AND ($test->ui_id == \Yii::$app->user->identity->profile->id OR \Yii::$app->user->can(Users::ROLE_SUPER))) {

            if (!$test->isExpired) {
                $question = $attributes['question'];
                $answers = $test->jInfo['answers'];
                $answers[$question['id']] = $question['answers'];
                $test->setInfo("answers", $answers);
                $test->save();
            }

            return ActiveRecord::arrayAttributes($test, [], array_merge((new TestStarted())->attributes(), ["isExpired"]), true);
        }

        return false;

    }

}
