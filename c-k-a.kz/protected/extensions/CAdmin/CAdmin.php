<?php
class CAdmin extends CWidget
{

    var $instance = null;

    public function run()
    {

        if ($this->instance == null OR !isset($this->instance->caption)) return;

        $this->render("index", array(
            "instance"=>$this->instance
        ));

    }

}
?>
