<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // add "roleEmployee" permission
        $roleEmployee = $auth->createPermission('roleEmployee');
        $roleEmployee->description = 'Роль сотрудника';
        $auth->add($roleEmployee);

        // add "roleAdmin" permission
        $roleAdmin = $auth->createPermission('roleAdmin');
        $roleAdmin->description = 'Роль администратора';
        $auth->add($roleAdmin);

        // add "author" role and give this role the "createPost" permission
        $employee = $auth->createRole('employee');
        $auth->add($employee);
        $auth->addChild($employee, $roleEmployee);

        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $roleAdmin);
        $auth->addChild($admin, $employee);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($employee, 2);
        $auth->assign($admin, 1);
    }
}
