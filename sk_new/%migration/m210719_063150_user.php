<?php

use yii\db\Migration;
use yii\rbac\DbManager;

/**
 * Class m210719_063150_user
 */
class m210719_063150_user extends Migration
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

$this->createTable('user', [
    'id' => $this->primaryKey(),
    'username' => $this->string()->notNull()->unique(),
    'auth_key' => $this->string(32)->notNull(),
    'password_hash' => $this->string()->notNull(),
    'password_reset_token' => $this->string()->unique(),
    'email' => $this->string()->notNull()->unique(),
    'status' => $this->smallInteger()->notNull()->defaultValue(10),
    'created_at' => $this->integer()->notNull(),
    'updated_at' => $this->integer()->notNull(),
], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210719_063150_user cannot be reverted.\n";
        $this->dropTable('user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210719_063150_user cannot be reverted.\n";

        return false;
    }
    */
}
