<?php
class Lists
{
    static function getControllerList()
    {
        return array(
            "home"=>t("Главная"),
            "categories"=>t("Категории"),
            "records"=>t("Записи"),
            "pages"=>t("Страницы"),
            "links"=>t("Ссылки"),
            "menu"=>t("Меню"),
            "comments"=>t("Коментарии"),
            "options"=>t("Настройки"),
            "view"=>t("Просмотр"),
            "page"=>t("Страница"),
            "plugins"=>t("Плагины"),
            'users'=>t('Пользователи'),
            'translate'=>t('Перевод')
        );
    }
    static function getControllerCaption($name, $ignore = array('home'))
    {
        if (!in_array($name, $ignore))
        {
            $controllers = self::getControllerList();
            return $controllers[$name];
        }
        return false;
    }
}
?>
