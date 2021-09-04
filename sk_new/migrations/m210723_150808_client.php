<?php

use yii\db\Migration;

/**
 * Class m210723_150808_client
 */
class m210723_150808_client extends Migration
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

      $this->createTable('client', [
      'id' => $this->primaryKey(),
      'client'=>$this->string()->notNull(),
      'view'=>$this->string()->notNull(),
      'img'=>$this->string(),
      'date'=>$this->date(),
      'created_at' => $this->integer()->notNull(),
      'updated_at' => $this->integer()->notNull(),
    ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210723_150808_client cannot be reverted.\n";
        $this->dropTable('client');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210723_150808_client cannot be reverted.\n";

        return false;
    }
    */
}
