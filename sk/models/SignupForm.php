<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{

    public $username;
    public $email;
    public $password;
    public $role;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['role', 'string'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'email' => 'E-mail',
            'role' => 'Роль',
            'password' => 'Пароль',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     * @throws \Exception
     */
   /* public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

    if($user->save()){
        $auth = Yii::$app->authManager;
        if($this->role){
            $role = $auth->getRole($this->role);
        }else{
            $role = $auth->getRole('user');
        }
        //var_dump($this->role);
        $auth->assign($role, $user->getId());

        return $user;
    }
    return null;
    }*/

    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save(false);

            // нужно добавить следующие три строки:
          /*  $auth = Yii::$app->authManager;
            if($this->role){
                $role = $auth->getRole($this->role);
            }else{
                $role = $auth->getRole('user');
            }
          */
            $connection = Yii::$app->db;
            $connection->createCommand()->insert('auth_assignment', ['item_name' => $this->role, 'user_id' => $user->getId(), 'created_at' => time()])->execute();

            return $user;
        }

        return null;
    }
}