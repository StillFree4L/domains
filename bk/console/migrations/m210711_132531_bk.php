<?php

use yii\db\Migration;

/**
 * Class m210711_132531_bk
 */
class m210711_132531_bk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('type_of_route_section',[
            'id'=>$this->primaryKey(),
            'attribute_code'=>$this->string(12)->notNull(),
            'name'=>$this->string(40)->notNull()->check("(name='Начальный') or (name='Промежуточный') or (name='Конечный')"),
        ]);
        $this->createTable('settlements',[
            'id'=>$this->primaryKey(),
            'locality_code'=>$this->integer()->notNull().' AUTO_INCREMENT',
            'name'=>$this->string(40)->notNull(),
        ]);
        $this->createTable('point_of_arrival',[
           'id'=>$this->primaryKey(),
           'ticket_number'=>$this->smallInteger()->notNull(),
            'locality_code'=>$this->integer()->notNull(),
        ]);
        $this->createTable('point_of_departure',[
            'id'=>$this->primaryKey(),
            'ticket_number'=>$this->smallInteger()->notNull(),
            'locality_code'=>$this->integer()->notNull(),
        ]);
        $this->createTable('car_type',[
            'id'=>$this->primaryKey(),
            'wagon_type_code'=>$this->smallInteger()->notNull(),
            'name'=>$this->string(40)->notNull(),
            'number_of_seats_in_the_car'=>$this->smallInteger()->notNull()->check('number_of_seats_in_the_car >= 0'),
        ]);
        $this->createTable('schedule',[
            'id'=>$this->primaryKey(),
            'flight_number'=>$this->smallInteger()->notNull(),
            'day_of_the_week_of_departure'=>$this->string(10)->notNull(),
            'departure_time'=>$this->time()->notNull(),
            'day_of_the_week_of_arrival '=>$this->string(10)->notNull(),
            'arrival_time'=>$this->time()->notNull(),
            'travel_time'=>$this->time()->notNull(),
        ]);
        $this->createTable('places',[
            'id'=>$this->primaryKey(),
            'flight_number'=>$this->smallInteger()->notNull(),
            'wagon_number'=>$this->smallInteger()->notNull()->check('wagon_number > 0'),
            'type_of_car'=>$this->smallInteger()->notNull(),
            'departure_date'=>$this->date()->notNull(),
            'seat_number'=>$this->smallInteger()->notNull()->check('seat_number > 0'),
            'sign'=>$this->string(40)->notNull()->check("(sign = 'Свободно) OR (sign = 'Продано')")
        ]);
        $this->createTable('cashier',[
            'id'=>$this->primaryKey(),
            'cashier_code'=>$this->integer()->notNull().' AUTO_INCREMENT',
            'full_name_of_the_cashier'=>$this->string(100)->notNull(),
        ]);
        $this->createTable('ticket',[
            'id'=>$this->primaryKey(),
            'ticket_number'=>$this->smallInteger()->notNull(),
            'flight_number'=>$this->smallInteger()->notNull(),
            'sale/return_sign'=>$this->string(40)->notNull()->check("(sale/return sign = 'Продажа') OR (sale/return sign = 'Возврат')"),
            'sale/return_date'=>$this->date()->notNull(),
            'departure_date'=>$this->date()->notNull(),
            'full_name_of_the_passenger'=>$this->string(100)->notNull(),
            'passport_id'=>$this->string(12)->notNull()->unique(),
            'cashier_code'=>$this->integer()->notNull(),
            'sum'=>$this->money()->notNull()->check('sum > 0'),
            'wagon_number'=>$this->smallInteger()->notNull(),
            'seat_number'=>$this->smallInteger()->notNull(),
            'type_of_car'=>$this->smallInteger()->notNull(),
        ]);
        $this->createTable('route',[
            'flight_number'=>$this->smallInteger()->notNull(),
            'route_segment_type_attribute'=>$this->string(12)->notNull(),
            'flight_leg_number'=>$this->smallInteger()->notNull(),
            'locality_code'=>$this->integer()->notNull(),
            'mileage_of_the_site'=>$this->smallInteger()->notNull()->check('mileage_of_the_site > 0'),
            'departure_time'=>$this->time()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('type_of_route_section');
        $this->dropTable('settlements');
        $this->dropTable('point_of_arrival');
        $this->dropTable('point_of_departure');
        $this->dropTable('car_type');
        $this->dropTable('schedule');
        $this->dropTable('places');
        $this->dropTable('cashier');
        $this->dropTable('ticket');
        $this->dropTable('route');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210711_132531_bk cannot be reverted.\n";

        return false;
    }
    */
}
