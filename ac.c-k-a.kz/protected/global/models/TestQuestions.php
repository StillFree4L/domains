<?php

namespace glob\models;

use Yii;

/**
 * This is the model class for table "test_questions".
 *
 * @property integer $id
 * @property string $question
 * @property string $score_template
 * @property integer $dis_id
 * @property string $info
 *
 * @property TestAnswers[] $testAnswers
 */
class TestQuestions extends \glob\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_questions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'dis_id'], 'required'],
            [['question', 'info'], 'string'],
            [['dis_id'], 'integer'],
            [['score_template'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'question' => Yii::t('main', 'Question'),
            'score_template' => Yii::t('main', 'Score Template'),
            'dis_id' => Yii::t('main', 'Dis ID'),
            'info' => Yii::t('main', 'Info'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestAnswers()
    {
        return $this->hasMany(TestAnswers::className(), ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCorrectCount()
    {

        return $this->hasMany(TestAnswers::className(), ['question_id' => 'id'])->andWhere(["correct" => "1"]);
    }

    public function getCorrect_count()
    {
        return count($this->correctCount);
    }

    public function getScore_templateA()
    {
        return is_array($this->score_template) ? $this->score_template : json_decode($this->score_template, true);
    }

}
