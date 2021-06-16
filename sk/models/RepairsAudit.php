<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "repairs_audit".
 *
 * @property int $id
 * @property string $operation
 * @property string $changed_on
 * @property int $receipt
 * @property string $date
 * @property string $client
 * @property string $phone
 * @property string $service_name
 * @property string $equipment
 * @property string $serial_id
 * @property string $facilities
 * @property string $problem
 * @property string $username
 * @property int $money
 * @property string $result_name
 */
class RepairsAudit extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'repairs_audit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['operation', 'changed_on', 'receipt', 'client', 'phone', 'service_name', 'equipment', 'serial_id', 'facilities', 'problem', 'username', 'result_name'], 'required'],
            [['changed_on', 'date'], 'safe'],
            [['receipt', 'money'], 'default', 'value' => null],
            [['receipt', 'money'], 'integer'],
            [['operation'], 'string', 'max' => 1],
            [['client', 'service_name', 'equipment', 'facilities', 'problem', 'username', 'result_name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 30],
            [['serial_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'operation' => 'Операция',
            'changed_on' => 'Дата',
            'receipt' => 'Квитанция',
            'date' => 'Дата',
            'client' => 'Клиент',
            'phone' => 'Телефон',
            'service_name' => 'Услуги',
            'equipment' => 'Оборудование',
            'serial_id' => 'Серийный номер',
            'facilities' => 'Комплектация',
            'problem' => 'Неисправность',
            'username' => 'Мастер',
            'money' => 'Цена',
            'result_name' => 'Результат',
        ];
    }
}
