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

<p align=center>Межелік бақылау ведомосы/Ведомость рубежного контроля</p>
<table align=center >
<tr>
<td valign=top>
<p><b>Күндізгі оқыту факультеті/Факультет очного обучения<br>
Мамандық: «5В030300–Құқық қорғау қызметі»<br>
Специальность: «5В030300-Правоохранительная деятельность»<br>

<table><tr><td><p><b>Пән/дисциплина:</td><td>
<?
$result1 = mysql_query ("select * from disved where ved=$ved");
$myrow1 = mysql_fetch_array($result1);
echo "<p><b>$myrow1[dis]</p>";
//echo "__________________________________";
?>
</p>
</td></tr></table>
<table><tr><td><b>Кредиттер саны/количество кредитов: </td><td><? echo "$myrow1[credit]"; 

//echo "____";
?></td></tr></table>



</td><td valign=top>

<table><tr><td><b><?  echo 2*$kurs; ?>	</td><td><b>семестр	</td><td><b>2016-2017</td><td><b>оқу жылы/уч.год</td></tr></table>
<table><tr><td>
<?
$result = mysql_query ("select * from groups where groupID=$groups");
$myrow = mysql_fetch_array($result);

echo "<b>$myrow[name]";
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
<td colspan=3 align=center class=border-ram><b>Рубежный контроль I</td>
<td rowspan=2 align=center class=border-ram1>	<b>Ознакомлен</td></tr>
<tr><td align=center class=border-ram><b>пайызбен<br>в процентах </td>
<td align=center class=border-ram><b>балмен<br>в баллах</td>
<td align=center class=border-ram><b>әріптік<br>буквенная</td></tr>



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
if ($kurs==1) 
{
//satu1
$result1 = mysql_query ("select * from journal4 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=1");
$myrow1 = mysql_fetch_array($result1);
//r1
$result2 = mysql_query ("select * from journal4 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=1");
$myrow2 = mysql_fetch_array($result2);
//satu2
$result3 = mysql_query ("select * from journal4 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=2");
$myrow3 = mysql_fetch_array($result3);
//r2
$result4 = mysql_query ("select * from journal4 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=2");
$myrow4 = mysql_fetch_array($result4);

}
else
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
}


if ($myrow2['Mark']<0) {$mark="Неявка по ув.п.";				$bukva= ""; 		$ball = "";}
if (($myrow2['Mark'] =='') or  ($myrow2['Mark'] ==0))		{$mark="";				$bukva= ""; 		$ball = "";}
if (($myrow2['Mark'] >=1) and  ($myrow2['Mark'] <=49))   	{$mark=$myrow2['Mark'];	$bukva= "F"; 		$ball = "0";}
if (($myrow2['Mark'] >=50) and  ($myrow2['Mark'] <=54)) 	{$mark=$myrow2['Mark'];	$bukva= "D"; 		$ball = "1.0";}
if (($myrow2['Mark'] >=55) and  ($myrow2['Mark'] <=59)) 	{$mark=$myrow2['Mark'];	$bukva= "D+"; 	$ball = "1.33";}
if (($myrow2['Mark'] >=60) and  ($myrow2['Mark'] <=64)) 	{$mark=$myrow2['Mark'];	$bukva= "C-";	$ball = "1.67";}
if (($myrow2['Mark'] >=65) and  ($myrow2['Mark'] <=69)) 	{$mark=$myrow2['Mark'];	$bukva= "C"; 		$ball = "2.0";}
if (($myrow2['Mark'] >=70) and  ($myrow2['Mark'] <=74)) 	{$mark=$myrow2['Mark'];	$bukva= "C+"; 	$ball = "2.33";}
if (($myrow2['Mark'] >=75) and  ($myrow2['Mark'] <=79)) 	{$mark=$myrow2['Mark'];	$bukva= "B-";	$ball = "2.67";}
if (($myrow2['Mark'] >=80) and  ($myrow2['Mark'] <=84)) 	{$mark=$myrow2['Mark'];	$bukva= "B"; 		$ball = "3.0";}
if (($myrow2['Mark'] >=85) and  ($myrow2['Mark'] <=89)) 	{$mark=$myrow2['Mark'];	$bukva= "B+"; 	$ball = "3.33";}
if (($myrow2['Mark'] >=90) and  ($myrow2['Mark'] <=94)) 	{$mark=$myrow2['Mark'];	$bukva= "A-";	$ball = "3.67";}
if (($myrow2['Mark'] >=95) and  ($myrow2['Mark'] <=100)) 	{$mark=$myrow2['Mark'];	$bukva= "A"; 		$ball = "4.0";}

echo "<tr><td align=right class=border-ram>$i.</td><td class=border-ram>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td><td align=center class=border-ram>$myrow[zachetka]</td><td align=center class=border-ram>$mark</td><td align=center class=border-ram>$bukva</td><td align=center class=border-ram>$ball</td><td class=border-ram1></td></tr>";
$i++;
}
while ($myrow = mysql_fetch_array($result));
?>
<tr><td colspan=7 class=border-ram2>&nbsp;</td></tr>

<tr><td colspan=5>
<p><b>Мониторинг және білім сапасын бағалау <br>бөлімінің бастығы	<br>полиция полковнигі</p></td>
<td colspan=2 ><b>Б.К.Жилкибаев</td>


</tr>
</table>



</body>

</html>
