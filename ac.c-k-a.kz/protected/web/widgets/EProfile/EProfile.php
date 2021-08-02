<?php

namespace app\widgets\EProfile;

class EProfile extends \app\components\Widget
{

    public $size = 'small';
    public $logout = false;

    /**
     * If static, then model must be provided
     * @var null
     */
    public $model = null;
    public $userType = null;

    public $htmlOptions = [];

    public $link = [];

    public function run()
    {

        if (!isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] = "profile profile-".$this->size." clearfix";
        } else {
            $this->htmlOptions['class'] .= " profile profile-".$this->size." clearfix";
        }

        if ($this->model == null) return false;

        if ($this->type == self::TYPE_TEMPLATE)
        {
            $m = new \stdClass();
            $m->instance = $this->model;
            $m->id = "<%=".$m->instance.".id"."%>";
            $m->fio = "<%=".$m->instance.".fio"."%>";
            $m->roleName = "<%=".$m->instance.".roleName"."%>";
            $this->model = $m;
        }

        return $this->render("profile");

    }

}

?>