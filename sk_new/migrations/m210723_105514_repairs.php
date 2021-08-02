<?php

use yii\db\Migration;

/**
 * Class m210723_105514_repairs
 */
class m210723_105514_repairs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    /*  $tableOptions = null;

      if ($this->db->driverName === 'mysql') {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
      }

    $this->createTable('repairs', [
    'id' => $this->primaryKey(),
    'date'=>$this->datetime()->notNull(),
    'client'=$this->string()->notNull(),


    'created_at' => $this->integer()->notNull(),
    'updated_at' => $this->integer()->notNull(),
  ], $tableOptions);*/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210723_105514_repairs cannot be reverted.\n";
        //$this->dropTable('user');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210723_105514_repairs cannot be reverted.\n";

        return false;
    }
    */
}
