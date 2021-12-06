<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacStartController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // добавляем роль "user"
        $user = $auth->createRole('user');
        $auth->add($user);

        // добавляем роль "master"
        $master = $auth->createRole('master');
        $auth->add($master);

        // добавляем роль "admin"
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $auth->addChild($admin, $user, $master);
   }

}