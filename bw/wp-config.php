<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'bw' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'root' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', 'root' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'D%>~m-]QK$D=_rN?9MhOYOF^mvfzZ3),$zpAJPYS%S<r:T`y2S}/N/&KA|.`dSx^' );
define( 'SECURE_AUTH_KEY',  'v2mN(e5Rxwi2Qb:` ~51FkHsS#wn,1E=@EuWvb j56OJ9l//L)C62.cHSozpPz:u' );
define( 'LOGGED_IN_KEY',    'ZCDez[TZH}TaGh^:w^W<ir-QU)Q`Sm~V5uB3( ]bGG}UHRm3o8Tonm^py0^d+jJ&' );
define( 'NONCE_KEY',        'LN6m0:6 K&NSUV_)E1OT/~M3S-ZA&Eh/cL72VewwKTH.@hsLz.B]$xf[@&G%?ER~' );
define( 'AUTH_SALT',        'PulIHxeN.`_%6q{u?kDvE5D`naD7YS&Wd-LL?pnjc5QA!yj:w: 4buF=S9l},VTd' );
define( 'SECURE_AUTH_SALT', 'wUh1E{Q[_V-rj!%&-4|7OH.uOPj=igj+l9Ac;ic@*RtCJ;Fghz*WxVj-fUO>aw}4' );
define( 'LOGGED_IN_SALT',   'FrM_XxSHu~M!/V?{FYQUV7s5Hs:cx6H6ql^CN$kfQUeYt)3Ek)aG!?2Qds@e)eTI' );
define( 'NONCE_SALT',       'RSGS{F>l8PkW|st*JzpCT2Y%0YG8!V*h%):bULO`%s/Y&0Uk:WR:h^v1&r.:5lXG' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
define('FS_METHOD','direct');
/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';

define(‘FS_METHOD’,’direct’);
