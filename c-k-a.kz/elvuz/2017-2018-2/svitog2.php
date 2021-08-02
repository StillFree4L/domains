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

		$result20 = mysql_query ("select * from disved where groupID=$groupID and god=2017 and sem=2 order by dis");
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
			echo "<td align=center colspan=3><b>$myrow20[dis]</td>";
		}
		while ($myrow20 = mysql_fetch_array($result20));
echo "<td align=center><b>Үлгерімінің орташа балы (GPA)<br>средний балл успеваемости GPA</td></tr>";
$x=0;
$result = mysql_query ("select * from students2017 where groupID=$groupID"); 
$myrow = mysql_fetch_array($result);
do
{
$x++;
	$result1 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[1]");
	$myrow1 = mysql_fetch_array($result1);
	$result2 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[2]");
	$myrow2 = mysql_fetch_array($result2);
	$result3 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[3]");
	$myrow3 = mysql_fetch_array($result3);
	$result4 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[4]");
	$myrow4 = mysql_fetch_array($result4);
	$result5 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[5]");
	$myrow5 = mysql_fetch_array($result5);
	$result6 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[6]");
	$myrow6 = mysql_fetch_array($result6);
	$result7 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[7]");
	$myrow7 = mysql_fetch_array($result7);
	$result8 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[8]");
	$myrow8 = mysql_fetch_array($result8);
	$result9 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[9]");
	$myrow9 = mysql_fetch_array($result9);
	$result10 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[10]");
	$myrow10 = mysql_fetch_array($result10);
	$result11 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[11]");
	$myrow11 = mysql_fetch_array($result11);
	
if (($myrow1['totalmark'] >=0) and    ($myrow1['totalmark'] <=49))   	{$bukva1= "F"; 	$ball1 = "0";		$d1=$ball1*$c[1];}
if (($myrow1['totalmark'] >=50) and  ($myrow1['totalmark'] <=54)) 	{$bukva1= "D"; 	$ball1 = "1.0";		$d1=$ball1*$c[1];}
if (($myrow1['totalmark'] >=55) and  ($myrow1['totalmark'] <=59)) 	{$bukva1= "D+"; 	$ball1 = "1.33";		$d1=$ball1*$c[1];}
if (($myrow1['totalmark'] >=60) and  ($myrow1['totalmark'] <=64)) 	{$bukva1= "C-";	$ball1 = "1.67";		$d1=$ball1*$c[1];}
if (($myrow1['totalmark'] >=65) and  ($myrow1['totalmark'] <=69)) 	{$bukva1= "C"; 	$ball1 = "2.0";		$d1=$ball1*$c[1];}
if (($myrow1['totalmark'] >=70) and  ($myrow1['totalmark'] <=74)) 	{$bukva1= "C+"; 	$ball1 = "2.33";		$d1=$ball1*$c[1];}
if (($myrow1['totalmark'] >=75) and  ($myrow1['totalmark'] <=79)) 	{$bukva1= "B-";	$ball1 = "2.67";		$d1=$ball1*$c[1];}
if (($myrow1['totalmark'] >=80) and  ($myrow1['totalmark'] <=84)) 	{$bukva1= "B"; 	$ball1 = "3.0";		$d1=$ball1*$c[1];}
if (($myrow1['totalmark'] >=85) and  ($myrow1['totalmark'] <=89)) 	{$bukva1= "B+"; 	$ball1 = "3.33";		$d1=$ball1*$c[1];}
if (($myrow1['totalmark'] >=90) and  ($myrow1['totalmark'] <=94)) 	{$bukva1= "A-";	$ball1 = "3.67";		$d1=$ball1*$c[1];}
if (($myrow1['totalmark'] >=95) and  ($myrow1['totalmark'] <=100)) 	{$bukva1= "A"; 	$ball1 = "4.0";		$d1=$ball1*$c[1];}

if (($myrow2['totalmark'] >=0) and    ($myrow2['totalmark'] <=49))   	{$bukva2= "F"; 	$ball2 = "0";		$d2=$ball2*$c[2];}
if (($myrow2['totalmark'] >=50) and  ($myrow2['totalmark'] <=54)) 	{$bukva2= "D"; 	$ball2 = "1.0";		$d2=$ball2*$c[2];}
if (($myrow2['totalmark'] >=55) and  ($myrow2['totalmark'] <=59)) 	{$bukva2= "D+"; 	$ball2 = "1.33";		$d2=$ball2*$c[2];}
if (($myrow2['totalmark'] >=60) and  ($myrow2['totalmark'] <=64)) 	{$bukva2= "C-";	$ball2 = "1.67";		$d2=$ball2*$c[2];}
if (($myrow2['totalmark'] >=65) and  ($myrow2['totalmark'] <=69)) 	{$bukva2= "C"; 	$ball2 = "2.0";		$d2=$ball2*$c[2];}
if (($myrow2['totalmark'] >=70) and  ($myrow2['totalmark'] <=74)) 	{$bukva2= "C+"; 	$ball2 = "2.33";		$d2=$ball2*$c[2];}
if (($myrow2['totalmark'] >=75) and  ($myrow2['totalmark'] <=79)) 	{$bukva2= "B-";	$ball2 = "2.67";		$d2=$ball2*$c[2];}
if (($myrow2['totalmark'] >=80) and  ($myrow2['totalmark'] <=84)) 	{$bukva2= "B"; 	$ball2 = "3.0";		$d2=$ball2*$c[2];}
if (($myrow2['totalmark'] >=85) and  ($myrow2['totalmark'] <=89)) 	{$bukva2= "B+"; 	$ball2 = "3.33";		$d2=$ball2*$c[2];}
if (($myrow2['totalmark'] >=90) and  ($myrow2['totalmark'] <=94)) 	{$bukva2= "A-";	$ball2 = "3.67";		$d2=$ball2*$c[2];}
if (($myrow2['totalmark'] >=95) and  ($myrow2['totalmark'] <=100)) 	{$bukva2= "A"; 	$ball2 = "4.0";		$d2=$ball2*$c[2];}	
	
if (($myrow3['totalmark'] >=0) and    ($myrow3['totalmark'] <=49))   	{$bukva3= "F"; 	$ball3 = "0";		$d3=$ball3*$c[3];}
if (($myrow3['totalmark'] >=50) and  ($myrow3['totalmark'] <=54)) 	{$bukva3= "D"; 	$ball3 = "1.0";		$d3=$ball3*$c[3];}
if (($myrow3['totalmark'] >=55) and  ($myrow3['totalmark'] <=59)) 	{$bukva3= "D+"; 	$ball3 = "1.33";		$d3=$ball3*$c[3];}
if (($myrow3['totalmark'] >=60) and  ($myrow3['totalmark'] <=64)) 	{$bukva3= "C-";	$ball3 = "1.67";		$d3=$ball3*$c[3];}
if (($myrow3['totalmark'] >=65) and  ($myrow3['totalmark'] <=69)) 	{$bukva3= "C"; 	$ball3 = "2.0";		$d3=$ball3*$c[3];}
if (($myrow3['totalmark'] >=70) and  ($myrow3['totalmark'] <=74)) 	{$bukva3= "C+"; 	$ball3 = "2.33";		$d3=$ball3*$c[3];}
if (($myrow3['totalmark'] >=75) and  ($myrow3['totalmark'] <=79)) 	{$bukva3= "B-";	$ball3 = "2.67";		$d3=$ball3*$c[3];}
if (($myrow3['totalmark'] >=80) and  ($myrow3['totalmark'] <=84)) 	{$bukva3= "B"; 	$ball3 = "3.0";		$d3=$ball3*$c[3];}
if (($myrow3['totalmark'] >=85) and  ($myrow3['totalmark'] <=89)) 	{$bukva3= "B+"; 	$ball3 = "3.33";		$d3=$ball3*$c[3];}
if (($myrow3['totalmark'] >=90) and  ($myrow3['totalmark'] <=94)) 	{$bukva3= "A-";	$ball3 = "3.67";		$d3=$ball3*$c[3];}
if (($myrow3['totalmark'] >=95) and  ($myrow3['totalmark'] <=100)) 	{$bukva3= "A"; 	$ball3 = "4.0";		$d3=$ball3*$c[3];}	
	
if (($myrow4['totalmark'] >=0) and    ($myrow4['totalmark'] <=49))   	{$bukva4= "F"; 	$ball4 = "0";		$d4=$ball4*$c[4];}
if (($myrow4['totalmark'] >=50) and  ($myrow4['totalmark'] <=54)) 	{$bukva4= "D"; 	$ball4 = "1.0";		$d4=$ball4*$c[4];}
if (($myrow4['totalmark'] >=55) and  ($myrow4['totalmark'] <=59)) 	{$bukva4= "D+"; 	$ball4 = "1.33";		$d4=$ball4*$c[4];}
if (($myrow4['totalmark'] >=60) and  ($myrow4['totalmark'] <=64)) 	{$bukva4= "C-";	$ball4 = "1.67";		$d4=$ball4*$c[4];}
if (($myrow4['totalmark'] >=65) and  ($myrow4['totalmark'] <=69)) 	{$bukva4= "C"; 	$ball4 = "2.0";		$d4=$ball4*$c[4];}
if (($myrow4['totalmark'] >=70) and  ($myrow4['totalmark'] <=74)) 	{$bukva4= "C+"; 	$ball4 = "2.33";		$d4=$ball4*$c[4];}
if (($myrow4['totalmark'] >=75) and  ($myrow4['totalmark'] <=79)) 	{$bukva4= "B-";	$ball4 = "2.67";		$d4=$ball4*$c[4];}
if (($myrow4['totalmark'] >=80) and  ($myrow4['totalmark'] <=84)) 	{$bukva4= "B"; 	$ball4 = "3.0";		$d4=$ball4*$c[4];}
if (($myrow4['totalmark'] >=85) and  ($myrow4['totalmark'] <=89)) 	{$bukva4= "B+"; 	$ball4 = "3.33";		$d4=$ball4*$c[4];}
if (($myrow4['totalmark'] >=90) and  ($myrow4['totalmark'] <=94)) 	{$bukva4= "A-";	$ball4 = "3.67";		$d4=$ball4*$c[4];}
if (($myrow4['totalmark'] >=95) and  ($myrow4['totalmark'] <=100)) 	{$bukva4= "A"; 	$ball4 = "4.0";		$d4=$ball4*$c[4];}

if (($myrow5['totalmark'] >=0) and    ($myrow5['totalmark'] <=49))   	{$bukva5= "F"; 	$ball5 = "0";		$d5=$ball5*$c[5];}
if (($myrow5['totalmark'] >=50) and  ($myrow5['totalmark'] <=54)) 	{$bukva5= "D"; 	$ball5 = "1.0";		$d5=$ball5*$c[5];}
if (($myrow5['totalmark'] >=55) and  ($myrow5['totalmark'] <=59)) 	{$bukva5= "D+"; 	$ball5 = "1.33";		$d5=$ball5*$c[5];}
if (($myrow5['totalmark'] >=60) and  ($myrow5['totalmark'] <=64)) 	{$bukva5= "C-";	$ball5 = "1.67";		$d5=$ball5*$c[5];}
if (($myrow5['totalmark'] >=65) and  ($myrow5['totalmark'] <=69)) 	{$bukva5= "C"; 	$ball5 = "2.0";		$d5=$ball5*$c[5];}
if (($myrow5['totalmark'] >=70) and  ($myrow5['totalmark'] <=74)) 	{$bukva5= "C+"; 	$ball5 = "2.33";		$d5=$ball5*$c[5];}
if (($myrow5['totalmark'] >=75) and  ($myrow5['totalmark'] <=79)) 	{$bukva5= "B-";	$ball5 = "2.67";		$d5=$ball5*$c[5];}
if (($myrow5['totalmark'] >=80) and  ($myrow5['totalmark'] <=84)) 	{$bukva5= "B"; 	$ball5 = "3.0";		$d5=$ball5*$c[5];}
if (($myrow5['totalmark'] >=85) and  ($myrow5['totalmark'] <=89)) 	{$bukva5= "B+"; 	$ball5 = "3.33";		$d5=$ball5*$c[5];}
if (($myrow5['totalmark'] >=90) and  ($myrow5['totalmark'] <=94)) 	{$bukva5= "A-";	$ball5 = "3.67";		$d5=$ball5*$c[5];}
if (($myrow5['totalmark'] >=95) and  ($myrow5['totalmark'] <=100)) 	{$bukva5= "A"; 	$ball5 = "4.0";		$d5=$ball5*$c[5];}	
	
if (($myrow6['totalmark'] >=0) and    ($myrow6['totalmark'] <=49))   	{$bukva6= "F"; 	$ball6 = "0";		$d6=$ball6*$c[6];}
if (($myrow6['totalmark'] >=50) and  ($myrow6['totalmark'] <=54)) 	{$bukva6= "D"; 	$ball6 = "1.0";		$d6=$ball6*$c[6];}
if (($myrow6['totalmark'] >=55) and  ($myrow6['totalmark'] <=59)) 	{$bukva6= "D+"; 	$ball6 = "1.33";		$d6=$ball6*$c[6];}
if (($myrow6['totalmark'] >=60) and  ($myrow6['totalmark'] <=64)) 	{$bukva6= "C-";	$ball6 = "1.67";		$d6=$ball6*$c[6];}
if (($myrow6['totalmark'] >=65) and  ($myrow6['totalmark'] <=69)) 	{$bukva6= "C"; 	$ball6 = "2.0";		$d6=$ball6*$c[6];}
if (($myrow6['totalmark'] >=70) and  ($myrow6['totalmark'] <=74)) 	{$bukva6= "C+"; 	$ball6 = "2.33";		$d6=$ball6*$c[6];}
if (($myrow6['totalmark'] >=75) and  ($myrow6['totalmark'] <=79)) 	{$bukva6= "B-";	$ball6 = "2.67";		$d6=$ball6*$c[6];}
if (($myrow6['totalmark'] >=80) and  ($myrow6['totalmark'] <=84)) 	{$bukva6= "B"; 	$ball6 = "3.0";		$d6=$ball6*$c[6];}
if (($myrow6['totalmark'] >=85) and  ($myrow6['totalmark'] <=89)) 	{$bukva6= "B+"; 	$ball6 = "3.33";		$d6=$ball6*$c[6];}
if (($myrow6['totalmark'] >=90) and  ($myrow6['totalmark'] <=94)) 	{$bukva6= "A-";	$ball6 = "3.67";		$d6=$ball6*$c[6];}
if (($myrow6['totalmark'] >=95) and  ($myrow6['totalmark'] <=100)) 	{$bukva6= "A"; 	$ball6 = "4.0";		$d6=$ball6*$c[6];}	
	
if (($myrow7['totalmark'] >=0) and    ($myrow7['totalmark'] <=49))   	{$bukva7= "F"; 	$ball7 = "0";		$d7=$ball7*$c[7];}
if (($myrow7['totalmark'] >=50) and  ($myrow7['totalmark'] <=54)) 	{$bukva7= "D"; 	$ball7 = "1.0";		$d7=$ball7*$c[7];}
if (($myrow7['totalmark'] >=55) and  ($myrow7['totalmark'] <=59)) 	{$bukva7= "D+"; 	$ball7 = "1.33";		$d7=$ball7*$c[7];}
if (($myrow7['totalmark'] >=60) and  ($myrow7['totalmark'] <=64)) 	{$bukva7= "C-";	$ball7 = "1.67";		$d7=$ball7*$c[7];}
if (($myrow7['totalmark'] >=65) and  ($myrow7['totalmark'] <=69)) 	{$bukva7= "C"; 	$ball7 = "2.0";		$d7=$ball7*$c[7];}
if (($myrow7['totalmark'] >=70) and  ($myrow7['totalmark'] <=74)) 	{$bukva7= "C+"; 	$ball7 = "2.33";		$d7=$ball7*$c[7];}
if (($myrow7['totalmark'] >=75) and  ($myrow7['totalmark'] <=79)) 	{$bukva7= "B-";	$ball7 = "2.67";		$d7=$ball7*$c[7];}
if (($myrow7['totalmark'] >=80) and  ($myrow7['totalmark'] <=84)) 	{$bukva7= "B"; 	$ball7 = "3.0";		$d7=$ball7*$c[7];}
if (($myrow7['totalmark'] >=85) and  ($myrow7['totalmark'] <=89)) 	{$bukva7= "B+"; 	$ball7 = "3.33";		$d7=$ball7*$c[7];}
if (($myrow7['totalmark'] >=90) and  ($myrow7['totalmark'] <=94)) 	{$bukva7= "A-";	$ball7 = "3.67";		$d7=$ball7*$c[7];}
if (($myrow7['totalmark'] >=95) and  ($myrow7['totalmark'] <=100)) 	{$bukva7= "A"; 	$ball7 = "4.0";		$d7=$ball7*$c[7];}	

if (($myrow8['totalmark'] >=0) and    ($myrow8['totalmark'] <=49))   	{$bukva8= "F"; 	$ball8 = "0";		$d8=$ball8*$c[8];}
if (($myrow8['totalmark'] >=50) and  ($myrow8['totalmark'] <=54)) 	{$bukva8= "D"; 	$ball8 = "1.0";		$d8=$ball8*$c[8];}
if (($myrow8['totalmark'] >=55) and  ($myrow8['totalmark'] <=59)) 	{$bukva8= "D+"; 	$ball8 = "1.33";		$d8=$ball8*$c[8];}
if (($myrow8['totalmark'] >=60) and  ($myrow8['totalmark'] <=64)) 	{$bukva8= "C-";	$ball8 = "1.67";		$d8=$ball8*$c[8];}
if (($myrow8['totalmark'] >=65) and  ($myrow8['totalmark'] <=69)) 	{$bukva8= "C"; 	$ball8 = "2.0";		$d8=$ball8*$c[8];}
if (($myrow8['totalmark'] >=70) and  ($myrow8['totalmark'] <=74)) 	{$bukva8= "C+"; 	$ball8 = "2.33";		$d8=$ball8*$c[8];}
if (($myrow8['totalmark'] >=75) and  ($myrow8['totalmark'] <=79)) 	{$bukva8= "B-";	$ball8 = "2.67";		$d8=$ball8*$c[8];}
if (($myrow8['totalmark'] >=80) and  ($myrow8['totalmark'] <=84)) 	{$bukva8= "B"; 	$ball8 = "3.0";		$d8=$ball8*$c[8];}
if (($myrow8['totalmark'] >=85) and  ($myrow8['totalmark'] <=89)) 	{$bukva8= "B+"; 	$ball8 = "3.33";		$d8=$ball8*$c[8];}
if (($myrow8['totalmark'] >=90) and  ($myrow8['totalmark'] <=94)) 	{$bukva8= "A-";	$ball8 = "3.67";		$d8=$ball8*$c[8];}
if (($myrow8['totalmark'] >=95) and  ($myrow8['totalmark'] <=100)) 	{$bukva8= "A"; 	$ball8 = "4.0";		$d8=$ball8*$c[8];}	

if (($myrow9['totalmark'] >=0) and    ($myrow9['totalmark'] <=49))   	{$bukva9= "F"; 	$ball9 = "0";		$d9=$ball9*$c[9];}
if (($myrow9['totalmark'] >=50) and  ($myrow9['totalmark'] <=54)) 	{$bukva9= "D"; 	$ball9 = "1.0";		$d9=$ball9*$c[9];}
if (($myrow9['totalmark'] >=55) and  ($myrow9['totalmark'] <=59)) 	{$bukva9= "D+"; 	$ball9 = "1.33";		$d9=$ball9*$c[9];}
if (($myrow9['totalmark'] >=60) and  ($myrow9['totalmark'] <=64)) 	{$bukva9= "C-";	$ball9 = "1.67";		$d9=$ball9*$c[9];}
if (($myrow9['totalmark'] >=65) and  ($myrow9['totalmark'] <=69)) 	{$bukva9= "C"; 	$ball9 = "2.0";		$d9=$ball9*$c[9];}
if (($myrow9['totalmark'] >=70) and  ($myrow9['totalmark'] <=74)) 	{$bukva9= "C+"; 	$ball9 = "2.33";		$d9=$ball9*$c[9];}
if (($myrow9['totalmark'] >=75) and  ($myrow9['totalmark'] <=79)) 	{$bukva9= "B-";	$ball9 = "2.67";		$d9=$ball9*$c[9];}
if (($myrow9['totalmark'] >=80) and  ($myrow9['totalmark'] <=84)) 	{$bukva9= "B"; 	$ball9 = "3.0";		$d9=$ball9*$c[9];}
if (($myrow9['totalmark'] >=85) and  ($myrow9['totalmark'] <=89)) 	{$bukva9= "B+"; 	$ball9 = "3.33";		$d9=$ball9*$c[9];}
if (($myrow9['totalmark'] >=90) and  ($myrow9['totalmark'] <=94)) 	{$bukva9= "A-";	$ball9 = "3.67";		$d9=$ball9*$c[9];}
if (($myrow9['totalmark'] >=95) and  ($myrow9['totalmark'] <=100)) 	{$bukva9= "A"; 	$ball9 = "4.0";		$d9=$ball9*$c[9];}

if (($myrow10['totalmark'] >=0) and    ($myrow10['totalmark'] <=49))   	{$bukva10= "F"; 	$ball10 = "0";		$d10=$ball10*$c[10];}
if (($myrow10['totalmark'] >=50) and  ($myrow10['totalmark'] <=54)) 	{$bukva10= "D"; 	$ball10 = "1.0";		$d10=$ball10*$c[10];}
if (($myrow10['totalmark'] >=55) and  ($myrow10['totalmark'] <=59)) 	{$bukva10= "D+"; 	$ball10 = "1.33";	$d10=$ball10*$c[10];}
if (($myrow10['totalmark'] >=60) and  ($myrow10['totalmark'] <=64)) 	{$bukva10= "C-";	$ball10 = "1.67";	$d10=$ball10*$c[10];}
if (($myrow10['totalmark'] >=65) and  ($myrow10['totalmark'] <=69)) 	{$bukva10= "C"; 	$ball10 = "2.0";		$d10=$ball10*$c[10];}
if (($myrow10['totalmark'] >=70) and  ($myrow10['totalmark'] <=74)) 	{$bukva10= "C+"; 	$ball10 = "2.33";	$d10=$ball10*$c[10];}
if (($myrow10['totalmark'] >=75) and  ($myrow10['totalmark'] <=79)) 	{$bukva10= "B-";	$ball10 = "2.67";	$d10=$ball10*$c[10];}
if (($myrow10['totalmark'] >=80) and  ($myrow10['totalmark'] <=84)) 	{$bukva10= "B"; 	$ball10 = "3.0";		$d10=$ball10*$c[10];}
if (($myrow10['totalmark'] >=85) and  ($myrow10['totalmark'] <=89)) 	{$bukva10= "B+"; 	$ball10 = "3.33";	$d10=$ball10*$c[10];}
if (($myrow10['totalmark'] >=90) and  ($myrow10['totalmark'] <=94)) 	{$bukva10= "A-";	$ball10 = "3.67";	$d10=$ball10*$c[10];}
if (($myrow10['totalmark'] >=95) and  ($myrow10['totalmark'] <=100)) 	{$bukva10= "A"; 	$ball10 = "4.0";		$d10=$ball10*$c[10];}
	
if (($myrow11['totalmark'] >=0) and    ($myrow11['totalmark'] <=49))   	{$bukva11= "F"; 	$ball11 = "0";		$d11=$ball11*$c[11];}
if (($myrow11['totalmark'] >=50) and  ($myrow11['totalmark'] <=54)) 	{$bukva11= "D"; 	$ball11 = "1.0";		$d11=$ball11*$c[11];}
if (($myrow11['totalmark'] >=55) and  ($myrow11['totalmark'] <=59)) 	{$bukva11= "D+"; 	$ball11 = "1.33";	$d11=$ball11*$c[11];}
if (($myrow11['totalmark'] >=60) and  ($myrow11['totalmark'] <=64)) 	{$bukva11= "C-";	$ball11 = "1.67";	$d11=$ball11*$c[11];}
if (($myrow11['totalmark'] >=65) and  ($myrow11['totalmark'] <=69)) 	{$bukva11= "C"; 	$ball11 = "2.0";		$d11=$ball11*$c[11];}
if (($myrow11['totalmark'] >=70) and  ($myrow11['totalmark'] <=74)) 	{$bukva11= "C+"; 	$ball11 = "2.33";	$d11=$ball11*$c[11];}
if (($myrow11['totalmark'] >=75) and  ($myrow11['totalmark'] <=79)) 	{$bukva11= "B-";	$ball11 = "2.67";	$d11=$ball11*$c[11];}
if (($myrow11['totalmark'] >=80) and  ($myrow11['totalmark'] <=84)) 	{$bukva11= "B"; 	$ball11 = "3.0";		$d11=$ball11*$c[11];}
if (($myrow11['totalmark'] >=85) and  ($myrow11['totalmark'] <=89)) 	{$bukva11= "B+"; 	$ball11 = "3.33";	$d11=$ball11*$c[11];}
if (($myrow11['totalmark'] >=90) and  ($myrow11['totalmark'] <=94)) 	{$bukva11= "A-";	$ball11 = "3.67";	$d11=$ball11*$c[11];}
if (($myrow11['totalmark'] >=95) and  ($myrow11['totalmark'] <=100)) 	{$bukva11= "A"; 	$ball11 = "4.0";		$d11=$ball11*$c[11];}	
	$d = $d1+$d2+$d3+$d4+$d5+$d6+$d7+$d8+$d9+$d10+$d11;

$gpa = round(($d/$credit),2);
	
printf("<tr><td>$x.</td><td>%s %s %s</td><td align=center> %s</td><td align=center> %s</td><td>$bukva1</td><td>$ball1</td><td align=center>%s</td><td>$bukva2</td><td>$ball2</td><td align=center> %s</td><td>$bukva3</td><td>$ball3</td><td align=center>%s</td><td>$bukva4</td><td>$ball4</td><td align=center> %s</td><td>$bukva5</td><td>$ball5</td><td align=center> %s</td><td>$bukva6</td><td>$ball6</td><td align=center> %s</td><td>$bukva7</td><td>$ball7</td><td align=center> %s</td><td>$bukva8</td><td>$ball8</td><td align=center> %s</td><td>$bukva9</td><td>$ball9</td><td align=center> %s</td><td>$bukva10</td><td>$ball10</td><td align=center> %s</td><td>$bukva11</td><td>$ball11</td><td> $gpa</td></tr>",$myrow['lastname'],$myrow['firstname'],$myrow['patronymic'],$myrow['zachetka'],$myrow1['totalmark'],$myrow2['totalmark'],$myrow3['totalmark'],$myrow4['totalmark'],$myrow5['totalmark'],$myrow6['totalmark'],$myrow7['totalmark'],$myrow8['totalmark'],$myrow9['totalmark'],$myrow10['totalmark'],$myrow11['totalmark']);
}
while ($myrow = mysql_fetch_array($result));




echo "</table>";
		

?>

<table width=100%><tr><td><p><b>Мониторинг және білім сапасын бағалау <br>бөлімінің бастығы	<br>полиция полковнигі</p></td><td align=right><b>Б.К.Жилкибаев</b></td></tr></table>














                
                
                
                
      
</body>
</html>
