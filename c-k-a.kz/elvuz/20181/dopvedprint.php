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
include("include/bd.php");
$t=$_REQUEST['type']; 
$dopvedid=$_REQUEST['dopvedid'];

$result100 = mysql_query ("select * from dopved where id=$dopvedid");
$myrow100 = mysql_fetch_array($result100);
$ved=$myrow100['ved'];
$groups=$myrow100['groupID'];
?>
<p align=center><b>ҚАЗАҚСТАН РЕСПУБЛИКАСЫ ІІМ/ МВД РЕСПУБЛИКИ КАЗАХСТАН <br>
Б.БЕЙСЕНОВ атындағы ҚАРАҒАНДЫ  АКАДЕМИЯСЫ/КАРАГАНДИНСКАЯ АКАДЕМИЯ имени Б.БЕЙСЕНОВА<br>
ЗАҢ ИНСТИТУТЫ/ЮРИДИЧЕСКИЙ ИНСТИТУТ </p>

<p align=center><b>ҚОРЫТЫНДЫ БАҚЫЛАУ ВЕДОМОСІ / ВЕДОМОСТЬ ИТОГОВОГО КОНТРОЛЯ</b></p>

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
<table><tr><td><b>Кредиттер саны/количество кредитов: </td><td><? //echo "$myrow1[credit]"; 

echo "____";
?></td></tr></table>



</td><td valign=top>

<table><tr><td><b><? echo "_____";?>	</td><td><b>семестр	</td><td><b>2017-2018</td><td><b>оқу жылы/уч.год</td></tr></table>
<table><tr><td>
<?
$result = mysql_query ("select * from groups2018 where groupID=$groups");
$myrow = mysql_fetch_array($result);

echo "<b>$myrow[name]";
?>
</td><td><b>оқыту топ/учебная группа</td></tr><tr><td colspan=2>«____» ______________________</td></tr><tr><td colspan=2><b>Бақылау өткізу күні/Дата проведения контроля</td></tr></table>
<table><tr><td>___________________________________</td></tr><tr><td><b>Оқытушының аты-жөні/Ф.И.О.преподавателя</td></tr></table>

</td>
</tr>
</table>

<table align=center cellspacing="0" cellpadding="2" ><tr>
<td rowspan=2 align=center class=border-ram><b>р/с<br>п/п	</td>
<td rowspan=2 align=center class=border-ram><b>Курсантарының Т.А.Ә.<br>Ф.И.О.курсантов	</td>
<td rowspan=2 align=center class=border-ram> <img src=images/zachetka.png width=60></td>
<td colspan=3 align=center class=border-ram ><b>Бағалауға жіберілу рейтингі<br>Оценка рейтинга допуска <br>(Рср.,%)</td>
<td colspan=3 align=center class=border-ram><b>Емтихан бағасы <br>Экзаменационная оценка</td>
<td colspan=3 align=center class=border-ram><b>Қорытынды баға<br>Итоговая оценка</td>

<td rowspan=2 align=center 1 class=border-ram1>	<b>Емтихан қабылдаушының қолы<br>Подпись экзаменатора</td></tr>
<tr>
<td align=center class=border-ram><img src=images/procent.png width=25></td>
<td align=center class=border-ram><img src=images/bukva.png width=25></td>
<td align=center class=border-ram><img src=images/ball.png width=25></td>
<td align=center class=border-ram><img src=images/procent.png width=25></td>
<td align=center class=border-ram><img src=images/bukva.png width=25></td>
<td align=center class=border-ram><img src=images/ball.png width=25></td>
<td align=center class=border-ram><img src=images/procent.png width=25></td>
<td align=center class=border-ram><img src=images/bukva.png width=25></td>
<td align=center class=border-ram><img src=images/ball.png width=25></td>
</tr>

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
$e=0;//neyavka

