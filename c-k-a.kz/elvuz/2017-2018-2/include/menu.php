<?  
//include ("include/auth.php"); 



if (isset($_REQUEST['user_id'])) {
$user_id=$_REQUEST['user_id'];
echo $user_id;
?>




<table width="100%" border="0" cellpadding="0" cellspacing="0" class="latest-ev-table">
<tr>
<td height="1" class="le-header"><p align="center">2017-2018  ЛЕТО</p> </td>
</tr>
<tr>
<td><img src="images/le-line.gif" width="193" height="1"></td>
</tr>
<tr>
<td height="100%" class="le-content">
<?
echo "<a href=zachetka.php?sem=2&user_id=$user_id>Номера зачетак</a><br><br>";
echo "<a href=zachetka.php?sem=2&user_id=$user_id>Удалить</a><br><br>";
echo "<a href=r1_2.php?sem=2&user_id=$user_id>Рубежный 1</a><br><br>";
echo "<a href=r2_2.php?sem=2&user_id=$user_id>Рубежный 2 для 1 курса</a><br><br>";
echo "<a href=r2_22.php?sem=2&user_id=$user_id>Рубежный 2 для 2 курса</a><br><br>";
echo "<a href=r2_23.php?sem=2&user_id=$user_id>Рубежный 2 для 3 курса</a><br><br>";
echo "<a href=r3_2.php?sem=2&user_id=$user_id>Рубежный 3 и курсовая работа, рейтинг допуска и экзаменнационная ведомость Уголовное право для 2 курса</a><br><br>";
//echo "//<a href=>Курсовая</a><br><br>";
echo "<a href=rd.php?sem=2&user_id=$user_id>Рейтинг допуска для 1 курса</a><br><br>";
echo "<a href=rd2.php?sem=2&user_id=$user_id>Рейтинг допуска для 2 курса</a><br><br>";
echo "<a href=rd3.php?sem=2&user_id=$user_id>Рейтинг допуска для 3 курса</a><br><br>";
echo "<a href=view_test.php?user_id=$user_id>Tестирования</a><br><br>";

echo "<a href=aca2017.php?user_id=$user_id>Академическая разница</a><br><br>";
//echo "<a href=aca20171.php?user_id=$user_id>Академическая разница курсовая</a><br><br>";
//echo "<a href=govex.php?user_id=$user_id>Госэкзамен</a><br><br>";

echo "<a href=dopved.php?user_id=$user_id>Дополнительные ведомости</a><br><br>";

echo "<a href=inpit_oral_exam.php?user_id=$user_id>Устная форма</a><br><br>";
echo "<a href=exam.php?user_id=$user_id>Экзаменационный для 1 курса</a><br><br>";
echo "<a href=exam2.php?user_id=$user_id>Экзаменационный для 2 курса</a><br><br>";
echo "<a href=exam3.php?user_id=$user_id>Экзаменационный для 3 курса</a><br><br>";

echo "<a href=con2017.php?user_id=$user_id>Допуск для 1 курса</a><br><br>";
echo "<a href=con20172.php?user_id=$user_id>Допуск для 2 курса</a><br><br>";
echo "<a href=con20173.php?user_id=$user_id>Допуск для 3 курса</a><br><br>";
//echo "<a href=komissia.php?user_id=$user_id>Комиссия</a><br><br>";
echo "<a href=sv.php>Сводная ведомасть</a><br><br>";
echo "<a href=sv1.php>Итоговая ведомасть за 2 семестра</a><br><br>";


?>
</td>

</tr>
</table>         








  
<?
}
else echo "Вы вели не правильно";
?>