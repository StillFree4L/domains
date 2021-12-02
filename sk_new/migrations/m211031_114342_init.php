<?php

use yii\db\Migration;

/**
 * Class m211031_114342_init
 */
class m211031_114342_init extends Migration
{
    /*public function up()
    {
        $this->createTable('message', [
            'id' => $this->primaryKey(),
            'from' => $this->integer()->notNull(),
            'to' => $this->integer()->notNull(),
            'text' => $this->text()->notNull()
        ]);
    }*/

    public function down()
    {
        $this->dropTable('message');
    }
    
    public function up()
    {
        $this->createTable('message', [
            1'id' => $this->primaryKey(),
            2'grup' => $this->integer(),
           3 'dis' => $this->integer(),
            4'from' => $this->integer()->notNull(),
           5 'to' => $this->integer()->notNull(),
            6'file' => $this->text(),
            7'date' => $this->integer()->notNull(),
            8'text' => $this->text()->notNull()
        ]);
    }
    
}
