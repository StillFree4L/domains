<?php

    namespace app\components;

    class View extends \yii\web\View
    {

        public function setTitle($title, $breadcrumbs = true)
        {
            $this->title = $title;
            \Yii::$app->data->pageTitle = $title;
            if ($breadcrumbs) \Yii::$app->breadCrumbs->addLink($title);
        }

        public function addTitle($title, $breadcrumbs = true)
        {
            $this->title = $this->title." ".$title;
            \Yii::$app->data->pageTitle = $this->title;
            if ($breadcrumbs) \Yii::$app->breadCrumbs->addLink($title);
        }

        public function registerCssFile($url, $options = [], $key = null)
        {

            if (!\Yii::$app->request->isAjax) {
                //run the parent method to parse the less/css file like Yii normally does.
                return parent::registerCssFile($url, $options, $key);
            } else {

                \Yii::$app->data->append("css",$url);

            }
        }

        public function registerJsFile($url, $options = [], $key = null)
        {
            if (!\Yii::$app->request->isAjax) {
                return parent::registerJsFile($url, $options, $key);
            } else {
                \Yii::$app->data->append("js",$url);
            }
        }

        public function registerJs($js, $position = self::POS_READY, $key = null, $options = [])
        {

            if (!\Yii::$app->request->isAjax) {
                //run the parent method to parse the less/css file like Yii normally does.
                return parent::registerJs($js, $position, $key);
            } else {
                if (!$key) $key = uniqid();
                $s = array("id" => $key, "script" => $js);
                if (isset($options['force'])) {
                    $s['force'] = true;
                }
                \Yii::$app->data->append("js", $s);

            }

        }

    }
?>