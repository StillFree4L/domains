<?php

use yii\db\Migration;

/**
 * Class m210723_110432_employee
 */
class m210723_110432_employee extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $tableOptions = null;

      if ($this->db->driverName === 'mysql') {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
      }

    $this->createTable('employee', [
    'id' => $this->primaryKey(),
    'username' => $this->string(200)->notNull()->unique(),
    'email' => $this->string(100)->notNull()->unique(),
    'role' => $this->string(200)->notNull(),
    'date' => $this->date()->notNull(),
    'about' => $this->string()->notNull(),
    'created_at' => $this->integer()->notNull(),
    'updated_at' => $this->integer()->notNull(),
  ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210723_110432_employee cannot be reverted.\n";
        $this->dropTable('user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210723_110432_employee cannot be reverted.\n";

        return false;
    }
    */
}
