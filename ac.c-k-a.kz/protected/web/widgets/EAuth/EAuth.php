<?php

namespace app\widgets\EAuth;

/**
 * Виджет авторизации пользователя
 * Проверяет, если пользователь не авторизован, предлагает ему авторизоваться
 * В противном случае показываем мини профайл с ссылкой
 *
 * Class EAuth
 */
class EAuth extends \app\components\Widget
{

    public function run()
    {

        return $this->render("index");

    }

}
?>