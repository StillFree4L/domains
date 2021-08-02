<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

return array(

		/**
		 * Roles
		 */

	    'guest' => array(
	        'type' => CAuthItem::TYPE_ROLE,
	        'description' => t('Гость'),
	        'bizRule' => null,
	        'data' => null
		),
	    Users::ROLE_USER => array(
	        'type' => CAuthItem::TYPE_ROLE,
	        'description' => t('Пользователь'),
	        'children' => array(
	            'guest'
			),
	        'bizRule' => null,
	        'data' => null
		),
	    Users::ROLE_ADMIN => array(
	        'type' => CAuthItem::TYPE_ROLE,
	        'description' => t('Администратор'),
	        'bizRule' => null,
	        'data' => null
		),
	    Users::ROLE_ROOT => array(
	        'type' => CAuthItem::TYPE_ROLE,
	        'description' => t('Главный администратор'),
	        'children' => array(
	            Users::ROLE_ADMIN,
			),
	        'bizRule' => null,
	        'data' => null
		),
	);

?>
