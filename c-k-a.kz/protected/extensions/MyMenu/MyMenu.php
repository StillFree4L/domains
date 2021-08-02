<?php
class MyMenu extends CWidget
{
    var $group = null;
    var $label = false;
    public function run()
    {
        if (empty($this->group))
        {
            $this->render("error",array(
                "error"=>t("Ошибка. Укажите группу меню")
            ));
            return;            

        }

        $group = MenuGroups::model()->findByAttributes(array("uniq_name"=>$this->group));
        $menu = Menu::model()->byGroup($group->id)->top()->findAll();

        if (!$menu)
        {
            $this->render("error",array(
                "error"=>t("Ошибка. Меню отсутствует")
            ));
            return;            
        }

        $this->render($this->group, array(
            "menu"=>$menu,
            "group"=>$group,
        ));

    }
}
?>
