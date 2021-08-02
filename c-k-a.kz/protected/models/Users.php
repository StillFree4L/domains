<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $role
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property integer $sex
 * @property integer $birthdate
 * @property integer $ts
 */
class Users extends BaseActiveRecord
{

        const ROLE_ROOT = 'root';
	const ROLE_ADMIN = 'admin';
	const ROLE_USER = 'user';

        var $fio = "";
        var $repeatPassword = "";

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        public function getRoles()
	{
		return array(
			self::ROLE_ROOT => t('Главный администратор'),
			self::ROLE_ADMIN => t('Администратор'),
			self::ROLE_USER => t('Пользователь'),
		);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return parent::tableName("users");
	}

        public function defaultScope()
        {
            return array(
                "condition"=>"active = 1"
            );
        }

        public function getRegions()
        {
            return array(
                '0'=>'Абайский район',
                '1'=>'Актогайский район',
                '2'=>'Балхаш',
                '3'=>'Бухаржыраусский район',
                '4'=>'Жанаркинский район',
                '5'=>'Жезказган',
                '6'=>'Караганда',
                '7'=>'Каражал',
                '8'=>'Каркаралинский район',
                '9'=>'Нуринский район',
                '10'=>'Осакаровский район',
                '11'=>'Приозерск',
                '12'=>'Сарань',
                '13'=>'Сатпаев',
                '14'=>'Темиртау',
                '15'=>'Улытаусский район',
                '16'=>'Шахтинск',
                '17'=>'Шетский район',
                );
        }

        public function getRanks()
        {
            return array(
            '0'=>'Заведующий методическим кабинетом',
            '1'=>'Директор колледжа',
            '2'=>'Директор школы',
            '3'=>'Директор дошкольной организации',
            '4'=>'Директор внешкольной организации',
            '5'=>'Заместитель директора',
            '6'=>'Методист методического кабинета',
            '7'=>'Методист дошкольной организации',
            '8'=>'Методист внешкольной организации',
            '9'=>'Учитель',
            '10'=>'Преподаватель спец. дисциплин',
            '11'=>'Мастер',
            '12'=>'Воспитатель',
            '13'=>'Педагог-психолог',
            '14'=>'Социальный педагог',
			'15'=>'Методист'			
                );
        }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, password, last_name, first_name, email, repeatPassword', 'required'),
			array('sex, birthdate, ts, rank_id, region_id', 'numerical', 'integerOnly'=>true),
			array('login, password', 'length', 'max'=>32),
                        array('login, password', 'length', 'min'=>5),
			array('last_name, first_name, middle_name', 'length', 'max'=>64),
                        array('login, email', 'checkForUnique'),
                        array('login, password', 'checkForBadSymbols'),
                        array('password, repeatPassword', 'checkPassword'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('organization, id, login, password, role, last_name, first_name, middle_name, sex, birthdate, ts, rank_id, email, region_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

        public function  beforeValidate() {
            if (!$this->isNewRecord) $this->repeatPassword = $this->password;
            return parent::beforeValidate();

        }

        public function beforeSave()
        {

            if (empty($this->role)) $this->role = "user";



            if ($this->isNewRecord) {
                $this->password = md5($this->password);
            }

            return parent::beforeSave();
        }

        public function afterFind()
        {

            $this->fio = $this->last_name." ".$this->first_name;

            parent::afterFind();
        }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => t('Логин'),
			'password' => t('Пароль'),
			'role' => t('Роль'),
			'last_name' => t('Фамилия'),
			'first_name' => t('Имя'),
			'middle_name' => t('Отчество'),
			'sex' => t('Пол'),
			'birthdate' => t('Дата рождения'),
			'ts' => t('Ts'),
                        'rank_id' => t("Категория"),
                        'email' => t("Адрес электронной почты"),
                        "region_id" => t("Город/Район"),
                        "repeatPassword"=>t("Повторите пароль"),
                        "organization" => t("Укажите вашу организацию"),

		);
	}

        public function checkForUnique()
        {
            if ($this->isNewRecord AND Users::model()->resetScope()->exists("login = :login", array(":login"=>$this->login)))
            {
                $this->addError("login",t("Пользователь с таким логином уже существует"));
				return false;
            }

            if ($this->isNewRecord AND Users::model()->resetScope()->exists("email = :email", array(":email"=>$this->email)))
            {
                $this->addError("email",t("Данный почтовый адрес уже зарегистрирован на сайте"));
				return false;
            }
            return true;
        }

        public function checkPassword()
        {
            if ($this->isNewRecord AND $this->password != $this->repeatPassword)
            {
                $this->addError("repeatPassword",t("Пароли не совпадают"));
                return false;
            }
			return true;
        }

        public function checkForBadSymbols()
        {
            if (!preg_match('/^[a-zA-Z0-9_]+$/',$this->login)) {
                $this->addError("login",t("Логин может состоять только из латинских букв, цифр и знака \"_\""));
                return false;
            }
            if (!preg_match('/^[a-zA-Z0-9_]+$/',$this->password)) {
                $this->addError("password",t("Пароль может состоять только из латинских букв, цифр и знака \"_\""));
                return false;
            }
			return true;
        }

        public function activate($id)
        {

            if (Users::model()->resetScope()->exists("id = :id",array(":id"=>$id)))
            {

                $user = Users::model()->resetScope()->findByPk($id);
                $user->repeatPassword = $user->password;
                $user->active = "1";

                if ($user->save())
                {
                    return true;
                }


            }

                return false;
        }

        //
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('middle_name',$this->middle_name,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('birthdate',$this->birthdate);
		$criteria->compare('ts',$this->ts);
                $criteria->compare('region_id',$this->ts);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
