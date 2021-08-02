<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf8">


  <style>
   p {
    font-size: 9pt; /* Размер шрифта в пунктах */ 
   }
   td {
    font-size: 9pt; /* Размер шрифта в пунктах */ 
   }
.border-ram{
border-top:solid 1px #000000;
border-left:solid 1px #000000;

}
.border-ram1{

border-top:solid 1px #000000;
border-right:solid 1px #000000;
border-left:solid 1px #000000;
}
.border-ram2{
border-top:solid 1px #000000;
}

  </style>

</head>

<body text=2>
<?
$groups=$_REQUEST['groups']; 
$ved=$_REQUEST['ved']; 
$kurs=$_REQUEST['kurs']; 

include("include/bd.php");

?>
<p align=center><b>ҚАЗАҚСТАН РЕСПУБЛИКАСЫ ІІМ/ МВД РЕСПУБЛИКИ КАЗАХСТАН <br>
Б.БЕЙСЕНОВ атындағы ҚАРАҒАНДЫ  АКАДЕМИЯСЫ/КАРАГАНДИНСКАЯ АКАДЕМИЯ имени Б.БЕЙСЕНОВА<br>
ЗАҢ ИНСТИТУТЫ/ЮРИДИЧЕСКИЙ ИНСТИТУТ </p>

<p align=center>Тест ведомосы/Ведомость тестирования</p>
<table align=center >
<tr>
<td valign=top>
<p><b>Күндізгі оқыту факультеті/Факультет очного обучения<br>
Мамандық: «5В030300–Құқық қорғау қызметі»<br>
Специальность: «5В030300-Правоохранительная деятельность»<br>

<table><tr><td><p><b>Пән/дисциплина:</td><td>
<?
//$result1 = mysql_query ("select * from disved where ved=$ved");
//$myrow1 = mysql_fetch_array($result1);
//echo "<p><b>$myrow1[dis]</p>";
echo "__________________________________";
?>
</p>
</td></tr></table>
<table><tr><td><b>Кредиттер саны/количество кредитов: </td><td><? //echo "$myrow1[credit]"; 

echo "____";
?></td></tr></table>



</td><td valign=top>

<table><tr><td><b>2	</td><td><b>семестр	</td><td><b>2016-2017</td><td><b>оқу жылы/уч.год</td></tr></table>
<table><tr><td>
<?
//$result = mysql_query ("select * from groups where groupID=$groups");
//$myrow = mysql_fetch_array($result);

//echo "<b>$myrow[name]";
?>
</td><td><b>оқыту топ/учебная группа</td></tr><tr><td colspan=2>«____» ______________________</td></tr><tr><td colspan=2><b>Бақылау өткізу күні/Дата проведения контроля</td></tr></table>
<table><tr><td>___________________________________</td></tr><tr><td><b>Оқытушының аты-жөні/Ф.И.О.преподавателя</td></tr></table>

</td>
</tr>
</table>

<table border=0 align=center cellspacing="0" cellpadding="3"><tr>
<td rowspan=2 align=center class=border-ram><b>р/с<br>п/п	</td>
<td rowspan=2 align=center class=border-ram><b>Курсантарының Т.А.Ә.<br>Ф.И.О.курсантов	</td>
<td rowspan=2 align=center class=border-ram><b>Сынақ кітапша-сының нөмірі/<br>Номер зачетной книжки</td>
<td colspan=3 align=center class=border-ram><b>Тестирование</td>
<td rowspan=2 align=center class=border-ram1>	<b>Ознакомлен</td></tr>
<tr><td align=center class=border-ram><b>пайызбен<br>в процентах </td>
<td align=center class=border-ram><b>балмен<br>в баллах</td>
<td align=center class=border-ram><b>әріптік<br>буквенная</td></tr>



<?
$i=1;

$testID=$_REQUEST['testID'];
$lang=$_REQUEST['lang'];
$result1 = mysql_query ("select * from magstu where lang='$lang'");
$myrow1 = mysql_fetch_array($result1);



do
{
$result2 = mysql_query ("select * from test_work2 where testID=$testID and studentID=$myrow1[studentID]");
$myrow2 = mysql_fetch_array($result2);

if ($myrow2['ball'] =='') {$bukva= ""; 		$ball = "";		$f++;}
if ($myrow2['ball'] <=49) {$bukva= "F"; 		$ball = "0";		$f++;}
if (($myrow2['ball'] >=50) and  ($myrow2['ball'] <=54)) {$bukva= "D"; 		$ball = "1.0";		$d2++;}
if (($myrow2['ball'] >=55) and  ($myrow2['ball'] <=59)) {$bukva= "D+"; 		$ball = "1.33";	$d1++;}
if (($myrow2['ball'] >=60) and  ($myrow2['ball'] <=64)) {$bukva= "C-"; 		$ball = "1.67";	$c3++;}
if (($myrow2['ball'] >=65) and  ($myrow2['ball'] <=69)) {$bukva= "C"; 		$ball = "2.0";		$c2++;}
if (($myrow2['ball'] >=70) and  ($myrow2['ball'] <=74)) {$bukva= "C+"; 		$ball = "2.33";	$c1++;}
if (($myrow2['ball'] >=75) and  ($myrow2['ball'] <=79)) {$bukva= "B-"; 		$ball = "2.67";	$b3++;}
if (($myrow2['ball'] >=80) and  ($myrow2['ball'] <=84)) {$bukva= "B"; 		$ball = "3.0";		$b2++;}
if (($myrow2['ball'] >=85) and  ($myrow2['ball'] <=89)) {$bukva= "B+"; 		$ball = "3.33";	$b1++;}
if (($myrow2['ball'] >=90) and  ($myrow2['ball'] <=94)) {$bukva= "A-"; 		$ball = "3.67";	$a2++;}
if (($myrow2['ball'] >=95) and  ($myrow2['ball'] <=100)) {$bukva= "A"; 		$ball = "4.0";		$a1++;}


echo "<tr><td align=right class=border-ram>$i.</td><td class=border-ram>$myrow1[fio] </td><td align=center class=border-ram>$myrow1[zachetka]</td><td align=center class=border-ram>$myrow2[ball]</td><td align=center class=border-ram></td><td align=center class=border-ram></td><td class=border-ram1></td></tr>";
$i++;
}
while ($myrow1 = mysql_fetch_array($result1));





?>
<tr><td colspan=7 class=border-ram2>&nbsp;</td></tr>

<tr><td colspan=5>
<p><b>Мониторинг және білім сапасын бағалау <br>бөлімінің бастығы	<br>полиция полковнигі</p></td>
<td colspan=2 ><b>Б.К.Жилкибаев</td>


</tr>
</table>



</body>

</html>
