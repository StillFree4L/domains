<?php

class CKFinderButton extends CWidget
{

    public $id;
    public $kcFinderPath;
    public $filespath;
    public $filesurl;
    private $baseurl;
    public $config;
    public $type = "images"; // flash, files


    public function init()
    {
        $dir = dirname(__FILE__) . "/../editor/source";
        $this->baseurl = Yii::app()->getAssetManager()->publish($dir);
    	$this->kcFinderPath = $this->baseurl."/kcfinder/";
        parent::init();
    }

    public function run()
    {

        if (empty($this->id)) return;

        if ($this->filespath&&$this->filesurl) {

            $urls = array(
                "images"=>$this->kcFinderPath.'browse.php?type=images',
                "flash"=>$this->kcFinderPath.'browse.php?type=flash',
                "files"=>$this->kcFinderPath.'browse.php?type=files'
            );

          $this->config['filebrowserBrowseUrl']  = $this->kcFinderPath.'browse.php?type=files';
          $this->config['filebrowserImageBrowseUrl']  = $this->kcFinderPath.'browse.php?type=images';
          $this->config['filebrowserFlashBrowseUrl']  = $this->kcFinderPath.'browse.php?type=flash';
          $this->config['filebrowserUploadUrl']  = $this->kcFinderPath.'upload.php?type=files';
          $this->config['filebrowserImageUploadUrl']  = $this->kcFinderPath.'upload.php?type=images';
          $this->config['filebrowserFlashUploadUrl']  = $this->kcFinderPath.'upload.php?type=flash';
          $session=new CHttpSession;
          $session->open();
          $session['KCFINDER'] = array(
                    'disabled'=>false,
                    'uploadURL'=>$this->filesurl,
                    'uploadDir'=>realpath($this->filespath).'/',
                    );
        }

        $this->render("index", array(
            "browserUrl"=>$urls[$this->type]
        ));

    }

}

?>