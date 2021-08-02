<?php
2  $dbase=mysql_connect('localhost', 'userok', 'parolchik');
3  if(!$dbase){
4?>
5<!DOCTYPE html>
6<html>
7<head>
8  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
9  <title>Не могу подключиться к БД</title>
10</head>
11<body>
12  <br /><br /><br />
13  <h1 align="center">Проверьте настройки подключения к БД</h1>
14</body>
15</html>
16<?php
17  exit;
18  }
19  mysql_select_db('db_name');
20  @mysql_query('set character_set_client="utf8"');
21  @mysql_query('set character_set_results="utf8"');
22  @mysql_query('set collation_connection="utf8_general_ci"');
23?>