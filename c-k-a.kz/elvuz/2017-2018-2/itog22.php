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

		$result20 = mysql_query ("select * from disved where groupID=$groupID and god=2017 order by sem");
		$myrow20 = mysql_fetch_array($result20);
		echo "<tr><td align=center><b>р/с<br>п/п</td><td align=center><b>Білім алушының Т.А.Ә <br>ФИО обучающегося</td><td align=center><b>Сынақ кітапшасының нөмері<br>Номер зачетной книжки</td>";
		do
		{
			$i++;			
			$a[$i]=$myrow20['ved'];
			$c[$i]=$myrow20['credit'];
			$credit=$credit+$myrow20['credit'];
			$t[$i]=$myrow20['type'];
//echo "$i - $t[$i] - $myrow20[dis]<br>";
//			echo "<td align=center colspan=3><b>$myrow20[dis]</td>";
			echo "<td align=center><b>$myrow20[dis]</td>";
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
	
	$result12 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[12]");
	$myrow12 = mysql_fetch_array($result12);
	$result13 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[13]");
	$myrow13 = mysql_fetch_array($result13);
	$result14 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[14]");
	$myrow14 = mysql_fetch_array($result14);
	$result15 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[15]");
	$myrow15 = mysql_fetch_array($result15);
	$result16 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[16]");
	$myrow16 = mysql_fetch_array($result16);
	$result17 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[17]");
	$myrow17 = mysql_fetch_array($result17);
	$result18 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[18]");
	$myrow18 = mysql_fetch_array($result18);
	$result19 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[19]");
	$myrow19 = mysql_fetch_array($result19);
	
	$result20 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[20]");
	$myrow20 = mysql_fetch_array($result20);
	$result21 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[21]");
	$myrow21 = mysql_fetch_array($result21);
	$result22 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[22]");
	$myrow22 = mysql_fetch_array($result22);
	$result23 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[23]");
	$myrow23 = mysql_fetch_array($result23);
	$result24 = mysql_query ("select * from totalmarks172 where studentID=$myrow[StudentID] and studygroupID=$a[24]");
	$myrow24 = mysql_fetch_array($result24);
	
			
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
	
if (($myrow12['totalmark'] >=0) and    ($myrow12['totalmark'] <=49))   	{$bukva12= "F"; 	$ball12 = "0";		$d12=$ball12*$c[12];}
if (($myrow12['totalmark'] >=50) and  ($myrow12['totalmark'] <=54)) 	{$bukva12= "D"; 	$ball12 = "1.0";		$d12=$ball12*$c[12];}
if (($myrow12['totalmark'] >=55) and  ($myrow12['totalmark'] <=59)) 	{$bukva12= "D+"; 	$ball12 = "1.33";	$d12=$ball12*$c[12];}
if (($myrow12['totalmark'] >=60) and  ($myrow12['totalmark'] <=64)) 	{$bukva12= "C-";	$ball12 = "1.67";	$d12=$ball12*$c[12];}
if (($myrow12['totalmark'] >=65) and  ($myrow12['totalmark'] <=69)) 	{$bukva12= "C"; 	$ball12 = "2.0";		$d12=$ball12*$c[12];}
if (($myrow12['totalmark'] >=70) and  ($myrow12['totalmark'] <=74)) 	{$bukva12= "C+"; 	$ball12 = "2.33";	$d12=$ball12*$c[12];}
if (($myrow12['totalmark'] >=75) and  ($myrow12['totalmark'] <=79)) 	{$bukva12= "B-";	$ball12 = "2.67";	$d12=$ball12*$c[12];}
if (($myrow12['totalmark'] >=80) and  ($myrow12['totalmark'] <=84)) 	{$bukva12= "B"; 	$ball12 = "3.0";		$d12=$ball12*$c[12];}
if (($myrow12['totalmark'] >=85) and  ($myrow12['totalmark'] <=89)) 	{$bukva12= "B+"; 	$ball12 = "3.33";	$d12=$ball12*$c[12];}
if (($myrow12['totalmark'] >=90) and  ($myrow12['totalmark'] <=94)) 	{$bukva12= "A-";	$ball12 = "3.67";	$d12=$ball12*$c[12];}
if (($myrow12['totalmark'] >=95) and  ($myrow12['totalmark'] <=100)) 	{$bukva12= "A"; 	$ball12 = "4.0";		$d12=$ball12*$c[12];}		
	
if (($myrow13['totalmark'] >=0) and    ($myrow13['totalmark'] <=49))   	{$bukva13= "F"; 	$ball13 = "0";		$d13=$ball13*$c[13];}
if (($myrow13['totalmark'] >=50) and  ($myrow13['totalmark'] <=54)) 	{$bukva13= "D"; 	$ball13 = "1.0";		$d13=$ball13*$c[13];}
if (($myrow13['totalmark'] >=55) and  ($myrow13['totalmark'] <=59)) 	{$bukva13= "D+"; 	$ball13 = "1.33";	$d13=$ball13*$c[13];}
if (($myrow13['totalmark'] >=60) and  ($myrow13['totalmark'] <=64)) 	{$bukva13= "C-";	$ball13 = "1.67";	$d13=$ball13*$c[13];}
if (($myrow13['totalmark'] >=65) and  ($myrow13['totalmark'] <=69)) 	{$bukva13= "C"; 	$ball13 = "2.0";		$d13=$ball13*$c[13];}
if (($myrow13['totalmark'] >=70) and  ($myrow13['totalmark'] <=74)) 	{$bukva13= "C+"; 	$ball13 = "2.33";	$d13=$ball13*$c[13];}
if (($myrow13['totalmark'] >=75) and  ($myrow13['totalmark'] <=79)) 	{$bukva13= "B-";	$ball13 = "2.67";	$d13=$ball13*$c[13];}
if (($myrow13['totalmark'] >=80) and  ($myrow13['totalmark'] <=84)) 	{$bukva13= "B"; 	$ball13 = "3.0";		$d13=$ball13*$c[13];}
if (($myrow13['totalmark'] >=85) and  ($myrow13['totalmark'] <=89)) 	{$bukva13= "B+"; 	$ball13 = "3.33";	$d13=$ball13*$c[13];}
if (($myrow13['totalmark'] >=90) and  ($myrow13['totalmark'] <=94)) 	{$bukva13= "A-";	$ball13 = "3.67";	$d13=$ball13*$c[13];}
if (($myrow13['totalmark'] >=95) and  ($myrow13['totalmark'] <=100)) 	{$bukva13= "A"; 	$ball13 = "4.0";		$d13=$ball13*$c[13];}		
	
if (($myrow14['totalmark'] >=0) and    ($myrow14['totalmark'] <=49))   	{$bukva14= "F"; 	$ball14 = "0";		$d14=$ball14*$c[14];}
if (($myrow14['totalmark'] >=50) and  ($myrow14['totalmark'] <=54)) 	{$bukva14= "D"; 	$ball14 = "1.0";		$d14=$ball14*$c[14];}
if (($myrow14['totalmark'] >=55) and  ($myrow14['totalmark'] <=59)) 	{$bukva14= "D+"; 	$ball14 = "1.33";	$d14=$ball14*$c[14];}
if (($myrow14['totalmark'] >=60) and  ($myrow14['totalmark'] <=64)) 	{$bukva14= "C-";	$ball14 = "1.67";	$d14=$ball14*$c[14];}
if (($myrow14['totalmark'] >=65) and  ($myrow14['totalmark'] <=69)) 	{$bukva14= "C"; 	$ball14 = "2.0";		$d14=$ball14*$c[14];}
if (($myrow14['totalmark'] >=70) and  ($myrow14['totalmark'] <=74)) 	{$bukva14= "C+"; 	$ball14 = "2.33";	$d14=$ball14*$c[14];}
if (($myrow14['totalmark'] >=75) and  ($myrow14['totalmark'] <=79)) 	{$bukva14= "B-";	$ball14 = "2.67";	$d14=$ball14*$c[14];}
if (($myrow14['totalmark'] >=80) and  ($myrow14['totalmark'] <=84)) 	{$bukva14= "B"; 	$ball14 = "3.0";		$d14=$ball14*$c[14];}
if (($myrow14['totalmark'] >=85) and  ($myrow14['totalmark'] <=89)) 	{$bukva14= "B+"; 	$ball14 = "3.33";	$d14=$ball14*$c[14];}
if (($myrow14['totalmark'] >=90) and  ($myrow14['totalmark'] <=94)) 	{$bukva14= "A-";	$ball14 = "3.67";	$d14=$ball14*$c[14];}
if (($myrow14['totalmark'] >=95) and  ($myrow14['totalmark'] <=100)) 	{$bukva14= "A"; 	$ball14 = "4.0";		$d14=$ball14*$c[14];}		
	
if (($myrow15['totalmark'] >=0) and    ($myrow15['totalmark'] <=49))   	{$bukva15= "F"; 	$ball15 = "0";		$d15=$ball15*$c[15];}
if (($myrow15['totalmark'] >=50) and  ($myrow15['totalmark'] <=54)) 	{$bukva15= "D"; 	$ball15 = "1.0";		$d15=$ball15*$c[15];}
if (($myrow15['totalmark'] >=55) and  ($myrow15['totalmark'] <=59)) 	{$bukva15= "D+"; 	$ball15 = "1.33";	$d15=$ball15*$c[15];}
if (($myrow15['totalmark'] >=60) and  ($myrow15['totalmark'] <=64)) 	{$bukva15= "C-";	$ball15 = "1.67";	$d15=$ball15*$c[15];}
if (($myrow15['totalmark'] >=65) and  ($myrow15['totalmark'] <=69)) 	{$bukva15= "C"; 	$ball15 = "2.0";		$d15=$ball15*$c[15];}
if (($myrow15['totalmark'] >=70) and  ($myrow15['totalmark'] <=74)) 	{$bukva15= "C+"; 	$ball15 = "2.33";	$d15=$ball15*$c[15];}
if (($myrow15['totalmark'] >=75) and  ($myrow15['totalmark'] <=79)) 	{$bukva15= "B-";	$ball15 = "2.67";	$d15=$ball15*$c[15];}
if (($myrow15['totalmark'] >=80) and  ($myrow15['totalmark'] <=84)) 	{$bukva15= "B"; 	$ball15 = "3.0";		$d15=$ball15*$c[15];}
if (($myrow15['totalmark'] >=85) and  ($myrow15['totalmark'] <=89)) 	{$bukva15= "B+"; 	$ball15 = "3.33";	$d15=$ball15*$c[15];}
if (($myrow15['totalmark'] >=90) and  ($myrow15['totalmark'] <=94)) 	{$bukva15= "A-";	$ball15 = "3.67";	$d15=$ball15*$c[15];}
if (($myrow15['totalmark'] >=95) and  ($myrow15['totalmark'] <=100)) 	{$bukva15= "A"; 	$ball15 = "4.0";		$d15=$ball15*$c[15];}		
	
if (($myrow16['totalmark'] >=0) and    ($myrow16['totalmark'] <=49))   	{$bukva16= "F"; 	$ball16 = "0";		$d16=$ball16*$c[16];}
if (($myrow16['totalmark'] >=50) and  ($myrow16['totalmark'] <=54)) 	{$bukva16= "D"; 	$ball16 = "1.0";		$d16=$ball16*$c[16];}
if (($myrow16['totalmark'] >=55) and  ($myrow16['totalmark'] <=59)) 	{$bukva16= "D+"; 	$ball16 = "1.33";	$d16=$ball16*$c[16];}
if (($myrow16['totalmark'] >=60) and  ($myrow16['totalmark'] <=64)) 	{$bukva16= "C-";	$ball16 = "1.67";	$d16=$ball16*$c[16];}
if (($myrow16['totalmark'] >=65) and  ($myrow16['totalmark'] <=69)) 	{$bukva16= "C"; 	$ball16 = "2.0";		$d16=$ball16*$c[16];}
if (($myrow16['totalmark'] >=70) and  ($myrow16['totalmark'] <=74)) 	{$bukva16= "C+"; 	$ball16 = "2.33";	$d16=$ball16*$c[16];}
if (($myrow16['totalmark'] >=75) and  ($myrow16['totalmark'] <=79)) 	{$bukva16= "B-";	$ball16 = "2.67";	$d16=$ball16*$c[16];}
if (($myrow16['totalmark'] >=80) and  ($myrow16['totalmark'] <=84)) 	{$bukva16= "B"; 	$ball16 = "3.0";		$d16=$ball16*$c[16];}
if (($myrow16['totalmark'] >=85) and  ($myrow16['totalmark'] <=89)) 	{$bukva16= "B+"; 	$ball16 = "3.33";	$d16=$ball16*$c[16];}
if (($myrow16['totalmark'] >=90) and  ($myrow16['totalmark'] <=94)) 	{$bukva16= "A-";	$ball16 = "3.67";	$d16=$ball16*$c[16];}
if (($myrow16['totalmark'] >=95) and  ($myrow16['totalmark'] <=100)) 	{$bukva16= "A"; 	$ball16 = "4.0";		$d16=$ball16*$c[16];}		
	
if (($myrow17['totalmark'] >=0) and    ($myrow17['totalmark'] <=49))   	{$bukva17= "F"; 	$ball17 = "0";		$d17=$ball17*$c[17];}
if (($myrow17['totalmark'] >=50) and  ($myrow17['totalmark'] <=54)) 	{$bukva17= "D"; 	$ball17 = "1.0";		$d17=$ball17*$c[17];}
if (($myrow17['totalmark'] >=55) and  ($myrow17['totalmark'] <=59)) 	{$bukva17= "D+"; 	$ball17 = "1.33";	$d17=$ball17*$c[17];}
if (($myrow17['totalmark'] >=60) and  ($myrow17['totalmark'] <=64)) 	{$bukva17= "C-";	$ball17 = "1.67";	$d17=$ball17*$c[17];}
if (($myrow17['totalmark'] >=65) and  ($myrow17['totalmark'] <=69)) 	{$bukva17= "C"; 	$ball17 = "2.0";		$d17=$ball17*$c[17];}
if (($myrow17['totalmark'] >=70) and  ($myrow17['totalmark'] <=74)) 	{$bukva17= "C+"; 	$ball17 = "2.33";	$d17=$ball17*$c[17];}
if (($myrow17['totalmark'] >=75) and  ($myrow17['totalmark'] <=79)) 	{$bukva17= "B-";	$ball17 = "2.67";	$d17=$ball17*$c[17];}
if (($myrow17['totalmark'] >=80) and  ($myrow17['totalmark'] <=84)) 	{$bukva17= "B"; 	$ball17 = "3.0";		$d17=$ball17*$c[17];}
if (($myrow17['totalmark'] >=85) and  ($myrow17['totalmark'] <=89)) 	{$bukva17= "B+"; 	$ball17 = "3.33";	$d17=$ball17*$c[17];}
if (($myrow17['totalmark'] >=90) and  ($myrow17['totalmark'] <=94)) 	{$bukva17= "A-";	$ball17 = "3.67";	$d17=$ball17*$c[17];}
if (($myrow17['totalmark'] >=95) and  ($myrow17['totalmark'] <=100)) 	{$bukva17= "A"; 	$ball17 = "4.0";		$d17=$ball17*$c[17];}		
	
if (($myrow18['totalmark'] >=0) and    ($myrow18['totalmark'] <=49))   	{$bukva18= "F"; 	$ball18 = "0";		$d18=$ball18*$c[18];}
if (($myrow18['totalmark'] >=50) and  ($myrow18['totalmark'] <=54)) 	{$bukva18= "D"; 	$ball18 = "1.0";		$d18=$ball18*$c[18];}
if (($myrow18['totalmark'] >=55) and  ($myrow18['totalmark'] <=59)) 	{$bukva18= "D+"; 	$ball18 = "1.33";	$d18=$ball18*$c[18];}
if (($myrow18['totalmark'] >=60) and  ($myrow18['totalmark'] <=64)) 	{$bukva18= "C-";	$ball18 = "1.67";	$d18=$ball18*$c[18];}
if (($myrow18['totalmark'] >=65) and  ($myrow18['totalmark'] <=69)) 	{$bukva18= "C"; 	$ball18 = "2.0";		$d18=$ball18*$c[18];}
if (($myrow18['totalmark'] >=70) and  ($myrow18['totalmark'] <=74)) 	{$bukva18= "C+"; 	$ball18 = "2.33";	$d18=$ball18*$c[18];}
if (($myrow18['totalmark'] >=75) and  ($myrow18['totalmark'] <=79)) 	{$bukva18= "B-";	$ball18 = "2.67";	$d18=$ball18*$c[18];}
if (($myrow18['totalmark'] >=80) and  ($myrow18['totalmark'] <=84)) 	{$bukva18= "B"; 	$ball18 = "3.0";		$d18=$ball18*$c[18];}
if (($myrow18['totalmark'] >=85) and  ($myrow18['totalmark'] <=89)) 	{$bukva18= "B+"; 	$ball18 = "3.33";	$d18=$ball18*$c[18];}
if (($myrow18['totalmark'] >=90) and  ($myrow18['totalmark'] <=94)) 	{$bukva18= "A-";	$ball18 = "3.67";	$d18=$ball18*$c[18];}
if (($myrow18['totalmark'] >=95) and  ($myrow18['totalmark'] <=100)) 	{$bukva18= "A"; 	$ball18 = "4.0";		$d18=$ball18*$c[18];}		
	
if (($myrow19['totalmark'] >=0) and    ($myrow19['totalmark'] <=49))   	{$bukva19= "F"; 	$ball19 = "0";		$d19=$ball19*$c[19];}
if (($myrow19['totalmark'] >=50) and  ($myrow19['totalmark'] <=54)) 	{$bukva19= "D"; 	$ball19 = "1.0";		$d19=$ball19*$c[19];}
if (($myrow19['totalmark'] >=55) and  ($myrow19['totalmark'] <=59)) 	{$bukva19= "D+"; 	$ball19 = "1.33";	$d19=$ball19*$c[19];}
if (($myrow19['totalmark'] >=60) and  ($myrow19['totalmark'] <=64)) 	{$bukva19= "C-";	$ball19 = "1.67";	$d19=$ball19*$c[19];}
if (($myrow19['totalmark'] >=65) and  ($myrow19['totalmark'] <=69)) 	{$bukva19= "C"; 	$ball19 = "2.0";		$d19=$ball19*$c[19];}
if (($myrow19['totalmark'] >=70) and  ($myrow19['totalmark'] <=74)) 	{$bukva19= "C+"; 	$ball19 = "2.33";	$d19=$ball19*$c[19];}
if (($myrow19['totalmark'] >=75) and  ($myrow19['totalmark'] <=79)) 	{$bukva19= "B-";	$ball19 = "2.67";	$d19=$ball19*$c[19];}
if (($myrow19['totalmark'] >=80) and  ($myrow19['totalmark'] <=84)) 	{$bukva19= "B"; 	$ball19 = "3.0";		$d19=$ball19*$c[19];}
if (($myrow19['totalmark'] >=85) and  ($myrow19['totalmark'] <=89)) 	{$bukva19= "B+"; 	$ball19 = "3.33";	$d19=$ball19*$c[19];}
if (($myrow19['totalmark'] >=90) and  ($myrow19['totalmark'] <=94)) 	{$bukva19= "A-";	$ball19 = "3.67";	$d19=$ball19*$c[19];}
if (($myrow19['totalmark'] >=95) and  ($myrow19['totalmark'] <=100)) 	{$bukva19= "A"; 	$ball19 = "4.0";		$d19=$ball19*$c[19];}		
	
	if (($myrow20['totalmark'] >=0) and    ($myrow20['totalmark'] <=49))   	{$bukva20= "F"; 	$ball20 = "0";		$d20=$ball20*$c[20];}
if (($myrow20['totalmark'] >=50) and  ($myrow20['totalmark'] <=54)) 	{$bukva20= "D"; 	$ball20 = "1.0";		$d20=$ball20*$c[20];}
if (($myrow20['totalmark'] >=55) and  ($myrow20['totalmark'] <=59)) 	{$bukva20= "D+"; 	$ball20 = "1.33";	$d20=$ball20*$c[20];}
if (($myrow20['totalmark'] >=60) and  ($myrow20['totalmark'] <=64)) 	{$bukva20= "C-";	$ball20 = "1.67";	$d20=$ball20*$c[20];}
if (($myrow20['totalmark'] >=65) and  ($myrow20['totalmark'] <=69)) 	{$bukva20= "C"; 	$ball20 = "2.0";		$d20=$ball20*$c[20];}
if (($myrow20['totalmark'] >=70) and  ($myrow20['totalmark'] <=74)) 	{$bukva20= "C+"; 	$ball20 = "2.33";	$d20=$ball20*$c[20];}
if (($myrow20['totalmark'] >=75) and  ($myrow20['totalmark'] <=79)) 	{$bukva20= "B-";	$ball20 = "2.67";	$d20=$ball20*$c[20];}
if (($myrow20['totalmark'] >=80) and  ($myrow20['totalmark'] <=84)) 	{$bukva20= "B"; 	$ball20 = "3.0";		$d20=$ball20*$c[20];}
if (($myrow20['totalmark'] >=85) and  ($myrow20['totalmark'] <=89)) 	{$bukva20= "B+"; 	$ball20 = "3.33";	$d20=$ball20*$c[20];}
if (($myrow20['totalmark'] >=90) and  ($myrow20['totalmark'] <=94)) 	{$bukva20= "A-";	$ball20 = "3.67";	$d20=$ball20*$c[20];}
if (($myrow20['totalmark'] >=95) and  ($myrow20['totalmark'] <=100)) 	{$bukva20= "A"; 	$ball20 = "4.0";		$d20=$ball20*$c[20];}	
	
	if (($myrow21['totalmark'] >=0) and    ($myrow21['totalmark'] <=49))   	{$bukva21= "F"; 	$ball21 = "0";		$d21=$ball21*$c[21];}
if (($myrow21['totalmark'] >=50) and  ($myrow21['totalmark'] <=54)) 	{$bukva21= "D"; 	$ball21 = "1.0";		$d21=$ball21*$c[21];}
if (($myrow21['totalmark'] >=55) and  ($myrow21['totalmark'] <=59)) 	{$bukva21= "D+"; 	$ball21 = "1.33";	$d21=$ball21*$c[21];}
if (($myrow21['totalmark'] >=60) and  ($myrow21['totalmark'] <=64)) 	{$bukva21= "C-";	$ball21 = "1.67";	$d21=$ball21*$c[21];}
if (($myrow21['totalmark'] >=65) and  ($myrow21['totalmark'] <=69)) 	{$bukva21= "C"; 	$ball21 = "2.0";		$d21=$ball21*$c[21];}
if (($myrow21['totalmark'] >=70) and  ($myrow21['totalmark'] <=74)) 	{$bukva21= "C+"; 	$ball21 = "2.33";	$d21=$ball21*$c[21];}
if (($myrow21['totalmark'] >=75) and  ($myrow21['totalmark'] <=79)) 	{$bukva21= "B-";	$ball21 = "2.67";	$d21=$ball21*$c[21];}
if (($myrow21['totalmark'] >=80) and  ($myrow21['totalmark'] <=84)) 	{$bukva21= "B"; 	$ball21 = "3.0";		$d21=$ball21*$c[21];}
if (($myrow21['totalmark'] >=85) and  ($myrow21['totalmark'] <=89)) 	{$bukva21= "B+"; 	$ball21 = "3.33";	$d21=$ball21*$c[21];}
if (($myrow21['totalmark'] >=90) and  ($myrow21['totalmark'] <=94)) 	{$bukva21= "A-";	$ball21 = "3.67";	$d21=$ball21*$c[21];}
if (($myrow21['totalmark'] >=95) and  ($myrow21['totalmark'] <=100)) 	{$bukva21= "A"; 	$ball21 = "4.0";		$d21=$ball21*$c[21];}	
	
if (($myrow22['totalmark'] >=0) and    ($myrow22['totalmark'] <=49))   	{$bukva22= "F"; 	$ball22 = "0";		$d22=$ball22*$c[22];}
if (($myrow22['totalmark'] >=50) and  ($myrow22['totalmark'] <=54)) 	{$bukva22= "D"; 	$ball22 = "1.0";		$d22=$ball22*$c[22];}
if (($myrow22['totalmark'] >=55) and  ($myrow22['totalmark'] <=59)) 	{$bukva22= "D+"; 	$ball22 = "1.33";	$d22=$ball22*$c[22];}
if (($myrow22['totalmark'] >=60) and  ($myrow22['totalmark'] <=64)) 	{$bukva22= "C-";	$ball22 = "1.67";	$d22=$ball22*$c[22];}
if (($myrow22['totalmark'] >=65) and  ($myrow22['totalmark'] <=69)) 	{$bukva22= "C"; 	$ball22 = "2.0";		$d22=$ball22*$c[22];}
if (($myrow22['totalmark'] >=70) and  ($myrow22['totalmark'] <=74)) 	{$bukva22= "C+"; 	$ball22 = "2.33";	$d22=$ball22*$c[22];}
if (($myrow22['totalmark'] >=75) and  ($myrow22['totalmark'] <=79)) 	{$bukva22= "B-";	$ball22 = "2.67";	$d22=$ball22*$c[22];}
if (($myrow22['totalmark'] >=80) and  ($myrow22['totalmark'] <=84)) 	{$bukva22= "B"; 	$ball22 = "3.0";		$d22=$ball22*$c[22];}
if (($myrow22['totalmark'] >=85) and  ($myrow22['totalmark'] <=89)) 	{$bukva22= "B+"; 	$ball22 = "3.33";	$d22=$ball22*$c[22];}
if (($myrow22['totalmark'] >=90) and  ($myrow22['totalmark'] <=94)) 	{$bukva22= "A-";	$ball22 = "3.67";	$d22=$ball22*$c[22];}
if (($myrow22['totalmark'] >=95) and  ($myrow22['totalmark'] <=100)) 	{$bukva22= "A"; 	$ball22 = "4.0";		$d22=$ball22*$c[22];}		
	
if (($myrow23['totalmark'] >=0) and    ($myrow23['totalmark'] <=49))   	{$bukva23= "F"; 	$ball23 = "0";		$d23=$ball23*$c[23];}
if (($myrow23['totalmark'] >=50) and  ($myrow23['totalmark'] <=54)) 	{$bukva23= "D"; 	$ball23 = "1.0";		$d23=$ball23*$c[23];}
if (($myrow23['totalmark'] >=55) and  ($myrow23['totalmark'] <=59)) 	{$bukva23= "D+"; 	$ball23 = "1.33";	$d23=$ball23*$c[23];}
if (($myrow23['totalmark'] >=60) and  ($myrow23['totalmark'] <=64)) 	{$bukva23= "C-";	$ball23 = "1.67";	$d23=$ball23*$c[23];}
if (($myrow23['totalmark'] >=65) and  ($myrow23['totalmark'] <=69)) 	{$bukva23= "C"; 	$ball23 = "2.0";		$d23=$ball23*$c[23];}
if (($myrow23['totalmark'] >=70) and  ($myrow23['totalmark'] <=74)) 	{$bukva23= "C+"; 	$ball23 = "2.33";	$d23=$ball23*$c[23];}
if (($myrow23['totalmark'] >=75) and  ($myrow23['totalmark'] <=79)) 	{$bukva23= "B-";	$ball23 = "2.67";	$d23=$ball23*$c[23];}
if (($myrow23['totalmark'] >=80) and  ($myrow23['totalmark'] <=84)) 	{$bukva23= "B"; 	$ball23 = "3.0";		$d23=$ball23*$c[23];}
if (($myrow23['totalmark'] >=85) and  ($myrow23['totalmark'] <=89)) 	{$bukva23= "B+"; 	$ball23 = "3.33";	$d23=$ball23*$c[23];}
if (($myrow23['totalmark'] >=90) and  ($myrow23['totalmark'] <=94)) 	{$bukva23= "A-";	$ball23 = "3.67";	$d23=$ball23*$c[23];}
if (($myrow23['totalmark'] >=95) and  ($myrow23['totalmark'] <=100)) 	{$bukva23= "A"; 	$ball23 = "4.0";		$d23=$ball23*$c[23];}		
	
	if (($myrow24['totalmark'] >=0) and    ($myrow24['totalmark'] <=49))   	{$bukva24= "F"; 	$ball24 = "0";		$d24=$ball24*$c[24];}
if (($myrow24['totalmark'] >=50) and  ($myrow24['totalmark'] <=54)) 	{$bukva24= "D"; 	$ball24 = "1.0";		$d24=$ball24*$c[24];}
if (($myrow24['totalmark'] >=55) and  ($myrow24['totalmark'] <=59)) 	{$bukva24= "D+"; 	$ball24 = "1.33";	$d24=$ball24*$c[24];}
if (($myrow24['totalmark'] >=60) and  ($myrow24['totalmark'] <=64)) 	{$bukva24= "C-";	$ball24 = "1.67";	$d24=$ball24*$c[24];}
if (($myrow24['totalmark'] >=65) and  ($myrow24['totalmark'] <=69)) 	{$bukva24= "C"; 	$ball24 = "2.0";		$d24=$ball24*$c[24];}
if (($myrow24['totalmark'] >=70) and  ($myrow24['totalmark'] <=74)) 	{$bukva24= "C+"; 	$ball24 = "2.33";	$d24=$ball24*$c[24];}
if (($myrow24['totalmark'] >=75) and  ($myrow24['totalmark'] <=79)) 	{$bukva24= "B-";	$ball24 = "2.67";	$d24=$ball24*$c[24];}
if (($myrow24['totalmark'] >=80) and  ($myrow24['totalmark'] <=84)) 	{$bukva24= "B"; 	$ball24 = "3.0";		$d24=$ball24*$c[24];}
if (($myrow24['totalmark'] >=85) and  ($myrow24['totalmark'] <=89)) 	{$bukva24= "B+"; 	$ball24 = "3.33";	$d24=$ball24*$c[24];}
if (($myrow24['totalmark'] >=90) and  ($myrow24['totalmark'] <=94)) 	{$bukva24= "A-";	$ball24 = "3.67";	$d24=$ball24*$c[24];}
if (($myrow24['totalmark'] >=95) and  ($myrow24['totalmark'] <=100)) 	{$bukva24= "A"; 	$ball24 = "4.0";		$d24=$ball24*$c[24];}
	
	$d = $d1+$d2+$d3+$d4+$d5+$d6+$d7+$d8+$d9+$d10+$d11+$d12+$d13+$d14+$d15+$d16+$d17+$d18+$d19+$d20+$d21+$d22+$d23+$d24;

$gpa = round(($d/$credit),2);
	
printf("<tr><td>$x.</td><td>%s %s %s</td><td align=center> %s</td><td align=center> %s</td><td align=center>%s</td><td align=center> %s</td><td align=center>%s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td align=center> %s</td><td> $gpa</td></tr>",$myrow['lastname'],$myrow['firstname'],$myrow['patronymic'],$myrow['zachetka'],$myrow1['totalmark'],$myrow2['totalmark'],$myrow3['totalmark'],$myrow4['totalmark'],$myrow5['totalmark'],$myrow6['totalmark'],$myrow7['totalmark'],$myrow8['totalmark'],$myrow9['totalmark'],$myrow10['totalmark'],$myrow11['totalmark'],$myrow12['totalmark'],$myrow13['totalmark'],$myrow14['totalmark'],$myrow15['totalmark'],$myrow16['totalmark'],$myrow17['totalmark'],$myrow18['totalmark'],$myrow19['totalmark'],$myrow20['totalmark'],$myrow21['totalmark'],$myrow22['totalmark'],$myrow23['totalmark'],$myrow24['totalmark']);


//printf("<tr><td>$x.</td><td>%s %s %s</td><td align=center> %s</td><td align=center> %s</td><td>$bukva1</td><td>$ball1</td><td align=center>%s</td><td>$bukva2</td><td>$ball2</td><td align=center> %s</td><td>$bukva3</td><td>$ball3</td><td align=center>%s</td><td>$bukva4</td><td>$ball4</td><td align=center> %s</td><td>$bukva5</td><td>$ball5</td><td align=center> %s</td><td>$bukva6</td><td>$ball6</td><td align=center> %s</td><td>$bukva7</td><td>$ball7</td><td align=center> %s</td><td>$bukva8</td><td>$ball8</td><td align=center> %s</td><td>$bukva9</td><td>$ball9</td><td align=center> %s</td><td>$bukva10</td><td>$ball10</td><td align=center> %s</td><td>$bukva11</td><td>$ball11</td><td align=center> %s</td><td>$bukva12</td><td>$ball12</td><td align=center> %s</td><td>$bukva13</td><td>$ball13</td><td align=center> %s</td><td>$bukva14</td><td>$ball14</td><td align=center> %s</td><td>$bukva15</td><td>$ball15</td><td align=center> %s</td><td>$bukva16</td><td>$ball16</td><td align=center> %s</td><td>$bukva17</td><td>$ball17</td><td align=center> %s</td><td>$bukva18</td><td>$ball18</td><td align=center> %s</td><td>$bukva19</td><td>$ball19</td><td align=center> %s</td><td>$bukva20</td><td>$ball20</td><td align=center> %s</td><td>$bukva21</td><td>$ball21</td><td align=center> %s</td><td>$bukva22</td><td>$ball22</td><td align=center> %s</td><td>$bukva23</td><td>$ball23</td><td align=center> %s</td><td>$bukva24</td><td>$ball24</td><td> $gpa</td></tr>",$myrow['lastname'],$myrow['firstname'],$myrow['patronymic'],$myrow['zachetka'],$myrow1['totalmark'],$myrow2['totalmark'],$myrow3['totalmark'],$myrow4['totalmark'],$myrow5['totalmark'],$myrow6['totalmark'],$myrow7['totalmark'],$myrow8['totalmark'],$myrow9['totalmark'],$myrow10['totalmark'],$myrow11['totalmark'],$myrow12['totalmark'],$myrow13['totalmark'],$myrow14['totalmark'],$myrow15['totalmark'],$myrow16['totalmark'],$myrow17['totalmark'],$myrow18['totalmark'],$myrow19['totalmark'],$myrow20['totalmark'],$myrow21['totalmark'],$myrow22['totalmark'],$myrow23['totalmark'],$myrow24['totalmark']);


}


while ($myrow = mysql_fetch_array($result));




echo "</table>";
		

?>

<table width=100%><tr><td><p><b>Мониторинг және білім сапасын бағалау <br>бөлімінің бастығы	<br>полиция полковнигі</p></td><td align=right><b>Б.К.Жилкибаев</b></td></tr></table>














                
                
                
                
      
</body>
</html>
