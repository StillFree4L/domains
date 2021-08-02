<?php
namespace glob\components;

use Yii;

class PhpManager extends \yii\rbac\PhpManager
{
    public function init()
    {
        parent::init();
    }

    public function getAssignments($userId)
    {
        if(!Yii::$app->user->isGuest){
            $assignment = new \yii\rbac\Assignment;
            $assignment->userId = $userId;
            $assignment->roleName = Yii::$app->user->identity->role_id;
            return [$assignment->roleName => $assignment];
        }
    }
}