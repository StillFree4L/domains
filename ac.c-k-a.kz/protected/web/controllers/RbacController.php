<?php
namespace app\controllers;

use glob\models\Users;
use yii;

class RbacController extends yii\base\Controller
{
    public function actionInit()
    {
        $authManager = \Yii::$app->authManager;
        $authManager->removeAll();

        // Create roles
        $student = $authManager->createRole(Users::ROLE_STUDENT);
        $teacher = $authManager->createRole(Users::ROLE_TEACHER);
        $registration = $authManager->createRole(Users::ROLE_REGISTRATION);
        $otdel = $authManager->createRole(Users::ROLE_OTDELCADROV);
        $comitet = $authManager->createRole(Users::ROLE_SELECTION_COMITET);
        $admin = $authManager->createRole(Users::ROLE_ADMIN);
        $super = $authManager->createRole(Users::ROLE_SUPER);

        $authManager->add($student);
        $authManager->add($teacher);
        $authManager->add($registration);
        $authManager->add($otdel);
        $authManager->add($comitet);
        $authManager->add($admin);
        $authManager->add($super);

        $authManager->addChild($admin, $student);
        $authManager->addChild($admin, $teacher);
        $authManager->addChild($admin, $registration);
        $authManager->addChild($admin, $otdel);
        $authManager->addChild($admin, $comitet);
        $authManager->addChild($super, $admin);
    }
}
?>
