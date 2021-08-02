<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf8">


  <style>
   p {
    font-size: 8pt; /* Размер шрифта в пунктах */ 
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
$studentID=$_REQUEST['studentID']; 
$ved=$_REQUEST['ved']; 
$t=$_REQUEST['t']; 
		$kurs=$_REQUEST['kurs']; 
include("include/bd.php");

?>
<p align=center><b>ҚАЗАҚСТАН РЕСПУБЛИКАСЫ ІІМ/ МВД РЕСПУБЛИКИ КАЗАХСТАН <br>
Б.БЕЙСЕНОВ атындағы ҚАРАҒАНДЫ  АКАДЕМИЯСЫ/КАРАГАНДИНСКАЯ АКАДЕМИЯ имени Б.БЕЙСЕНОВА<br>
ЗАҢ ИНСТИТУТЫ/ЮРИДИЧЕСКИЙ ИНСТИТУТ </p>

<p align=center>ҚОРЫТЫНДЫ ЕМТИХАН  ВЕДОМОСЫ / ИТОГОВАЯ ЭКЗАМЕНАЦИОННАЯ ВЕДОМОСТЬ</p>
<table align=center >
<tr>
<td valign=top>
<p><b>Күндізгі оқыту факультеті/Факультет очного обучения<br>
Мамандық: «5В030300–Құқық қорғау қызметі»<br>
Специальность: «5В030300-Правоохранительная деятельность»<br>

<table><tr><td><p><b>Пән/дисциплина:</td><td>
<?
$result1 = mysql_query ("select * from academy_razn where id=$ved");
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

<table align=center cellspacing="0" cellpadding="2" ><tr>
<td rowspan=2 align=center class=border-ram><b>р/с<br>п/п	</td>
<td rowspan=2 align=center class=border-ram><b>Курсантарының Т.А.Ә.<br>Ф.И.О.курсантов	</td>
<td rowspan=2 align=center class=border-ram><b>Сынақ кітапша-сының нөмірі/<br>Номер зачетной книжки</td>
<td colspan=3 align=center class=border-ram><b>Рубежный контроль III</td>
<td rowspan=2 align=center class=border-ram1>	<b>Ознакомлен</td></tr>
<tr><td align=center class=border-ram><b>пайызбен<br>в процентах </td>
<td align=center class=border-ram><b>балмен<br>в баллах</td>
<td align=center class=border-ram><b>әріптік<br>буквенная</td></tr>

</tr>

<?

$result = mysql_query ("select * from students2 where StudentID=$studentID");
$myrow = mysql_fetch_array($result);
do
{ 

$result2 = mysql_query ("select * from academ_student where studentID=$studentID and testID=$ved ");
$myrow2 = mysql_fetch_array($result2);

$mark =$myrow2['exam'];


			if ($mark<0) 							{$mark="Неявка по ув.п.";	$bukva= ""; 		$ball = "";}
			if (($mark =='') or       ($mark ==0))		{$mark="";				$bukva= ""; 		$ball = "";}
			if (($mark >=1) and    ($mark <=49))   	{	$bukva= "F"; 		$ball = "0";}
			if (($mark >=50) and  ($mark <=54)) 		{	$bukva= "D"; 		$ball = "1.0";}
			if (($mark >=55) and  ($mark <=59)) 		{	$bukva= "D+"; 	$ball = "1.33";}
			if (($mark >=60) and  ($mark <=64)) 		{	$bukva= "C-";	$ball = "1.67";}
			if (($mark >=65) and  ($mark <=69)) 		{	$bukva= "C"; 		$ball = "2.0";}
			if (($mark >=70) and  ($mark <=74)) 		{	$bukva= "C+"; 	$ball = "2.33";}
			if (($mark >=75) and  ($mark <=79)) 		{	$bukva= "B-";	$ball = "2.67";}
			if (($mark >=80) and  ($mark <=84)) 		{	$bukva= "B"; 		$ball = "3.0";}
			if (($mark >=85) and  ($mark <=89)) 		{	$bukva= "B+"; 	$ball = "3.33";}
			if (($mark >=90) and  ($mark <=94)) 		{	$bukva= "A-";	$ball = "3.67";}
			if (($mark >=95) and  ($mark <=100)) 	{	$bukva= "A"; 		$ball = "4.0";}


echo "<tr class=border-ram>";
echo "<td align=right class=border-ram>$i.</td><td class=border-ram>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td>";
echo "<td align=center class=border-ram>$myrow[zachetka]</td>";
echo "<td align=center class=border-ram>$mark</td>";
echo "<td align=center class=border-ram>$bukva</td>";
echo "<td align=center class=border-ram>$ball</td>";




echo "<td class=border-ram1></td>";


echo "</tr>";
$i++;
}
while ($myrow = mysql_fetch_array($result));
$a = $a1+$a2;
$b=$b1+$b2+$b3;
$c=$c1+$c2+$c3+$d1+$d2;
$f=$f-$e;
?>
<tr><td colspan=7 class=border-ram2 height=30><p><b>Бағалардың саны/ Количество оценок ____________________&nbsp;</p></td></tr>

<tr><td colspan=2 class=border-ram>А,А- 
<? 
//echo $a; 
?>
</td><td colspan=2 class=border-ram>В+,В,В- 
<? 
//echo $b; 
?></td><td colspan=2 class=border-ram>С+,С,С-,D+,D 
<? 
//echo $c; 
?></td><td colspan=1 class=border-ram1>F 
<? 
//echo "$f "; 
?></td></tr>
<tr><td colspan=7 class=border-ram2>&nbsp;</td></tr>
<tr><td colspan=7><p><b>Мониторинг және білім сапасын бағалау <br>бөлімінің бастығы	<br>полиция полковнигі</p></td><td colspan=6 align=right><b>Б.К.Жилкибаев</td></tr>
</table>



</body>

</html>
