<?php

namespace glob\models;

use glob\components\ActiveRecord;
use Yii;

class Message extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grup_id','dis_id','from_id','to_id','timedate'], 'integer'],
            [['text','file'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('main', 'ID'),
            'dis_id' => Yii::t('main', 'Dis'),
      'grup_id' => Yii::t('main', 'Grup'),
      'from_id' => Yii::t('main', 'From'),
      'to_id' => Yii::t('main', 'To'),
      'timedate' => Yii::t('main', 'Date'),
      'text' => Yii::t('main', 'Text'),
      'file' => Yii::t('main', 'File'),
        ];
    }
  
  public static function activities($grup_id,$dis_id)
    {
    $echo = '';
    $active = (new Query)->select("user_id")->from("chat_activity")
        ->where(["and",
            ["grup_id"=>$grup_id],
            [">","date",time()-900],
              ["dis_id"=>$dis_id]
             ])->column();
    $users = UsersInfo::find()->select("user_id,last_name,first_name,middle_name")->andWhere(["in", "user_id", $active])
      ->indexBy('id')->all();
    $dates=ChatActivity::find()->select('user_id,date')->indexBy('id')
      ->andWhere(["in", "user_id", $active])->all();
    
    $echo .= "<h6>Онлайн <?= count($users)?></h6>";
    if($users)
    {
      foreach ($users as $user)
      {
        foreach ($dates as $date)
        {
          if($date->user_id == $user->user_id)
          {
            $time=$date->date;
          }
        }
        $echo .= "<div class=\"user\">
                    <div class=\"avatar\">
          <img src=\"https://bootdey.com/img/Content/avatar/avatar1.png\" alt=\"".$user->fio."\">";
        if($time< time()-300)
        {
          $echo .= '<div class="status offline"></div>'
        }else{
          $echo .= '<div class="status online"></div>'
        }
        $echo .= '</div>'
        $echo .= '<div class="name">'.$user->fio.'</div>'
        $echo .= '<div class="mood">'.Yii::$app->formatter->asRelativeTime($time).'</div></div>'
      }
    }
    
    return $echo;
    }
  
  public static function loads($grup_id,$dis_id,$all=null)
    {
    $echo = '';
    $results = Message::find()
      ->andWhere(["grup_id" => $grup_id,"dis_id"=>$dis_id])
      ->orderBy("timedate DESC");
    if($all==null)
    {$results->limit(20);}
      $results = $results->all();
    if($results)
    {
      $results = array_reverse($results, true);
        foreach($results as $result)
        {
            $ui = \glob\models\UsersInfo::find()->andWhere(["id" => $result->from_id])->limit(1)->one();
          if($ui)
          {
            $url=json_decode($result->file);
            if($result->from_id==\Yii::$app->user->identity->profile->id)
            {
              $echo .= "<div class='answer left'><div class='name'>".$ui->fio." <i id='".$result->id."' class='fa fa-times' onClick='send_request(\"del\",this.id)'></i></div><div class='text'>".$result->text."<br/><a target='_blank' download='".$url->name."' href='".$url->url."'>".$url->name."</a></div><div class='time'>".\Yii::$app->formatter->asRelativeTime($result->timedate)."</div></div>";
            }
            else 
            {
            $echo .= "<div class='answer right'><div class='name'>".$ui->fio." <i id='".$result->id."' class='fa fa-times'></i></div><div class='text'>".$result->text."<br/><a target='_blank' download='".$url->name."' href='".$url->url."'>".$url->name."</a></div><div class='time'>".\Yii::$app->formatter->asRelativeTime($result->timedate)."</div></div>";
            }
          }
          else
          {$echo .= "Нет пользователя!";}
        }
    }
    else
    {$echo = "Нет сообщений!";}
    return $echo;
    }
  
  public static function del($id,$grup_id,$dis_id)
  {
    $mess = Message::find()->byPk($id)->limit(1)->one();
    if($mess->file!=null)
    {
      $info=json_decode($mess->file);
      $path = str_replace("https://files.c-k-a.kz/",FILES_ROOT,$info->url);
      unlink($path);
    }
    if ($mess AND $mess->delete()) {
            \Yii::$app->session->setFlash("success",\Yii::t("main","Сообщение успешно удалено"));
        }
    return \glob\models\Message::loads($grup_id,$dis_id);
  }
  
  public static function send($message,$grup_id,$dis_id,$file=null)
    {
    $message = htmlspecialchars($message);
    $message = trim($message);
    $message = addslashes($message);
    //$file = json_encode($file);
    $text = new Message();
    $text->grup_id=$grup_id;
    $text->dis_id=$dis_id;
    $text->from_id=\Yii::$app->user->identity->profile->id;
    $text->timedate=time();
    $text->text=$message;
    if($file!=null)
    {
      $name = uniqid();
      $dir = substr(md5(microtime()),mt_rand(0,30),6).'/'.substr(md5(microtime()),mt_rand(0,30),6);
      $path = $dir.'/'.$name;
      $fileName = '/var/www/vhosts/v-4762.webspace/www/files.c-k-a.kz/'.$dir;
      mkdir($fileName, 0777, true);
      $base64_decode  = base64_decode($file["base64StringFile"]);
      $dirSave = $fileName.'/'.$name.'.'.$file["fileType"];
      $jinfo = array(
            'url' => 'https://files.c-k-a.kz/'.$path.'.'.$file["fileType"],
            'name' => $file["fileName"],
            'ext' => $file["fileType"],
          );
      $info=json_encode($jinfo);
      $text->file=$info;
      file_put_contents($dirSave, $base64_decode);
    }
    if (!$text->save()) 
    {
      $this->addError("id", \Yii::t("main","При отправке возникла ошибка."));
            return false;
        }

    return \glob\models\Message::loads($grup_id,$dis_id);
    }
}
