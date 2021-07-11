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
        /*Table*/
        $this->createTable('type_of_route_section',[
            'id'=>$this->primaryKey(),
            'attribute_code'=>$this->string(12)->notNull()->unique(),
            'name'=>$this->string(40)->notNull()->check("(name='Начальный') or (name='Промежуточный') or (name='Конечный')"),
        ]);
        $this->createTable('settlements',[
            'id'=>$this->primaryKey(),
            'locality_code'=>$this->integer()->notNull()->unique(),
            'name'=>$this->string(40)->notNull()->unique(),
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
            'wagon_type_code'=>$this->smallInteger()->notNull()->unique(),
            'name'=>$this->string(40)->notNull(),
            'number_of_seats_in_the_car'=>$this->smallInteger()->notNull()->check('number_of_seats_in_the_car >= 0'),
        ]);
        $this->createTable('schedule',[
            'id'=>$this->primaryKey(),
            'flight_number'=>$this->smallInteger()->notNull()->unique(),
            'day_week_departure'=>$this->string(10)->notNull(),
            'departure_time'=>$this->time()->notNull(),
            'day_week_arrival'=>$this->string(10)->notNull(),
            'arrival_time'=>$this->time()->notNull(),
            'travel_time'=>$this->time()->notNull(),
        ]);
        $this->createTable('place',[
            'id'=>$this->primaryKey(),
            'flight_number'=>$this->smallInteger()->notNull(),
            'wagon_number'=>$this->smallInteger()->notNull()->check('wagon_number > 0'),
            'type_of_car'=>$this->smallInteger()->notNull(),
            'departure_date'=>$this->date()->notNull(),
            'seat_number'=>$this->smallInteger()->notNull()->check('seat_number > 0'),
            'sign'=>$this->string(40)->notNull()->check("(sign = 'Свободно') OR (sign = 'Продано')")
        ]);
        $this->createTable('cashier',[
            'id'=>$this->primaryKey(),
            'cashier_code'=>$this->integer()->notNull()->unique(),
            'full_name_of_the_cashier'=>$this->string(100)->notNull(),
        ]);
        $this->createTable('ticket',[
            'id'=>$this->primaryKey(),
            'ticket_number'=>$this->smallInteger()->notNull()->unique(),
            'flight_number'=>$this->smallInteger()->notNull(),
            'sale/return_sign'=>$this->string(40)->notNull()->check("(sale/return_sign = 'Продажа') OR (sale/return_sign = 'Возврат')"),
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
            'id'=>$this->primaryKey(),
            'flight_number'=>$this->smallInteger()->notNull(),
            'route_segment_type_attribute'=>$this->string(12)->notNull(),
            'flight_leg_number'=>$this->smallInteger()->notNull(),
            'locality_code'=>$this->integer()->notNull(),
            'mileage_of_the_site'=>$this->smallInteger()->notNull()->check('mileage_of_the_site > 0'),
            'departure_time'=>$this->time()->notNull(),
        ]);

        /*ForeignKey*/
        /*point*/
        $this->addForeignKey(
            'point_of_arrival_code',
            'point_of_arrival',
            'locality_code',
            'settlements',
            'locality_code',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'point_of_departure_code',
            'point_of_departure',
            'locality_code',
            'settlements',
            'locality_code',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'point_of_arrival_ticket',
            'point_of_arrival',
            'ticket_number',
            'ticket',
            'ticket_number',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'point_of_departure_ticket',
            'point_of_departure',
            'ticket_number',
            'ticket',
            'ticket_number',
            'CASCADE',
            'CASCADE'
        );
        /*place*/
        $this->addForeignKey(
            'place_flight',
            'place',
            'flight_number',
            'schedule',
            'flight_number',
            'NO ACTION',
            'CASCADE'
        );
        $this->addForeignKey(
            'place_type',
            'place',
            'type_of_car',
            'car_type',
            'wagon_type_code',
            'NO ACTION',
            'CASCADE'
        );
        /*ticket*/
        $this->addForeignKey(
            'ticket_flight',
            'ticket',
            'flight_number',
            'schedule',
            'flight_number',
            'NO ACTION',
            'CASCADE'
        );
        $this->addForeignKey(
            'ticket_cashier',
            'ticket',
            'cashier_code',
            'cashier',
            'cashier_code',
            'NO ACTION',
            'CASCADE'
        );
        $this->addForeignKey(
            'ticket_type',
            'ticket',
            'type_of_car',
            'car_type',
            'wagon_type_code',
            'NO ACTION',
            'CASCADE'
        );
        /*route*/
        $this->addForeignKey(
            'route_flight',
            'route',
            'flight_number',
            'schedule',
            'flight_number',
            'NO ACTION',
            'CASCADE'
        );
        $this->addForeignKey(
            'route_attribute',
            'route',
            'route_segment_type_attribute',
            'type_of_route_section',
            'attribute_code',
            'NO ACTION',
            'CASCADE'
        );
        $this->addForeignKey(
            'route_code',
            'route',
            'locality_code',
            'settlements',
            'locality_code',
            'NO ACTION',
            'CASCADE'
        );
        /*Index*/
        $this->createIndex(
            'point_arrival',
            'point_of_arrival',
            ['locality_code','ticket_number']
        );
        $this->createIndex(
            'point_departure',
            'point_of_departure',
            ['locality_code','ticket_number']
        );
        $this->createIndex(
            'places',
            'place',
            ['flight_number','type_of_car']
        );
        $this->createIndex(
            'tickets',
            'ticket',
            ['flight_number','cashier_code','wagon_number','seat_number','type_of_car']
        );
        $this->createIndex(
            'routes',
            'route',
            ['flight_number','route_segment_type_attribute','locality_code','departure_time']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        /*Table*/
        $this->dropTable('type_of_route_section');
        $this->dropTable('settlements');
        $this->dropTable('point_of_arrival');
        $this->dropTable('point_of_departure');
        $this->dropTable('car_type');
        $this->dropTable('schedule');
        $this->dropTable('place');
        $this->dropTable('cashier');
        $this->dropTable('ticket');
        $this->dropTable('route');
        /*ForeignKey*/
        $this->dropForeignKey('point_of_arrival_code','settlements');
        $this->dropForeignKey('point_of_departure_code','settlements');
        $this->dropForeignKey('point_of_arrival_ticket','ticket');
        $this->dropForeignKey('point_of_departure_ticket','ticket');
        $this->dropForeignKey('place_flight','schedule');
        $this->dropForeignKey('place_type','car_type');
        $this->dropForeignKey('ticket_flight','schedule');
        $this->dropForeignKey('ticket_cashier','cashier');
        $this->dropForeignKey('ticket_wagon','place');
        $this->dropForeignKey('ticket_seat','place');
        $this->dropForeignKey('ticket_type','car_type');
        $this->dropForeignKey('route_flight','schedule');
        $this->dropForeignKey('route_attribute','type_of_route_section');
        $this->dropForeignKey('route_code','settlements');
        $this->dropForeignKey('route_departure','schedule');
        /*Index*/
        $this->dropIndex('point_arrival','point_of_arrival');
        $this->dropIndex('point_departure','point_of_departure');
        $this->dropIndex('places','place');
        $this->dropIndex('tickets','ticket');
        $this->dropIndex('routes','route');
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
