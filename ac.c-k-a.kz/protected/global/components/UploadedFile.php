<?php

namespace glob\components;

use yii\helpers\Html;

class UploadedFile extends \yii\web\UploadedFile
{

    public $previews = [
        "preview"=>[
            150,
            150
        ]
    ];


    public $crop = false;

    /**
     * @return string the file extension name for {@link name}.
     * The extension name does not include the dot character. An empty string
     * is returned if {@link name} does not have an extension name.
     */
    public function getExtensionName()
    {
        if(($pos=strrpos($this->name,'.'))!==false)
            return (string)substr($this->name,$pos+1);
        else
            return '';
    }

    protected static function dirhash($string, $length = 8) {

        // Convert to a string which may contain only characters [0-9a-p]
        $hash = base_convert(md5($string), 16, 26);

        // Get part of the string
        $hash = substr($hash, -$length);

        // In rare cases it will be too short, add zeroes
        $hash = str_pad($hash, $length, '0', STR_PAD_LEFT);

        // Convert character set from [0-9a-p] to [a-z]
        $hash = strtr($hash, '0123456789', 'qrstuvwxyz');

        return $hash;
    }

    protected static function getServer()
    {
        return [
            "path"=>FILES_ROOT,
            "host"=>FILES_HOST
        ];
    }

    public static function getUploadDirectory() {

        $server_info = self::getServer();

        $path = "";

        $yearDir = self::dirhash(date('Y'));
        if (!is_dir($server_info['path'].$path.$yearDir)) {
            mkdir($server_info['path'].$path.$yearDir);
        }

        $monthDir = self::dirhash(date('m'));
        if (!is_dir($server_info['path'].$path.$yearDir."/".$monthDir)) {
            mkdir($server_info['path'].$path.$yearDir."/".$monthDir);
        }

        $dayDir = self::dirhash(date('d'));
        if (!is_dir($server_info['path'].$path.$yearDir."/".$monthDir."/".$dayDir)) {
            mkdir($server_info['path'].$path.$yearDir."/".$monthDir."/".$dayDir);
        }

        return [
            "host"=>$server_info['host'].$path.$yearDir."/".$monthDir."/".$dayDir."/",
            "path"=>$server_info['path'].$path.$yearDir."/".$monthDir."/".$dayDir."/"
        ];

    }

    protected function getHashedFileName() {
        $ext = explode(".",$this->name);
        $ext = $ext[count($ext)-1];
        $fname = uniqid(md5($this->name)).".".$ext;
        return $fname;
    }

    public function save()
    {

        $d = self::getUploadDirectory();
        $n = $this->getHashedFileName();
        $path = $d['path'].$n;

        if (in_array($this->type, [
                "image/png",
                "image/jpeg",
            ]) AND $this->crop AND count($this->crop)>1) {

            $result = [
                "name"=>$this->name,
                "ext"=>$this->getExtensionName(),
                "type"=>$this->type,
                "size"=>$this->size
            ];

            /**
             * Image handler component
             */
            $ih = new CImageHandler();
            /* @var $ih CImageHandler */
            $ih->load($this->tempName);

            // Get crops and save previews
            // Check if source image size bigger than original crop size
            $wp = 1;
            $hp = 1;
            if (isset($this->crop['original'])) {

                $sw = $ih->getWidth();
                $sh = $ih->getHeight();

                $ow = $this->crop['original']['w'];
                $oh = $this->crop['original']['h'];

                if ($sw>$ow)
                {
                    $wp = $sw/$ow;
                }
                if ($sh>$oh) {
                    $hp = $sh/$oh;
                }
            }

            if (isset($this->crop['crop'])) {

                $ih->reload();
                if (isset($this->crop['crop'])) {

                    $cc = $this->crop['crop'];
                    $ih->crop($cc['w']*$wp, $cc['h']*$hp, $cc['x']*$wp, $cc['y']*$hp);

                }

                if ($ih->save($path))
                {
                    $result['url'] = $d['host'].$n;
                }

            } else {
                if ($this->saveAs($path)) {
                    $result['url'] = $d['host'].$n;
                } else {
                    return false;
                }
            }

            foreach ($this->previews as $preview=>$sizes)
            {

                $ih->load($result['url']);
                if (isset($this->crop[$preview])) {

                    $cc = $this->crop[$preview];

                    $ih->crop($cc['w']*$wp, $cc['h']*$hp, $cc['x']*$wp, $cc['y']*$hp);

                }

                $ih->thumb($sizes[0],$sizes[1]);
                if ($ih->save($d['path'].$preview."_".$n))
                {
                    $result[$preview] = $d['host'].$preview."_".$n;
                }

            }

            return $result;

        } else {
            if ($this->saveAs($path)) {
                return [
                    "url"=>$d['host'].$n,
                    "name"=>$this->name,
                    "ext"=>$this->getExtensionName(),
                    "type"=>$this->type,
                    "size"=>$this->size
                ];
            }
        }
        return false;
    }

}
?>