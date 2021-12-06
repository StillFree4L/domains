<?php

use yii\db\Migration;

/**
 * Class m210718_122521_sk
 */
class m210718_122521_sk extends Migration
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

    $this->createTable('repairs', [
      'id' => $this->primaryKey(),
      'receipt' => $this->integer()->notNull(),
      'date'=>$this->date()->notNull()->defaultExpression('CURRENT_DATE'),
      'client'=>$this->string()->notNull(),
      'phone'=>$this->string()->notNull(),
      'service_name'=>$this->string()->notNull(),
      'equipment'=>$this->string()->notNull(),
      'serial_id'=>$this->string()->notNull(),
      'facilities'=>$this->string()->notNull(),
      'problem'=>$this->string()->notNull(),
      'username'=>$this->string()->notNull(),
      'money' => $this->integer()->notNull()->defaultValue(0),
      'result_name'=>$this->string()->notNull(),
      'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
      'created_at' => $this->timestamp()->defaultExpression('NOW()'),

    ], $tableOptions);

    $this->createTable('services', [
      'id' => $this->primaryKey(),
      'service'=>$this->string()->notNull()->unique(),
      'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
      'created_at' => $this->timestamp()->defaultExpression('NOW()'),

    ], $tableOptions);

    $this->insert('services', [
            'service' => 'Диагностика, Настройка',
        ]);
    $this->insert('services', [
            'service' => 'Профилактика, Чистка, Смазка',
        ]);
    $this->insert('services', [
            'service' => 'Ремонт, Замена, Установка',
        ]);

    $this->createTable('results', [
      'id' => $this->primaryKey(),
      'result'=>$this->string()->notNull()->unique(),
      'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
      'created_at' => $this->timestamp()->defaultExpression('NOW()'),

    ], $tableOptions);

    $this->insert('results', [
            'result' => 'Отказ от услуг',
        ]);
    $this->insert('results', [
            'result' => 'Завершен',
        ]);

    $this->createTable('master', [
      'id' => $this->primaryKey(),
      'name'=>$this->string()->notNull(),
      'role'=>$this->string()->notNull(),
      'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
      'created_at' => $this->timestamp()->defaultExpression('NOW()'),

    ], $tableOptions);

    $this->createTable('user', [
      'id' => $this->primaryKey(),
      'username'=>$this->string()->notNull()->unique(),
      'auth_key' => $this->integer()->notNull(),
      'password_reset_token'=>$this->string()->unique(),
      'password_hash' => $this->string()->notNull(),
      'email'=>$this->string()->notNull()->unique(),
      'status' => $this->integer()->notNull()->defaultValue(10),
      'updated_at' => $this->integer()->notNull(),
      'created_at' => $this->integer()->notNull(),

    ], $tableOptions);

/*
    $this->createTable('repairs_audit', [
      'id' => $this->primaryKey(),
      'operation'=>$this->string()->notNull(),
      'changed_on' => $this->timestamp()->notNull(),
      'receipt' => $this->integer()->notNull(),
      'date'=>$this->date()->notNull()->defaultExpression('CURRENT_DATE'),
      'client'=>$this->string()->notNull(),
      'phone'=>$this->string()->notNull(),
      'service_name'=>$this->string()->notNull(),
      'equipment'=>$this->string()->notNull(),
      'serial_id'=>$this->string()->notNull(),
      'facilities'=>$this->string()->notNull(),
      'problem'=>$this->string()->notNull(),
      'username'=>$this->string()->notNull(),
      'money' => $this->integer()->notNull()->defaultValue(0),
      'result_name'=>$this->string()->notNull(),

    ], $tableOptions);
    */

   $this->createTable('sertificat', [
      'id' => $this->primaryKey(),
      'name'=>$this->string()->notNull(),
      'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
      'created_at' => $this->timestamp()->defaultExpression('NOW()'),

    ], $tableOptions);

        $this->createTable('clients', [
            'id' => $this->primaryKey(),
            'client'=>$this->string()->notNull()->unique(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE NOW()'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()'),

        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210718_122521_sk cannot be reverted.\n";

        $this->dropTable('repairs');
        $this->dropTable('services');
        $this->dropTable('results');
        $this->dropTable('master');
        $this->dropTable('user');
        $this->dropTable('repairs_audit');
        $this->dropTable('sertificat');
        $this->dropTable('clients');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210718_122521_sk cannot be reverted.\n";

        return false;
    }
    */
}
