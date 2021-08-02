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
$id=$_REQUEST['id']; 
$groups=$_REQUEST['groups']; 
$ved=$_REQUEST['ved']; 
$kurs=$_REQUEST['kurs'];
include("include/bd.php");

?>
<table border=0 align=center><tr><td></td><td>
<b><p align=center>Бекітемін</p>
<p>  Заң институт бастығының орынбасары,<br>
  әрі күндізгі оқыту факультетінің бастығы<br>
  полиция полковнигі</p>
<p> _________________________ Т.З.Аймағанбетов </p>
</td></tr>

<tr><td colspan=2 align=center><b>Емтихандық сессияны тапсыруға жіберілген курсанттардың тізімі</b></td></tr>
<tr><td>
<b>Күндізгі оқыту факультеті/Факультет очного обучения</b><br>
Мамандық: «5В030300–Құқық қорғау қызметі»<br>
Специальность: «5В030300-Правоохранительная деятельность»
<table><tr><td><p>Пән/дисциплина:</td><td>
<?
$result1 = mysql_query ("select * from aca_razn_2017 where id=$id");
$myrow1 = mysql_fetch_array($result1);
echo "<p><b>$myrow1[dis]</p>";
//echo "__________________________________";
?>


</td></tr></table>
<table><tr><td>Кредиттер саны/количество кредитов: </td><td><?// echo "$myrow1[credit]"; 

echo "____";
?></td></tr></table>
</td><td>

<table><tr><td><? //echo 2*$kurs; 
?></td><td>семестр</td><td>2017-2018</td><td>оқу жылы/уч.год</td></tr></table>
<table><tr><td>
</td><td>____ оқыту топ/учебная группа</td></tr></table>

</td></tr>


</table>






<table border=0 align=center cellspacing="0" cellpadding="3"><tr>
<td rowspan=2 align=center class=border-ram><b>р/с<br>п/п	</td>
<td rowspan=2 align=center class=border-ram><b>Курсантарының Т.А.Ә.<br>Ф.И.О.курсантов	</td>
<td rowspan=2 align=center class=border-ram><b>Сынақ кітапша-сының нөмірі/<br>Номер зачетной книжки</td>
<td colspan=3 align=center class=border-ram1><b>Бағалауға жіберілу рейтингі<br>
Оценка рейтинга допуска (Рср.,%)
</td></tr>
<tr><td align=center class=border-ram><b>пайызбен<br>в процентах </td>
<td align=center class=border-ram><b>балмен<br>в баллах</td>
<td align=center class=border-ram1><b>әріптік<br>буквенная</td></tr>



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


$sotu1 = $myrow1['s1'];
$r1 = $myrow1['r1'];
$rei1=round(($sotu1+$r1)/2);
$sotu2 = $myrow1['s2'];
$r2 = $myrow1['r2'];
$rei2=round(($sotu2+$r2)/2);
$rd = round(($sotu1+$r1+$sotu2+$r2)/4);

if ($rd<0) {$mark="Неявка по ув.п.";				$bukva= ""; 		$ball = "";}
if (($rd =='') or  ($rd ==0))		{$mark="";				$bukva= ""; 		$ball = "";}
if (($rd >=1) and  ($rd <=49))   	{$mark=$rd;	$bukva= ""; 		$ball = "";}
if (($rd >=50) and  ($rd <=54)) 	{$mark=$rd;	$bukva= "D"; 		$ball = "1.0";}
if (($rd >=55) and  ($rd <=59)) 	{$mark=$rd;	$bukva= "D+"; 	$ball = "1.33";}
if (($rd >=60) and  ($rd <=64)) 	{$mark=$rd;	$bukva= "C-";	$ball = "1.67";}
if (($rd >=65) and  ($rd <=69)) 	{$mark=$rd;	$bukva= "C"; 		$ball = "2.0";}
if (($rd >=70) and  ($rd <=74)) 	{$mark=$rd;	$bukva= "C+"; 	$ball = "2.33";}
if (($rd >=75) and  ($rd <=79)) 	{$mark=$rd;	$bukva= "B-";	$ball = "2.67";}
if (($rd >=80) and  ($rd <=84)) 	{$mark=$rd;	$bukva= "B"; 		$ball = "3.0";}
if (($rd >=85) and  ($rd <=89)) 	{$mark=$rd;	$bukva= "B+"; 	$ball = "3.33";}
if (($rd >=90) and  ($rd <=94)) 	{$mark=$rd;	$bukva= "A-";	$ball = "3.67";}
if (($rd >=95) and  ($rd <=100)) 	{$mark=$rd;	$bukva= "A"; 		$ball = "4.0";}

echo "<tr><td align=right class=border-ram>$i.</td><td class=border-ram>$myrow1[student] </td><td align=center class=border-ram>$myrow[zachetka]</td><td align=center class=border-ram>$mark</td><td align=center class=border-ram>$bukva</td><td align=center class=border-ram1>$ball</td></tr>";
?>
<tr><td colspan=7 class=border-ram2>&nbsp;</td></tr>

<tr><td colspan=5>
<p><b>Мониторинг және білім сапасын бағалау <br>бөлімінің бастығы	<br>полиция полковнигі</p></td>
<td colspan=2 ><b>Б.К.Жилкибаев</td>


</tr>
</table>



</body>

</html>