$result101 = mysql_query ("select * from dopvedstud where dopvedid=$dopvedid");
$myrow101 = mysql_fetch_array($result101);
do
{ 
$result = mysql_query ("select * from students2018 where StudentID=$myrow101[studentID]");
$myrow = mysql_fetch_array($result);

$mark = $myrow101['rd'];
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
if ($t==1) 
{
$mark1=$myrow101['testexam']; 
//}
if ($mark1==-1) { echo "<td align=center class=border-ram colspan=3>Не явка</td>"; }


if ($mark1 =='') 
{
echo "<td align=center class=border-ram></td>";
echo "<td align=center class=border-ram></td>";
echo "<td align=center class=border-ram></td>";

}

if($mark1<50) 
{
$bukva1= "F"; 	$ball1 = "0";
echo "<td align=center class=border-ram>$mark1</td>";
echo "<td align=center class=border-ram>$bukva1</td>";
echo "<td align=center class=border-ram>$ball1</td>";
}
else {
			
	//		if (($mark1 ==0) ) {$bukva1= "F"; 		$ball1 = "0";}
			if (($mark>50) and ($mark1>=0) and    ($mark1 <=49))   		{$bukva1= "F"; 	$ball1 = "0";}
			if (($mark1 >=50) and  ($mark1 <=54)) 		{$bukva1= "D"; 	$ball1 = "1.0";}
			if (($mark1 >=55) and  ($mark1<=59)) 		{$bukva1= "D+"; 	$ball1 = "1.33";}
			if (($mark1 >=60) and  ($mark1 <=64)) 		{$bukva1= "C-";	$ball1 = "1.67";}
			if (($mark1 >=65) and  ($mark1 <=69)) 		{$bukva1= "C"; 	$ball1 = "2.0";}
			if (($mark1 >=70) and  ($mark1 <=74)) 		{$bukva1= "C+"; 	$ball1 = "2.33";}
			if (($mark1 >=75) and  ($mark1 <=79)) 		{$bukva1= "B-";	$ball1 = "2.67";}
			if (($mark1 >=80) and  ($mark1 <=84)) 		{$bukva1= "B"; 	$ball1 = "3.0";}
			if (($mark1 >=85) and  ($mark1 <=89)) 		{$bukva1= "B+"; 	$ball1 = "3.33";}
			if (($mark1 >=90) and  ($mark1 <=94)) 		{$bukva1= "A-";	$ball1 = "3.67";}
			if (($mark1 >=95) and  ($mark1 <=100)) 		{$bukva1= "A"; 	$ball1 = "4.0";}
echo "<td align=center class=border-ram>$mark1</td>";
echo "<td align=center class=border-ram>$bukva1</td>";
echo "<td align=center class=border-ram>$ball1</td>";
}
}

if ($t==2) 
{
$mark1=$myrow101['oralexam']; 
//}

			if ($mark1==-1) 							{$mark1="";	$bukva1= ""; 		$ball1 = "";}
			if (($mark1 =='') )		{$mark1="";		$bukva1= ""; 		$ball1 = "";}
			if (($mark1 ==0) )		{$mark1="";		$bukva1= ""; 		$ball1 = "";}
			if (($mark>50) and ($mark1>0) and ($mark1 <=49))   		{$bukva1= "F"; 	$ball1 = "0";}
			if (($mark1 >=50) and  ($mark1 <=54)) 		{$bukva1= "D"; 	$ball1 = "1.0";}
			if (($mark1 >=55) and  ($mark1<=59)) 		{$bukva1= "D+"; 	$ball1 = "1.33";}
			if (($mark1 >=60) and  ($mark1 <=64)) 		{$bukva1= "C-";	$ball1 = "1.67";}
			if (($mark1 >=65) and  ($mark1 <=69)) 		{$bukva1= "C"; 	$ball1 = "2.0";}
			if (($mark1 >=70) and  ($mark1 <=74)) 		{$bukva1= "C+"; 	$ball1 = "2.33";}
			if (($mark1 >=75) and  ($mark1 <=79)) 		{$bukva1= "B-";	$ball1 = "2.67";}
			if (($mark1 >=80) and  ($mark1 <=84)) 		{$bukva1= "B"; 	$ball1 = "3.0";}
			if (($mark1 >=85) and  ($mark1 <=89)) 		{$bukva1= "B+"; 	$ball1 = "3.33";}
			if (($mark1 >=90) and  ($mark1 <=94)) 		{$bukva1= "A-";	$ball1 = "3.67";}
			if (($mark1 >=95) and  ($mark1 <=100)) 		{$bukva1= "A"; 	$ball1 = "4.0";}
echo "<td align=center class=border-ram>$mark1</td>";
echo "<td align=center class=border-ram>$bukva1</td>";
echo "<td align=center class=border-ram>$ball1</td>";
}
if ($t==3) 
{
$mark1=round (($myrow101['oralexam']+$myrow101['testexam'])/2); 

			if ($mark1==-1) 							{$mark1="";	$bukva1= ""; 		$ball1 = "";}
			if (($mark1 =='') )							{$mark1="";		$bukva1= ""; 		$ball1 = "";}
			if (($mark1 ==0) )							{$mark1="";		$bukva1= ""; 		$ball1 = "";}
			if (($mark1>0) 	and    ($mark1 <=49))   		{$bukva1= "F"; 	$ball1 = "0";}
			if (($mark1 >=50) and  ($mark1 <=54)) 		{$bukva1= "D"; 	$ball1 = "1.0";}
			if (($mark1 >=55) and  ($mark1<=59)) 		{$bukva1= "D+"; 	$ball1 = "1.33";}
			if (($mark1 >=60) and  ($mark1 <=64)) 		{$bukva1= "C-";	$ball1 = "1.67";}
			if (($mark1 >=65) and  ($mark1 <=69)) 		{$bukva1= "C"; 	$ball1 = "2.0";}
			if (($mark1 >=70) and  ($mark1 <=74)) 		{$bukva1= "C+"; 	$ball1 = "2.33";}
			if (($mark1 >=75) and  ($mark1 <=79)) 		{$bukva1= "B-";	$ball1 = "2.67";}
			if (($mark1 >=80) and  ($mark1 <=84)) 		{$bukva1= "B"; 	$ball1 = "3.0";}
			if (($mark1 >=85) and  ($mark1 <=89)) 		{$bukva1= "B+"; 	$ball1 = "3.33";}
			if (($mark1 >=90) and  ($mark1 <=94)) 		{$bukva1= "A-";	$ball1 = "3.67";}
			if (($mark1 >=95) and  ($mark1 <=100)) 		{$bukva1= "A"; 	$ball1 = "4.0";}
echo "<td align=center class=border-ram>$mark1</td>";
echo "<td align=center class=border-ram>$bukva1</td>";
echo "<td align=center class=border-ram>$ball1</td>";
}

if  (($mark>=50) and ($mark1>=50))
//if  ($mark>=50) 
{	
			$mark2=round(($mark*0.6) + ($mark1*0.4)); 
			if ($mark2<0) 							{$mark2="Неявка по ув.п.";	$bukva2= ""; 		$ball2 = "";}
			if (($mark2 =='') or       ($mark2 ==0))		{$mark2="";		$bukva2= ""; 		$ball2 = "";}
	//		if (($mark2>=0) and    ($mark2 <=49))   		{$bukva2= "F"; 	$ball2 = "0"; $f++;}
			if (($mark2 >=50) and  ($mark2 <=54)) 		{$bukva2= "D"; 	$ball2 = "1.0"; 	$d2++;}
			if (($mark2 >=55) and  ($mark2<=59)) 		{$bukva2= "D+"; 	$ball2 = "1.33"; 	$d1++;}
			if (($mark2 >=60) and  ($mark2 <=64)) 		{$bukva2= "C-";	$ball2 = "1.67"; 	$c3++;}
			if (($mark2 >=65) and  ($mark2 <=69)) 		{$bukva2= "C"; 	$ball2 = "2.0"; 	$c2++;} 
			if (($mark2 >=70) and  ($mark2 <=74)) 		{$bukva2= "C+"; 	$ball2 = "2.33"; 	$c1++;}
			if (($mark2 >=75) and  ($mark2 <=79)) 		{$bukva2= "B-";	$ball2 = "2.67"; 	$b3++;}
			if (($mark2 >=80) and  ($mark2 <=84)) 		{$bukva2= "B"; 	$ball2 = "3.0"; 	$b2++;}
			if (($mark2 >=85) and  ($mark2 <=89)) 		{$bukva2= "B+"; 	$ball2 = "3.33"; 	$b1++;}
			if (($mark2 >=90) and  ($mark2 <=94)) 		{$bukva2= "A-";	$ball2 = "3.67"; 	$a2++;}
			if (($mark2 >=95) and  ($mark2 <=100)) 		{$bukva2= "A"; 	$ball2 = "4.0"; 	$a1++;}
echo "<td align=center class=border-ram>$mark2</td>";
echo "<td align=center class=border-ram>$bukva2</td>";
echo "<td align=center class=border-ram>$ball2</td>";
echo "<td class=border-ram1></td>";
} else {
//$f++;
//echo "<td align=center class=border-ram></td>";
//echo "<td align=center class=border-ram></td>";
//echo "<td align=center class=border-ram> </td>";
echo "<td align=center class=border-ram></td>";
echo "<td align=center class=border-ram></td>";
echo "<td align=center class=border-ram> </td>";
echo "<td class=border-ram1></td>";
}
if (($mark>=50) and ($mark1>=50)) {
//$res=mysql_query("UPDATE  `nitro`.`totalmarks182` SET  `exammark` =  '$mark1' ,`ap_exammark` =  '$mark1', ratings=$mark, ap_ratings=$mark, rating=$mark, ap_rating=$mark, totalmark=$mark2, ap_totalmark=$mark2 WHERE  `totalmarks182`.studygroupID=$ved and `totalmarks182`.studentID=$myrow[StudentID]");
}
echo "</tr>";
$i++;
}
while ($myrow101 = mysql_fetch_array($result101));
$a = $a1+$a2;
$b=$b1+$b2+$b3;
$c=$c1+$c2+$c3+$d1+$d2;
$f=$f-$e;
?>
<tr><td colspan=13 class=border-ram2 height=30><p><b>Бағалардың саны/ Количество оценок ____________________&nbsp;</p></td></tr>

<tr><td colspan=3 class=border-ram>А,А- 
<? 
//echo $a; 
?>
</td><td colspan=4 class=border-ram>В+,В,В- 
<? 
//echo $b; 
?></td><td colspan=4 class=border-ram>С+,С,С-,D+,D 
<? 
//echo $c; 
?></td><td colspan=2 class=border-ram1>F 
<? 
//echo "$f "; 
?></td></tr>
<tr><td colspan=13 class=border-ram2>&nbsp;</td></tr>
<tr><td colspan=7><p><b>Мониторинг және білім сапасын бағалау <br>бөлімінің бастығы	<br>полиция полковнигі</p></td><td colspan=6 align=right><b>Б.К.Жилкибаев</td></tr>
</table>



</body>

</html>
