<?  
//include ("include/auth.php"); 



if (isset($_REQUEST['user_id'])) {
$user_id=$_REQUEST['user_id'];
echo $user_id;
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="latest-ev-table">
<tr>
<td height="1" class="le-header"><p align="center">Навигация</p> </td>
</tr>
<tr>
<td><img src="images/le-line.gif" width="193" height="1"></td>
</tr>
<tr>
<td height="100%" class="le-content">
<a href="index.php">Главная</a><br>
Ведомости<br>
Журнал<br>
<a href=arhifved.php>Архив ведомостей</a><br>
</td>
</tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="latest-ev-table">
<tr>
<td height="1" class="le-header"><p align="center">2 семестр</p> </td>
</tr>
<tr>
<td><img src="images/le-line.gif" width="193" height="1"></td>
</tr>
<tr>
<td height="100%" class="le-content">
<?
echo "<a href=r1_2.php?sem=2&user_id=$user_id>Рубежный 1</a><br><br>";
echo "<a href=r2_2.php?sem=2&user_id=$user_id>Рубежный 2</a><br><br>";
//echo "//<a href=>Курсовая</a><br><br>";
echo "//<a href=rd.php?sem=2&user_id=$user_id>Рейтинг допуска</a><br><br>";
echo "<a href=view_test.php?user_id=$user_id>Tестирования</a><br><br>";
//echo "//<a href=inpit_oral_exam.php?user_id=$user_id>Устная форма</a><br><br>";
//echo "//<a href=exam.php?user_id=$user_id>Экзаменационный</a><br><br>";
//echo "//<a href=one.php?user_id=$user_id>Список двоишников</a><br><br>";
//echo "//<a href=constate21.php?user_id=$user_id>Сводная ведомасть</a><br><br>";
//echo "//<a href=constate1k.php?user_id=$user_id>Сводная ведомасть 1 курса </a><br><br>";
echo "//<a href=academ.php?user_id=$user_id>Академическая разница</a><br><br>";
echo "//<a href=indiv.php?user_id=$user_id>Индивидуальная ведомасть</a><br><br>";
echo "//<a href=indiv1.php?user_id=$user_id>Индивидуальная ведомасть2</a><br><br>";
//echo "//<a href=itog.php?user_id=$user_id>Итоговый</a><br><br>";
//<a href="retest.php">Пересдача тестирования</a><br><br>
//<a href="reoral.php">Пересдача Устная форма</a><br><br>
//<a href="two.php">Пересдача ведомасть</a><br><br>
?>
</td>

</tr>
</table>         


<table width="100%" border="0" cellpadding="0" cellspacing="0" class="latest-ev-table">
<tr>
<td height="1" class="le-header"><p align="center">Тестирования 4 курса</p> </td>
</tr>
<tr>
<td><img src="images/le-line.gif" width="193" height="1"></td>
</tr>
<tr>
<td height="100%" class="le-content">
<a href="view.php">Просмотр</a><br>
</td>
</tr>
</table>  

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="latest-ev-table">
<tr>
<td height="1" class="le-header"><p align="center">Анкетирование 1-3 курс</p> </td>
</tr>
<tr>
<td><img src="images/le-line.gif" width="193" height="1"></td>
</tr>
<tr>
<td height="100%" class="le-content">
<a href="view_anketa.php">Просмотр</a><br>
</td>
</tr>
</table>  




<table width="100%" border="0" cellpadding="0" cellspacing="0" class="latest-ev-table">
<tr>
<td height="1" class="le-header"><p align="center">Магистратура</p> </td>
</tr>
<tr>
<td><img src="images/le-line.gif" width="193" height="1"></td>
</tr>
<tr>
<td height="100%" class="le-content">
<a href="view_test_mag.php">Просмотр</a><br>
</td>
</tr>
</table>  
<?
}
else echo "Вы вели не правильно";
?>