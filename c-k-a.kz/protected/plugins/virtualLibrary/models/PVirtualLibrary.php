<?php

/**
 * This is the model class for table "p_virtual_library".
 *
 * The followings are the available columns in table 'p_virtual_library':
 * @property integer $id
 * @property string $book_name
 * @property integer $book_year
 * @property integer $book_price
 * @property string $book_lang
 * @property string $book_isbn
 * @property integer $book_country
 * @property integer $book_code
 * @property string $pub_view
 * @property string $pub_name
 * @property string $pub_city
 * @property string $pub_dep
 * @property integer $pub_code
 * @property string $library_name
 * @property string $author_name
 * @property integer $ts
 * @property string $book_link
 */
class PVirtualLibrary extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PVirtualLibrary the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'p_virtual_library';
	}

        public function defaultScope()
        {
            return array(
                "order" => "book_name, book_year"
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
			array('book_name', 'required'),
			array('book_year, book_price, pub_code, ts, author_code', 'numerical', 'integerOnly'=>true),
			array('book_name, pub_name, pub_dep, library_name, author_name', 'length', 'max'=>1000),
			array('book_lang, book_isbn, pub_city, book_link', 'length', 'max'=>255),
			array('pub_view', 'length', 'max'=>2000),
                        array('book_preview','length','max'=>10000),
                        array('book_code, book_isbn', 'checkForUnique'),
                        array("book_name", "checkForEmpty"),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('author_code, id, book_name, book_year, book_price, book_lang, book_isbn, book_country, book_code, pub_view, pub_name, pub_city, pub_dep, pub_code, library_name, author_name, ts, book_link, book_preview', 'safe', 'on'=>'search'),
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'book_name' => t("Название книги"),
			'book_year' => t("Год издания"),
			'book_price' => t("Цена"),
			'book_lang' => t("Языки"),
			'book_isbn' => t("isbn"),
			'book_country' => t("Страна"),
			'book_code' => t("Код"),
                        'book_preview'=>t("Краткое содержание книги"),
			'pub_view' => t("Вид издания"),
			'pub_name' => t("Издатель"),
			'pub_city' => t("Город"),
			'pub_dep' => t("Раздел"),
			'pub_code' => t("код издателя"),
			'library_name' => t("Название библиотеки"),
			'author_name' => t("Авторы"),
			'ts' => 'Ts',
			'book_link' => t("Ссылка на книгу"),
                        'author_code' => t("Автор(Код)")
		);
	}
        
        public function checkForEmpty()
        {
            if (trim($this->book_name) == "") return false;
        }
        
        public function checkForUnique()
        {
            
            if ($this->isNewRecord AND PVirtualLibrary::model()->resetScope()->exists("book_name = :name AND book_year = :year AND book_code = :bcode", array(
                ":name"=>$this->book_name,
                ":year"=>$this->book_year,
                ":bcode"=>$this->book_code,                
                    )))
            {
                $this->addError("book_name",t("Такая книга уже существует"));
				return false;
            }

            if ($this->isNewRecord AND PVirtualLibrary::model()->resetScope()->exists("book_isbn = :book_isbn", array(":book_isbn"=>$this->book_isbn)))
            {
                $this->addError("book_isbn",t("Книга с isbn ".$this->book_isbn." уже существует"));
				return false;
            }
            return true;
            
        }
            

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
		$criteria->compare('book_name',$this->book_name,true,'OR');
                $criteria->compare('book_year',$this->book_year,true,'OR');
		$criteria->compare('book_code',$this->book_code,true,'OR');
		$criteria->compare('pub_view',$this->pub_view,true,'OR');
		$criteria->compare('pub_name',$this->pub_name,true,'OR');
		$criteria->compare('pub_code',$this->pub_code,true,'OR');
		$criteria->compare('author_name',$this->author_name,true,'OR');		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        "pagination"=>array(
                            "pageSize"=>100
                        )
		));
	}
}