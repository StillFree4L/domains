<?php

namespace glob\models;

use Yii;

/**
 * This is the model class for table "test_answers".
 *
 * @property integer $id
 * @property string $mark
 * @property string $answer
 * @property integer $correct
 * @property integer $question_id
 *
 * @property TestQuestions $question
 */
class TestAnswers extends \glob\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_answers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mark', 'answer', 'question_id'], 'required'],
            [['answer'], 'string'],
            [['correct', 'question_id'], 'integer'],
            [['mark'], 'string', 'max' => 1],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestQuestions::className(), 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'mark' => Yii::t('main', 'Mark'),
            'answer' => Yii::t('main', 'Answer'),
            'correct' => Yii::t('main', 'Correct'),
            'question_id' => Yii::t('main', 'Question ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(TestQuestions::className(), ['id' => 'question_id']);
    }
}
