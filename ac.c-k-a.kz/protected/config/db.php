<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host='.PG_HOST.';dbname='.PG_DATABASE,
    'username' => PG_USER,
    'password' => PG_PASSWORD,
    'charset' => 'utf8',
];
