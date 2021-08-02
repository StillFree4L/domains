<?
include("include/bd.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Карагандинская академия МВД РК имени Б.Бейсенова</title>
<meta name="description" content="Education website">
<meta name="keywords" content="education, learning, teaching">
</head>

<body>


<table border=0 align=center><tr><td></td><td></td><td width=300>
<b><p align=center>Бекітемін</p>
<p>  Заң институт бастығының орынбасары,<br>
  әрі күндізгі оқыту факультетінің бастығы<br>
  полиция полковнигі</p>
<p> _________________________ Т.З.Аймағанбетов </p>
</td></tr>

<tr><td colspan=2 align=center><b>ҚОРЫТЫНДЫ ЖИЫНТЫҚ ВЕДОМОСІ
<br>СВОДНАЯ ИТОГОВАЯ ВЕДОМОСТЬ

</b></td></tr>
<tr><td>
Күндізгі оқыту факультеті/Факультет очного обучения _____ семестр 2017-2018 оқу жылы/уч.год<br>
Мамандық: «5В030300–Құқық қорғау қызметі»<br>
Специальность: «5В030300-Правоохранительная деятельность» ______ оқу тобы/учебная группа
</td><td>

<table><tr><td><? //echo 2*$kurs; 
?></td><td><br></td><td></td></tr></table>

</td></tr>


</table>



<?


$i=0;			
$credit=0;
echo "<h3>$myrow[name]</h3><table border=1 cellspacing=1 cellpadding=0>";
//



$groupID = $_REQUEST['groupID'];

		$result20 = mysql_query ("select * from disved where groupID=$groupID and god=2017 and sem=1 order by dis");
		$myrow20 = mysql_fetch_array($result20);
		echo "<tr><td align=center><b>р/с<br>п/п</td><td align=center><b>Білім алушының Т.А.Ә <br>ФИО обучающегося</td><td align=center><b>Сынақ кітапшасының нөмері<br>Номер зачетной книжки</td>";
		do
		{
			$i++;			
			$a[$i]=$myrow20['ved'];
			$c[$i]=$myrow20['credit'];
			$credit=$credit+$myrow20['credit'];
			$t[$i]=$myrow20['type'];
//echo "$i - $t[$i]<br>";
			echo "<td align=center colspan=3><b>$myrow20[dis] <br>$myrow20[credit]</td>";
		}
		while ($myrow20 = mysql_fetch_array($result20));
echo "<td align=center><b>Үлгерімінің орташа балы (GPA)<br>средний балл успеваемости GPA</td></tr>";
$x=0;
$result = mysql_query ("select * from students2017 where groupID=$groupID"); 
$myrow = mysql_fetch_array($result);
do
{
$x++;
//if (($ved==6371) or ($ved==6372) or ($ved==6373) or ($ved==6374) or ($ved==6375) or ($ved==6377) or ($ved==6378) or ($ved==6176) or ($ved==6379) or ($ved==6376))
//{

//}

//////////////////////////////////////////// 1 //////////////////////////////////////////////////////////////
for ($j=1;$j<=$i;$j++)
{

	$result6 = mysql_query ("select * from disved where ved=$a[$j] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from totalmarks18 where studentID=$myrow[StudentID] and studygroupID=$a[$j]");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark[$j] = $myrow5['totalmark'];

if (($totalmark[$j] >=0) and    ($totalmark[$j] <=49))   	{$bukva[$j]= "F"; 	$ball[$j] = "0";			$d[$j]=$ball[$j]*$c[$j];}
if (($totalmark[$j] >=50) and  ($totalmark[$j] <=54)) 		{$bukva[$j]= "D"; 	$ball[$j] = "1.0";		$d[$j]=$ball[$j]*$c[$j];}
if (($totalmark[$j] >=55) and  ($totalmark[$j] <=59)) 		{$bukva[$j]= "D+"; 	$ball[$j] = "1.33";		$d[$j]=$ball[$j]*$c[$j];}
if (($totalmark[$j] >=60) and  ($totalmark[$j] <=64)) 		{$bukva[$j]= "C-";	$ball[$j] = "1.67";		$d[$j]=$ball[$j]*$c[$j];}
if (($totalmark[$j] >=65) and  ($totalmark[$j] <=69)) 		{$bukva[$j]= "C"; 	$ball[$j] = "2.0";		$d[$j]=$ball[$j]*$c[$j];}
if (($totalmark[$j] >=70) and  ($totalmark[$j] <=74)) 		{$bukva[$j]= "C+"; 	$ball[$j] = "2.33";		$d[$j]=$ball[$j]*$c[$j];}
if (($totalmark[$j] >=75) and  ($totalmark[$j] <=79)) 		{$bukva[$j]= "B-";	$ball[$j] = "2.67";		$d[$j]=$ball[$j]*$c[$j];}
if (($totalmark[$j] >=80) and  ($totalmark[$j] <=84)) 		{$bukva[$j]= "B"; 	$ball[$j] = "3.0";		$d[$j]=$ball[$j]*$c[$j];}
if (($totalmark[$j] >=85) and  ($totalmark[$j] <=89)) 		{$bukva[$j]= "B+"; 	$ball[$j] = "3.33";		$d[$j]=$ball[$j]*$c[$j];}
if (($totalmark[$j] >=90) and  ($totalmark[$j] <=94)) 		{$bukva[$j]= "A-";	$ball[$j] = "3.67";		$d[$j]=$ball[$j]*$c[$j];}
if (($totalmark[$j] >=95) and  ($totalmark[$j] <=100)) 	{$bukva[$j]= "A"; 	$ball[$j] = "4.0";		$d[$j]=$ball[$j]*$c[$j];}
}



$dd=$d[1]+$d[2]+$d[3]+$d[4]+$d[5]+$d[6]+$d[7]+$d[8]+$d[9]+$d[10]+$d[11]+$d[12];
$gpa=round($dd/$credit,2);
printf("<tr><td>$x.</td><td>%s %s %s</td><td align=center> %s</td><td align=center> %s</td><td>$bukva[1]</td><td>$ball[1]</td><td align=center> %s</td><td>$bukva[2]</td><td>$ball[2]</td><td align=center> %s</td><td>$bukva[3]</td><td>$ball[3]</td><td align=center> %s</td><td>$bukva[4]</td><td>$ball[4]</td><td align=center> %s</td><td>$bukva[5]</td><td>$ball[5]</td><td align=center> %s</td><td>$bukva[6]</td><td>$ball[6]</td><td align=center> %s</td><td>$bukva[7]</td><td>$ball[7]</td><td align=center> %s</td><td>$bukva[8]</td><td>$ball[8]</td><td align=center> %s</td><td>$bukva[9]</td><td>$ball[9]</td><td align=center> %s</td><td>$bukva[10]</td><td>$ball[10]</td><td align=center> %s</td><td>$bukva[11]</td><td>$ball[11]</td><td align=center> %s</td><td>$bukva[12]</td><td>$ball[12]</td><td align=center>$gpa</td></tr>",$myrow['lastname'],$myrow['firstname'],$myrow['patronymic'],$myrow['zachetka'],$totalmark[1],$totalmark[2],$totalmark[3],$totalmark[4],$totalmark[5],$totalmark[6],$totalmark[7],$totalmark[8],$totalmark[9],$totalmark[10],$totalmark[11],$totalmark[12]);

}
while ($myrow = mysql_fetch_array($result));




echo "</table>";
		

?>

<table width=100%><tr><td><p><b>Мониторинг және білім сапасын бағалау <br>бөлімінің бастығы	<br>полиция полковнигі</p></td><td align=right><b>Б.К.Жилкибаев</b></td></tr></table>














                
                
                
                
      
</body>
</html>
