<?php

namespace glob\models;
use glob\components\ActiveRecord;

/**
 * This is the model class for table "dics".
 *
 * The followings are the available columns in table 'dics':
 * @property integer $id
 * @property string $name
 * @property integer $ts
 * @property string $info
 */
class Dics extends ActiveRecord
{

    public $excel_file = null;

    public function getValues()
    {
        return $this->hasMany(DicValues::className(), ["dic_id"=>"id"]);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            [['name'], 'required'],
            [['excel_file'], 'safe'],
            [['name'], 'checkLatin'],
            [['ts'], 'number', 'integerOnly'=>true],
            [['name'], 'string', 'max'=>'255'],
            [['name', 'info'], 'safe']
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {

        if ($this->excel_file) {
            $url = $this->excel_file;
            if (!$url) {
                throw new Exception("NO FILE URL");
            }

            $path = \Yii::getAlias("@webroot");
            include($path."/protected/vendors/PHPExcel/PHPExcel.php");

            $f = pathinfo($url);
            $temp = fopen(FILES_ROOT."/import", 'w');
            fwrite($temp, file_get_contents($url));

            try {
                $inputFileType = \PHPExcel_IOFactory::identify(FILES_ROOT."/import");
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load(FILES_ROOT."/import");
            } catch(Exception $e) {
                fclose($temp);
                die('Error loading file "'.pathinfo($path,PATHINFO_BASENAME).'": '.$e->getMessage());
            }

            /* @var $objPHPExcel \PHPExcel */
            $sheet = $objPHPExcel->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $rowsData = [];
            for ($row = 1; $row <= $highestRow; $row++){
                //  Read a row of data into an array
                $rowsData[] = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                    NULL,
                    TRUE,
                    TRUE);
                //  Insert row data array into your database of choice here
            }

            fclose($temp);
            unlink(FILES_ROOT."/import");
            if ($rowsData) {
                foreach ($rowsData as $r) {
                    if ($r[0]) {
                        if ($r[0][0]) {
                            $dv = new DicValues();
                            $dv->refresh();
                            $dv->name = $r[0][0];
                            $dv->dic_id = $this->id;
                            if ($r[0][1]) {
                                $dv->category = $r[0][1];
                            }
                            $dv->save();
                        }
                    }
                }
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public function checkLatin()
    {
        if (preg_match('/[^\\p{Common}\\p{Latin}]/u', $this->name) == 1) {
            $this->addError("name", \Yii::t("main","Должен содержать только латинские буквы"));
            return false;
        }
        return true;
    }

    public function getDescription()
    {
        return $this->jInfo['description'];
    }

}
