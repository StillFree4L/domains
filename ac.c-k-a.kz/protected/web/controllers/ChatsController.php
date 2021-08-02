<?php

namespace app\controllers;

use app\components\Controller;
use glob\components\ActiveRecord;
use glob\models\ChatMembers;
use glob\models\Chats;
use glob\models\Comments;
use glob\models\TargetTypes;
use glob\models\Users;
use yii\base\Exception;
use yii\filters\AccessControl;

class ChatsController extends Controller
{

    public function beforeAction($action) {
        $p = parent::beforeAction($action);
        \Yii::$app->breadCrumbs->addLink(\Yii::t("main","Чаты"), \Yii::$app->urlManager->createUrl("/chats/index"));
        return $p;
    }

    public function actionIndex()
    {

        $ids = array_keys(ChatMembers::find()->signed()->andWhere([
            "user_id"=>\Yii::$app->user->id
        ])->indexBy("chat_id")->asArray()->all());
        $chats = Chats::find()->andWhere(["in", "id", $ids])->with([
            "members" => function($query){
                return $query->indexBy("user_id")->joinWith(["user.myAd"]);
            },
            "lastMessage"
        ])->orderBy("chats.last_ts DESC")->all();
        \Yii::$app->data->chats = ActiveRecord::arrayAttributes($chats, [
            "members" => ['relations'=> ['user' => ['relations' => ['myAd']]]],
            "lastMessage"
        ], [], true);

        return $this->render("index", [
            "chats" => $chats
        ]);

    }

    public function actionAdd()
    {

        if (!\Yii::$app->request->get("uid")) throw new Exception("NO UID");
        $user = Users::findOne(\Yii::$app->request->get("uid"));
        if (!$user OR $user->id == \Yii::$app->user->id) throw new Exception("WRONG UID");

        $chat = Chats::getChatByUser($user);
        if ($chat === false) {
            throw new Exception("ERROR CREATING CHAT ROOM");
        }

        \Yii::$app->response->redirect(\glob\helpers\Common::createUrl("/chats/view", ["id"=>$chat->id]));

    }

    public function actionView()
    {
        if (!\Yii::$app->request->get("id")) throw new Exception("NO CHAT_ID");
        $chat = Chats::find()->joinWith(["members"])->byPk(\Yii::$app->request->get("id"))->one();
        if (!$chat OR !$chat->isMember) throw new Exception("WRONG CHAT");

        /* @var $chat Chats */
        $chat->member->visit_ts = time();
        $chat->member->save();

        \Yii::$app->data->chat = ActiveRecord::arrayAttributes($chat, [
            "members",
        ], [], true);
        \Yii::$app->data->commentOptions = [
            "totalCount" => Comments::find()->andWhere([
                "target_id"=>$chat->id,
                "target_type"=>TargetTypes::chat
            ])->count(),
            "target_id" => $chat->id,
            "target_type" => TargetTypes::chat,
            "canComment" => \Yii::$app->user->isGuest ? false : true,
            "canDelete" => false
        ];

        return $this->render("view", [
            "chat" => $chat
        ]);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?']
                    ]
                    // everything else is denied by default
                ],
            ],
        ];
    }

}

?>