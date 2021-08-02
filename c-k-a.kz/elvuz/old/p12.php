<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf8">

  <title>font-size</title>
  <style>
   p {
    font-size: 8pt; /* Размер шрифта в пунктах */ 
   }
   td {
    font-size: 8pt; /* Размер шрифта в пунктах */ 
   }

  </style>

</head>

<body text=2>
<?
$groups=$_REQUEST['groups']; 
$ved=$_REQUEST['ved']; 
include("include/bd.php");

?>
<p align=center>ҚАЗАҚСТАН РЕСПУБЛИКАСЫ ІІМ/ МВД РЕСПУБЛИКИ КАЗАХСТАН <br>
Б.БЕЙСЕНОВ атындағы ҚАРАҒАНДЫ  АКАДЕМИЯСЫ/КАРАГАНДИНСКАЯ АКАДЕМИЯ имени Б.БЕЙСЕНОВА<br>
ЗАҢ ИНСТИТУТЫ/ЮРИДИЧЕСКИЙ ИНСТИТУТ </p>

<p align=center>Межелік бақылау ведомосы/Ведомость рубежного контроля</p>
<table align=center >
<tr>
<td valign=top>
<p>Күндізгі оқыту факультеті/Факультет очного обучения<br>
Мамандық: «5В030300–Құқық қорғау қызметі»<br>
Специальность: «5В030300-Правоохранительная деятельность»<br>

<table><tr><td><p>Пән/дисциплина:</td><td>
<?
$result1 = mysql_query ("select * from disved where ved=$ved");
$myrow1 = mysql_fetch_array($result1);
echo "<p>$myrow1[dis]</p>";
//echo "__________________________________";
?>
</p>
</td></tr></table>
<table><tr><td>Кредиттер саны/количество кредитов: </td><td><? echo "$myrow1[credit]"; 

//echo "____";
?></td></tr></table>



</td><td valign=top>

<table><tr><td>2	</td><td>семестр	</td><td>2016-2017</td><td>оқу жылы/уч.год</td></tr></table>
<table><tr><td>
<?
$result = mysql_query ("select * from groups where groupID=$groups");
$myrow = mysql_fetch_array($result);

echo "$myrow[name]";
?>
</td><td>оқыту топ/учебная группа</td></tr><tr><td colspan=2>«____» ______________________</td></tr><tr><td colspan=2>Бақылау өткізу күні/Дата проведения контроля</td></tr></table>

<table><tr><td>___________________________________</td></tr><tr><td>Оқытушының аты-жөні/Ф.И.О.преподавателя</td></tr></table>

</td>
</tr>
</table>

<table border=1 align=center><tr><td rowspan=2 align=center><b>р/с<br>п/п	</td><td rowspan=2 align=center><b>Курсантарының Т.А.Ә.<br>Ф.И.О.курсантов	</td><td rowspan=2 align=center><b>Сынақ кітапша-сының нөмірі/<br>Номер зачетной книжки</td><td colspan=3 align=center><b>Рубежный контроль I</td><td rowspan=2 align=center>	<b>Ознакомлен</td></tr>
<tr><td align=center><b>пайызбен<br>в процентах </td><td align=center><b>балмен<br>в баллах</td><td align=center><b>әріптік<br>буквенная</td></tr>



<?
$i=1;
$a1=0; //a
$a2=0;//a-
$b1=0;//b+
$b2=0;//b
$b3=0;//b-
$c1=0;//c+
$c2=0;//c
$c3=0;//c-
$d1=0;//d
$d2=0;//d-
$f=0;//f

$result = mysql_query ("select * from students2 where groupID=$groups");
$myrow = mysql_fetch_array($result);
do
{ 
//satu1
$result1 = mysql_query ("select * from journal2 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=1");
$myrow1 = mysql_fetch_array($result1);
//r1
$result2 = mysql_query ("select * from journal2 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=1");
$myrow2 = mysql_fetch_array($result2);
//satu2
$result3 = mysql_query ("select * from journal2 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=2");
$myrow3 = mysql_fetch_array($result3);
//r2
$result4 = mysql_query ("select * from journal2 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=2");
$myrow4 = mysql_fetch_array($result4);

if (($myrow2['Mark'] >=0) and  ($myrow2['Mark'] <=49)) {$bukva= "F"; 		$ball = "0";		$f++;}
if (($myrow2['Mark'] >=50) and  ($myrow2['Mark'] <=54)) {$bukva= "D"; 		$ball = "1.0";		$d2++;}
if (($myrow2['Mark'] >=55) and  ($myrow2['Mark'] <=59)) {$bukva= "D+"; 	$ball = "1.33";	$d1++;}
if (($myrow2['Mark'] >=60) and  ($myrow2['Mark'] <=64)) {$bukva= "C-"; 		$ball = "1.67";	$c3++;}
if (($myrow2['Mark'] >=65) and  ($myrow2['Mark'] <=69)) {$bukva= "C"; 		$ball = "2.0";		$c2++;}
if (($myrow2['Mark'] >=70) and  ($myrow2['Mark'] <=74)) {$bukva= "C+"; 	$ball = "2.33";	$c1++;}
if (($myrow2['Mark'] >=75) and  ($myrow2['Mark'] <=79)) {$bukva= "B-"; 		$ball = "2.67";	$b3++;}
if (($myrow2['Mark'] >=80) and  ($myrow2['Mark'] <=84)) {$bukva= "B"; 		$ball = "3.0";		$b2++;}
if (($myrow2['Mark'] >=85) and  ($myrow2['Mark'] <=89)) {$bukva= "B+"; 	$ball = "3.33";	$b1++;}
if (($myrow2['Mark'] >=90) and  ($myrow2['Mark'] <=94)) {$bukva= "A-"; 		$ball = "3.67";	$a2++;}
if (($myrow2['Mark'] >=95) and  ($myrow2['Mark'] <=100)) {$bukva= "A"; 	$ball = "4.0";		$a1++;}

echo "<tr><td align=right>$i.</td><td>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td><td align=center>$myrow[zachetka]</td><td align=center>$myrow2[Mark]</td><td align=center>$bukva</td><td align=center>$ball</td><td></td></tr>";
$i++;
}
while ($myrow = mysql_fetch_array($result));
?>

</table>
<br>
<table border=1 align=center>
<tr>
<td>A:</td>	<td><? echo $a1;?></td>
<td>A-:</td>	<td><? echo $a2;?></td>
<td>B+:</td><td><? echo $b1;?></td>
<td>B:</td>	<td><? echo $b2;?></td>
<td>B-:</td>	<td><? echo $b3;?></td>
<td>C+:</td>	<td><? echo $c1;?></td>
<td>C:</td>	<td><? echo $c2;?></td>
<td>C-:</td>	<td><? echo $c3;?></td>
<td>D+:</td><td><? echo $d1;?></td>
<td>D:</td>	<td><? echo $d2;?></td>
<td>F:</td>	<td><? echo $f;?></td>
</tr>

</table>
<p>
Мониторинг және білім сапасын бағалау <br>
бөлімінің бастығы	<br>
полиция полковнигі&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Б.К.Жилкибаев
</p>


</body>

</html>
