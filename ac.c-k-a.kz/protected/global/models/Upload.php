<?php

namespace glob\models;

use glob\components\Model;
use glob\components\UploadedFile;

class Upload extends Model
{

    public $file;
    public $name;
    public $cropCoordinates;


    const MTYPE_TEXT = 1;
    const MTYPE_DOCUMENT = 2;
    const MTYPE_AUDIO = 3;
    const MTYPE_VIDEO = 4;
    const MTYPE_PRESENTATION = 5;
    const MTYPE_ARCHIVE = 6;
    const MTYPE_IMAGE = 7;
    const MTYPE_LINK = 8;

    public function rules()
    {
        return [
            [['file'], 'file', /*'extensions' => self::getExtensions()*/],
            [['name', 'cropCoordinates'], 'safe']
        ];
    }

    public static function getIcon($file)
    {
        $exts = [
            'zip' => ['zip','rar'],
            'doc' => ['docx'],
            'flp' => ['flp'],
        ];

        $icon_types = [
            'image/' => "img",
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xls',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'ppt',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'doc',
            'video/' => 'video',
            'text/' => 'txt',
            'audio/' => 'audio',
            'application/pdf' => 'pdf',
            'application/x-rar-compressed' => 'zip',
            'application/zip' => 'zip'
        ];

        $i = 'file';


        foreach ($exts as $k => $ext)
        {
            if (in_array($file['ext'],$ext)) {
                return $k;
            }
        }

        foreach ($icon_types as $k=>$t) {
            if ($file['type'] == $k OR strpos($file['type'], $k)!== false) {
                return $t;
            }
        }

        return $i;

    }

    public static function getExtensions($type = null)
    {
        $ext = [
            Upload::MTYPE_DOCUMENT => ['txt','htm','html','rtf','doc','docx','xls','xlsx','pdf'],
            Upload::MTYPE_AUDIO => ['mp3','waf'],
            Upload::MTYPE_VIDEO => ['mp4'],
            Upload::MTYPE_PRESENTATION => ['ppt','pptx'],
            Upload::MTYPE_ARCHIVE => ['zip','gz','rar'],
            Upload::MTYPE_IMAGE => ['jpg','png','gif','jpeg','tif','tiff']
        ];
        //if ($type != null) return $ext[$type];
        $r = [];
        foreach ($ext as $e) {
            $r = array_merge($r,$e);
        }
        return $r;

    }

    public function insertAccess($attributes = [])
    {
        if (!\Yii::$app->user->isGuest) return true;
        return false;
    }

    public function insertRequest($attributes)
    {

        $this->attributes = $attributes;

        $this->file = UploadedFile::getInstance($this, "file");
        $this->file->name = $this->name;

        if ($this->validate()) {
            if ($this->cropCoordinates) {
                $this->file->crop = $this->cropCoordinates;
            }
            if ($r = $this->file->save())
            {
                return $r;
            } else {
                $this->addError("file", Yii::t("main","Ошибка загрузки на сервер"));
            }

        } else {

            return false;

        }

    }

}
?>