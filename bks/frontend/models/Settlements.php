<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "settlements".
 *
 * @property int $id
 * @property int $locality_code
 * @property string $name
 *
 * @property PointOfArrival[] $pointOfArrivals
 * @property PointOfDeparture[] $pointOfDepartures
 * @property Route[] $routes
 */
class Settlements extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settlements';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['locality_code', 'name'], 'required'],
            [['locality_code'], 'integer'],
            [['name'], 'string', 'max' => 40],
            [['locality_code'], 'unique'],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'locality_code' => 'Locality Code',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[PointOfArrivals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPointOfArrivals()
    {
        return $this->hasMany(PointOfArrival::className(), ['locality_code' => 'locality_code']);
    }

    /**
     * Gets query for [[PointOfDepartures]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPointOfDepartures()
    {
        return $this->hasMany(PointOfDeparture::className(), ['locality_code' => 'locality_code']);
    }

    /**
     * Gets query for [[Routes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes()
    {
        return $this->hasMany(Route::className(), ['locality_code' => 'locality_code']);
    }
}
