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

		$result20 = mysql_query ("select * from disved where groupID=$groupID and god=2017 order by dis");
		$myrow20 = mysql_fetch_array($result20);
		echo "<tr><td align=center><b>р/с<br>п/п</td><td align=center><b>Білм алушының Т.А.Ә <br>ФИО обучающегося</td><td align=center><b>Сынақ кітапшасының нөмері<br>Номер зачетной книжки</td>";
		do
		{
			$i++;			
			$a[$i]=$myrow20['ved'];
			$c[$i]=$myrow20['credit'];
			$credit=$credit+$myrow20['credit'];
			$t[$i]=$myrow20['type'];
//echo "$i - $t[$i] - $a[$i]<br>";
			echo "<td align=center colspan=3><b>$myrow20[dis] </td>";
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
/////////////////////////////////// 1 ///////////////////////////////////////////////////////////
//satu1
	$result1 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[1] and markTypeID=6 and number=1");
	$myrow1 = mysql_fetch_array($result1);
//r1
	$result2 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[1] and markTypeID=2 and number=1");
	$myrow2 = mysql_fetch_array($result2);
//satu2
	$result3 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[1] and markTypeID=6 and number=2");
	$myrow3 = mysql_fetch_array($result3);
//r2
	$result4 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[1] and markTypeID=2 and number=2");
	$myrow4 = mysql_fetch_array($result4);

/////////////////////////////////// 2 ///////////////////////////////////////////////////////////
//satu1
	$result11 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[2] and markTypeID=6 and number=1");
	$myrow11 = mysql_fetch_array($result11);
//r1
	$result12 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[2] and markTypeID=2 and number=1");
	$myrow12 = mysql_fetch_array($result12);
//satu2
	$result13 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[2] and markTypeID=6 and number=2");
	$myrow13 = mysql_fetch_array($result13);
//r2
	$result14 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[2] and markTypeID=2 and number=2");
	$myrow14 = mysql_fetch_array($result14);

/////////////////////////////////// 3 ///////////////////////////////////////////////////////////
//satu1
	$result21 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[3] and markTypeID=6 and number=1");
	$myrow21 = mysql_fetch_array($result21);
//r1
	$result22 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[3] and markTypeID=2 and number=1");
	$myrow22 = mysql_fetch_array($result22);
//satu2
	$result23 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[3] and markTypeID=6 and number=2");
	$myrow23 = mysql_fetch_array($result23);
//r2
	$result24 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[3] and markTypeID=2 and number=2");
	$myrow24 = mysql_fetch_array($result24);

/////////////////////////////////// 4 ///////////////////////////////////////////////////////////
//satu1
	$result31 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[4] and markTypeID=6 and number=1");
	$myrow31 = mysql_fetch_array($result31);
//r1
	$result32 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[4] and markTypeID=2 and number=1");
	$myrow32 = mysql_fetch_array($result32);
//satu2
	$result33 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[4] and markTypeID=6 and number=2");
	$myrow33 = mysql_fetch_array($result33);
//r2
	$result34 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[4] and markTypeID=2 and number=2");
	$myrow34 = mysql_fetch_array($result34);

/////////////////////////////////// 5 ///////////////////////////////////////////////////////////
//satu1
	$result41 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[5] and markTypeID=6 and number=1");
	$myrow41 = mysql_fetch_array($result41);
//r1
	$result42 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[5] and markTypeID=2 and number=1");
	$myrow42 = mysql_fetch_array($result42);
//satu2
	$result43 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[5] and markTypeID=6 and number=2");
	$myrow43 = mysql_fetch_array($result43);
//r2
	$result44 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[5] and markTypeID=2 and number=2");
	$myrow44 = mysql_fetch_array($result44);

/////////////////////////////////// 6 ///////////////////////////////////////////////////////////
//satu1
	$result51 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[6] and markTypeID=6 and number=1");
	$myrow51 = mysql_fetch_array($result51);
//r1
	$result52 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[6] and markTypeID=2 and number=1");
	$myrow52 = mysql_fetch_array($result52);
//satu2
	$result53 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[6] and markTypeID=6 and number=2");
	$myrow53 = mysql_fetch_array($result53);
//r2
	$result54 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[6] and markTypeID=2 and number=2");
	$myrow54 = mysql_fetch_array($result54);

/////////////////////////////////// 7 ///////////////////////////////////////////////////////////
//satu1
	$result61 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[7] and markTypeID=6 and number=1");
	$myrow61 = mysql_fetch_array($result61);
//r1
	$result62 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[7] and markTypeID=2 and number=1");
	$myrow62 = mysql_fetch_array($result62);
//satu2
	$result63 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[7] and markTypeID=6 and number=2");
	$myrow63 = mysql_fetch_array($result63);
//r2
	$result64 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[7] and markTypeID=2 and number=2");
	$myrow64 = mysql_fetch_array($result64);

/////////////////////////////////// 8 ///////////////////////////////////////////////////////////
//satu1
	$result71 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[8] and markTypeID=6 and number=1");
	$myrow71 = mysql_fetch_array($result71);
//r1
	$result72 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[8] and markTypeID=2 and number=1");
	$myrow72 = mysql_fetch_array($result72);
//satu2
	$result73 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[8] and markTypeID=6 and number=2");
	$myrow73 = mysql_fetch_array($result73);
//r2
	$result74 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[8] and markTypeID=2 and number=2");
	$myrow74 = mysql_fetch_array($result74);


/////////////////////////////////// 9 ///////////////////////////////////////////////////////////
//satu1
	$result81 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[9] and markTypeID=6 and number=1");
	$myrow81 = mysql_fetch_array($result81);
//r1
	$result82 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[9] and markTypeID=2 and number=1");
	$myrow82 = mysql_fetch_array($result82);
//satu2
	$result83 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[9] and markTypeID=6 and number=2");
	$myrow83 = mysql_fetch_array($result83);
//r2
	$result84 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[9] and markTypeID=2 and number=2");
	$myrow84 = mysql_fetch_array($result84);

/////////////////////////////////// 10 ///////////////////////////////////////////////////////////
//satu1
	$result91 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[10] and markTypeID=6 and number=1");
	$myrow91 = mysql_fetch_array($result91);
//r1
	$result92 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[10] and markTypeID=2 and number=1");
	$myrow92 = mysql_fetch_array($result92);
//satu2
	$result93 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[10] and markTypeID=6 and number=2");
	$myrow93 = mysql_fetch_array($result93);
//r2
	$result94 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[10] and markTypeID=2 and number=2");
	$myrow94 = mysql_fetch_array($result94);

/////////////////////////////////// 11 ///////////////////////////////////////////////////////////
//satu1
	$result101 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[11] and markTypeID=6 and number=1");
	$myrow101 = mysql_fetch_array($result101);
//r1
	$result102 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[11] and markTypeID=2 and number=1");
	$myrow102 = mysql_fetch_array($result102);
//satu2
	$result103 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[11] and markTypeID=6 and number=2");
	$myrow103 = mysql_fetch_array($result103);
//r2
	$result104 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[11] and markTypeID=2 and number=2");
	$myrow104 = mysql_fetch_array($result104);

/////////////////////////////////// 12 ///////////////////////////////////////////////////////////
//satu1
	$result111 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[12] and markTypeID=6 and number=1");
	$myrow111 = mysql_fetch_array($result111);
//r1
	$result112 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[12] and markTypeID=2 and number=1");
	$myrow112 = mysql_fetch_array($result112);
//satu2
	$result113 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[12] and markTypeID=6 and number=2");
	$myrow113 = mysql_fetch_array($result113);
//r2
	$result114 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[12] and markTypeID=2 and number=2");
	$myrow114 = mysql_fetch_array($result114);
	
	/////////////////////////////////// 13 ///////////////////////////////////////////////////////////
//satu1
//	$result121 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[13] and markTypeID=6 and number=1");
//	$myrow121 = mysql_fetch_array($result121);
//r1
//	$result122 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[13] and markTypeID=2 and number=1");
//	$myrow122 = mysql_fetch_array($result122);
//satu2
//	$result123 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[13] and markTypeID=6 and number=2");
//	$myrow123 = mysql_fetch_array($result123);
//r2
//	$result124 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[13] and markTypeID=2 and number=2");
//	$myrow124 = mysql_fetch_array($result124);

//////////////////////////////////////////// 1 //////////////////////////////////////////////////////////////
if ($t[1]==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[1] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark1 = round((($myrow1['Mark']+$myrow2['Mark']+$myrow3['Mark']+$myrow4['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[1]==2) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[1] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark1 = round((($myrow1['Mark']+$myrow2['Mark']+$myrow3['Mark']+$myrow4['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[1]==3) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[1] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow7 = mysql_fetch_array($result7);

	$totalmark1 = round((($myrow1['Mark']+$myrow2['Mark']+$myrow3['Mark']+$myrow4['Mark'])/4)*0.6+((($myrow5['ball']+$myrow7['ball'])/2)*0.4));
}
if (($totalmark1 >=0) and    ($totalmark1 <=49))   	{$bukva1= "F"; 	$ball1 = "0";		$d1=$ball1*$c[1];}
if (($totalmark1 >=50) and  ($totalmark1 <=54)) 	{$bukva1= "D"; 	$ball1 = "1.0";		$d1=$ball1*$c[1];}
if (($totalmark1 >=55) and  ($totalmark1 <=59)) 	{$bukva1= "D+"; 	$ball1 = "1.33";		$d1=$ball1*$c[1];}
if (($totalmark1 >=60) and  ($totalmark1 <=64)) 	{$bukva1= "C-";	$ball1 = "1.67";		$d1=$ball1*$c[1];}
if (($totalmark1 >=65) and  ($totalmark1 <=69)) 	{$bukva1= "C"; 	$ball1 = "2.0";		$d1=$ball1*$c[1];}
if (($totalmark1 >=70) and  ($totalmark1 <=74)) 	{$bukva1= "C+"; 	$ball1 = "2.33";		$d1=$ball1*$c[1];}
if (($totalmark1 >=75) and  ($totalmark1 <=79)) 	{$bukva1= "B-";	$ball1 = "2.67";		$d1=$ball1*$c[1];}
if (($totalmark1 >=80) and  ($totalmark1 <=84)) 	{$bukva1= "B"; 	$ball1 = "3.0";		$d1=$ball1*$c[1];}
if (($totalmark1 >=85) and  ($totalmark1 <=89)) 	{$bukva1= "B+"; 	$ball1 = "3.33";		$d1=$ball1*$c[1];}
if (($totalmark1 >=90) and  ($totalmark1 <=94)) 	{$bukva1= "A-";	$ball1 = "3.67";		$d1=$ball1*$c[1];}
if (($totalmark1 >=95) and  ($totalmark1 <=100)) 	{$bukva1= "A"; 	$ball1 = "4.0";		$d1=$ball1*$c[1];}

//////////////////////////////////////////// 2 //////////////////////////////////////////////////////////////
if ($t[2]==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[2] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark2 = round((($myrow11['Mark']+$myrow12['Mark']+$myrow13['Mark']+$myrow14['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[2]==2) 
{

	$result6 = mysql_query ("select * from disved where ved=$a[2] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark2 = round((($myrow11['Mark']+$myrow12['Mark']+$myrow13['Mark']+$myrow14['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));

}
if ($t[2]==3) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[2] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow7 = mysql_fetch_array($result7);

	$totalmark2 = round((($myrow11['Mark']+$myrow12['Mark']+$myrow13['Mark']+$myrow14['Mark'])/4)*0.6+((($myrow5['ball']+$myrow7['ball'])/2)*0.4));
}
if (($totalmark2 >=0) and    ($totalmark2 <=49))   	{$bukva2= "F"; 	$ball2 = "0";		$d2=$ball2*$c[2];}
if (($totalmark2 >=50) and  ($totalmark2 <=54)) 	{$bukva2= "D"; 	$ball2 = "1.0";		$d2=$ball2*$c[2];}
if (($totalmark2 >=55) and  ($totalmark2 <=59)) 	{$bukva2= "D+"; 	$ball2 = "1.33";		$d2=$ball2*$c[2];}
if (($totalmark2 >=60) and  ($totalmark2 <=64)) 	{$bukva2= "C-";	$ball2 = "1.67";		$d2=$ball2*$c[2];}
if (($totalmark2 >=65) and  ($totalmark2 <=69)) 	{$bukva2= "C"; 	$ball2 = "2.0";		$d2=$ball2*$c[2];}
if (($totalmark2 >=70) and  ($totalmark2 <=74)) 	{$bukva2= "C+"; 	$ball2 = "2.33";		$d2=$ball2*$c[2];}
if (($totalmark2 >=75) and  ($totalmark2 <=79)) 	{$bukva2= "B-";	$ball2 = "2.67";		$d2=$ball2*$c[2];}
if (($totalmark2 >=80) and  ($totalmark2 <=84)) 	{$bukva2= "B"; 	$ball2 = "3.0";		$d2=$ball2*$c[2];}
if (($totalmark2 >=85) and  ($totalmark2 <=89)) 	{$bukva2= "B+"; 	$ball2 = "3.33";		$d2=$ball2*$c[2];}
if (($totalmark2 >=90) and  ($totalmark2 <=94)) 	{$bukva2= "A-";	$ball2 = "3.67";		$d2=$ball2*$c[2];}
if (($totalmark2 >=95) and  ($totalmark2 <=100)) 	{$bukva2= "A"; 	$ball2 = "4.0";		$d2=$ball2*$c[2];}


//////////////////////////////////////////// 3 //////////////////////////////////////////////////////////////
if ($t[3]==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[3] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark3 = round((($myrow21['Mark']+$myrow22['Mark']+$myrow23['Mark']+$myrow24['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[3]==2) 
{

	$result6 = mysql_query ("select * from disved where ved=$a[3] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark3 = round((($myrow21['Mark']+$myrow22['Mark']+$myrow23['Mark']+$myrow24['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[3]==3) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[3] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow7 = mysql_fetch_array($result7);

	$totalmark3 = round((($myrow21['Mark']+$myrow22['Mark']+$myrow23['Mark']+$myrow24['Mark'])/4)*0.6+((($myrow5['ball']+$myrow7['ball'])/2)*0.4));

}
if (($totalmark3 >=0) and    ($totalmark3 <=49))   	{$bukva3= "F"; 	$ball3 = "0";		$d3=$ball3*$c[3];}
if (($totalmark3 >=50) and  ($totalmark3 <=54)) 	{$bukva3= "D"; 	$ball3 = "1.0";		$d3=$ball3*$c[3];}
if (($totalmark3 >=55) and  ($totalmark3 <=59)) 	{$bukva3= "D+"; 	$ball3 = "1.33";		$d3=$ball3*$c[3];}
if (($totalmark3 >=60) and  ($totalmark3 <=64)) 	{$bukva3= "C-";	$ball3 = "1.67";		$d3=$ball3*$c[3];}
if (($totalmark3 >=65) and  ($totalmark3 <=69)) 	{$bukva3= "C"; 	$ball3 = "2.0";		$d3=$ball3*$c[3];}
if (($totalmark3 >=70) and  ($totalmark3 <=74)) 	{$bukva3= "C+"; 	$ball3 = "2.33";		$d3=$ball3*$c[3];}
if (($totalmark3 >=75) and  ($totalmark3 <=79)) 	{$bukva3= "B-";	$ball3 = "2.67";		$d3=$ball3*$c[3];}
if (($totalmark3 >=80) and  ($totalmark3 <=84)) 	{$bukva3= "B"; 	$ball3 = "3.0";		$d3=$ball3*$c[3];}
if (($totalmark3 >=85) and  ($totalmark3 <=89)) 	{$bukva3= "B+"; 	$ball3 = "3.33";		$d3=$ball3*$c[3];}
if (($totalmark3 >=90) and  ($totalmark3 <=94)) 	{$bukva3= "A-";	$ball3 = "3.67";		$d3=$ball3*$c[3];}
if (($totalmark3 >=95) and  ($totalmark3 <=100)) 	{$bukva3= "A"; 	$ball3 = "4.0";		$d3=$ball3*$c[3];}

//////////////////////////////////////////// 4 //////////////////////////////////////////////////////////////
if ($t[4]==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[4] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark4 = round((($myrow31['Mark']+$myrow32['Mark']+$myrow33['Mark']+$myrow34['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[4]==2) 
{

	$result6 = mysql_query ("select * from disved where ved=$a[4] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark4 = round((($myrow31['Mark']+$myrow32['Mark']+$myrow33['Mark']+$myrow34['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));

}
if ($t[4]==3) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[4] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow7 = mysql_fetch_array($result7);

	$totalmark4 = round((($myrow31['Mark']+$myrow32['Mark']+$myrow33['Mark']+$myrow34['Mark'])/4)*0.6+((($myrow5['ball']+$myrow7['ball'])/2)*0.4));

}
if (($totalmark4 >=0) and    ($totalmark4 <=49))   	{$bukva4= "F"; 	$ball4 = "0";		$d4=$ball4*$c[4];}
if (($totalmark4 >=50) and  ($totalmark4 <=54)) 	{$bukva4= "D"; 	$ball4 = "1.0";		$d4=$ball4*$c[4];}
if (($totalmark4 >=55) and  ($totalmark4 <=59)) 	{$bukva4= "D+"; 	$ball4 = "1.33";		$d4=$ball4*$c[4];}
if (($totalmark4 >=60) and  ($totalmark4 <=64)) 	{$bukva4= "C-";	$ball4 = "1.67";		$d4=$ball4*$c[4];}
if (($totalmark4 >=65) and  ($totalmark4 <=69)) 	{$bukva4= "C"; 	$ball4 = "2.0";		$d4=$ball4*$c[4];}
if (($totalmark4 >=70) and  ($totalmark4 <=74)) 	{$bukva4= "C+"; 	$ball4 = "2.33";		$d4=$ball4*$c[4];}
if (($totalmark4 >=75) and  ($totalmark4 <=79)) 	{$bukva4= "B-";	$ball4 = "2.67";		$d4=$ball4*$c[4];}
if (($totalmark4 >=80) and  ($totalmark4 <=84)) 	{$bukva4= "B"; 	$ball4 = "3.0";		$d4=$ball4*$c[4];}
if (($totalmark4 >=85) and  ($totalmark4 <=89)) 	{$bukva4= "B+"; 	$ball4 = "3.33";		$d4=$ball4*$c[4];}
if (($totalmark4 >=90) and  ($totalmark4 <=94)) 	{$bukva4= "A-";	$ball4 = "3.67";		$d4=$ball4*$c[4];}
if (($totalmark4 >=95) and  ($totalmark4 <=100)) 	{$bukva4= "A"; 	$ball4 = "4.0";		$d4=$ball4*$c[4];}

//////////////////////////////////////////// 5 //////////////////////////////////////////////////////////////
if ($t[5]==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[5] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark5 = round((($myrow41['Mark']+$myrow42['Mark']+$myrow43['Mark']+$myrow44['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[5]==2) 
{

	$result6 = mysql_query ("select * from disved where ved=$a[5] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark5 = round((($myrow41['Mark']+$myrow42['Mark']+$myrow43['Mark']+$myrow44['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
//echo "$t[5] - $a[5] $myrow41[Mark] - $myrow42[Mark] - $myrow43[Mark] - $myrow44[Mark]  - $myrow5[ball] = $totalmark5<br>";
}
if ($t[5]==3) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[5] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow7 = mysql_fetch_array($result7);

	$totalmark5 = round((($myrow41['Mark']+$myrow42['Mark']+$myrow43['Mark']+$myrow44['Mark'])/4)*0.6+((($myrow5['ball']+$myrow7['ball'])/2)*0.4));
//echo "$t[5] - $a[5] $myrow41[Mark] - $myrow42[Mark] - $myrow43[Mark] - $myrow44[Mark]  - $myrow5[ball] - $myrow7[ball] = $totalmark5<br>";
}
if (($totalmark5 >=0) and    ($totalmark5 <=49))   	{$bukva5= "F"; 	$ball5 = "0";		$d5=$ball5*$c[5];}
if (($totalmark5 >=50) and  ($totalmark5 <=54)) 	{$bukva5= "D"; 	$ball5 = "1.0";		$d5=$ball5*$c[5];}
if (($totalmark5 >=55) and  ($totalmark5 <=59)) 	{$bukva5= "D+"; 	$ball5 = "1.33";		$d5=$ball5*$c[5];}
if (($totalmark5 >=60) and  ($totalmark5 <=64)) 	{$bukva5= "C-";	$ball5 = "1.67";		$d5=$ball5*$c[5];}
if (($totalmark5 >=65) and  ($totalmark5 <=69)) 	{$bukva5= "C"; 	$ball5 = "2.0";		$d5=$ball5*$c[5];}
if (($totalmark5 >=70) and  ($totalmark5 <=74)) 	{$bukva5= "C+"; 	$ball5 = "2.33";		$d5=$ball5*$c[5];}
if (($totalmark5 >=75) and  ($totalmark5 <=79)) 	{$bukva5= "B-";	$ball5 = "2.67";		$d5=$ball5*$c[5];}
if (($totalmark5 >=80) and  ($totalmark5 <=84)) 	{$bukva5= "B"; 	$ball5 = "3.0";		$d5=$ball5*$c[5];}
if (($totalmark5 >=85) and  ($totalmark5 <=89)) 	{$bukva5= "B+"; 	$ball5 = "3.33";		$d5=$ball5*$c[5];}
if (($totalmark5 >=90) and  ($totalmark5 <=94)) 	{$bukva5= "A-";	$ball5 = "3.67";		$d5=$ball5*$c[5];}
if (($totalmark5 >=95) and  ($totalmark5 <=100)) 	{$bukva5= "A"; 	$ball5 = "4.0";		$d5=$ball5*$c[5];}


//////////////////////////////////////////// 6 //////////////////////////////////////////////////////////////
if ($t[6]==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[6] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark6 = round((($myrow51['Mark']+$myrow52['Mark']+$myrow53['Mark']+$myrow54['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[6]==2) 
{

	$result6 = mysql_query ("select * from disved where ved=$a[6] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark6 = round((($myrow51['Mark']+$myrow52['Mark']+$myrow53['Mark']+$myrow54['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
//echo "$t[6] - $a[6] $myrow51[Mark] - $myrow52[Mark] - $myrow53[Mark] - $myrow54[Mark]  - $myrow5[ball] = $totalmark6<br>";
}
if ($t[6]==3) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[6] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow7 = mysql_fetch_array($result7);

	$result8 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[6] and markTypeID=7 ");
	$myrow8 = mysql_fetch_array($result8);

	$totalmark6 = round((($myrow51['Mark']+$myrow52['Mark']+$myrow53['Mark']+$myrow54['Mark']+$myrow8['Mark'])/5)*0.6+((($myrow5['ball']+$myrow7['ball'])/2)*0.4));
//echo "$t[6] - $a[6] СР1 - $myrow51[Mark]  Р1-$myrow52[Mark] СР2-$myrow53[Mark] Р2-$myrow54[Mark]  Кур- $myrow8[Mark]  Уст- $myrow5[ball] комп- $myrow7[ball] = $totalmark6<br>";

}
if (($totalmark6 >=0) and    ($totalmark6 <=49))   	{$bukva6= "F"; 	$ball6 = "0";		$d6=$ball6*$c[6];}
if (($totalmark6 >=50) and  ($totalmark6 <=54)) 	{$bukva6= "D"; 	$ball6 = "1.0";		$d6=$ball6*$c[6];}
if (($totalmark6 >=55) and  ($totalmark6 <=59)) 	{$bukva6= "D+"; 	$ball6 = "1.33";		$d6=$ball6*$c[6];}
if (($totalmark6 >=60) and  ($totalmark6 <=64)) 	{$bukva6= "C-";	$ball6 = "1.67";		$d6=$ball6*$c[6];}
if (($totalmark6 >=65) and  ($totalmark6 <=69)) 	{$bukva6= "C"; 	$ball6 = "2.0";		$d6=$ball6*$c[6];}
if (($totalmark6 >=70) and  ($totalmark6 <=74)) 	{$bukva6= "C+"; 	$ball6 = "2.33";		$d6=$ball6*$c[6];}
if (($totalmark6 >=75) and  ($totalmark6 <=79)) 	{$bukva6= "B-";	$ball6 = "2.67";		$d6=$ball6*$c[6];}
if (($totalmark6 >=80) and  ($totalmark6 <=84)) 	{$bukva6= "B"; 	$ball6 = "3.0";		$d6=$ball6*$c[6];}
if (($totalmark6 >=85) and  ($totalmark6 <=89)) 	{$bukva6= "B+"; 	$ball6 = "3.33";		$d6=$ball6*$c[6];}
if (($totalmark6 >=90) and  ($totalmark6 <=94)) 	{$bukva6= "A-";	$ball6 = "3.67";		$d6=$ball6*$c[6];}
if (($totalmark6 >=95) and  ($totalmark6 <=100)) 	{$bukva6= "A"; 	$ball6 = "4.0";		$d6=$ball6*$c[6];}


//////////////////////////////////////////// 7 //////////////////////////////////////////////////////////////
if ($t[7]==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[7] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark7 = round((($myrow61['Mark']+$myrow62['Mark']+$myrow63['Mark']+$myrow64['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[7]==2) 
{

	$result6 = mysql_query ("select * from disved where ved=$a[7] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark7 = round((($myrow61['Mark']+$myrow62['Mark']+$myrow63['Mark']+$myrow64['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
//echo "$t[7] - $a[7] $myrow61[Mark] - $myrow62[Mark] - $myrow63[Mark] - $myrow64[Mark]  - $myrow5[ball] = $totalmark7<br>";
}
if ($t[7]==3) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[7] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow7 = mysql_fetch_array($result7);

	$totalmark7 = round((($myrow61['Mark']+$myrow62['Mark']+$myrow63['Mark']+$myrow64['Mark'])/4)*0.6+((($myrow5['ball']+$myrow7['ball'])/2)*0.4));
//echo "$t[7] - $a[7] $myrow61[Mark] - $myrow62[Mark] - $myrow63[Mark] - $myrow64[Mark]  - $myrow5[ball] - $myrow7[ball] = $totalmark7<br>";

}
if (($totalmark7 >=0) and    ($totalmark7 <=49))   	{$bukva7= "F"; 	$ball7 = "0";		$d7=$ball7*$c[7];}
if (($totalmark7 >=50) and  ($totalmark7 <=54)) 	{$bukva7= "D"; 	$ball7 = "1.0";		$d7=$ball7*$c[7];}
if (($totalmark7 >=55) and  ($totalmark7 <=59)) 	{$bukva7= "D+"; 	$ball7 = "1.33";		$d7=$ball7*$c[7];}
if (($totalmark7 >=60) and  ($totalmark7 <=64)) 	{$bukva7= "C-";	$ball7 = "1.67";		$d7=$ball7*$c[7];}
if (($totalmark7 >=65) and  ($totalmark7 <=69)) 	{$bukva7= "C"; 	$ball7 = "2.0";		$d7=$ball7*$c[7];}
if (($totalmark7 >=70) and  ($totalmark7 <=74)) 	{$bukva7= "C+"; 	$ball7 = "2.33";		$d7=$ball7*$c[7];}
if (($totalmark7 >=75) and  ($totalmark7 <=79)) 	{$bukva7= "B-";	$ball7 = "2.67";		$d7=$ball7*$c[7];}
if (($totalmark7 >=80) and  ($totalmark7 <=84)) 	{$bukva7= "B"; 	$ball7 = "3.0";		$d7=$ball7*$c[7];}
if (($totalmark7 >=85) and  ($totalmark7 <=89)) 	{$bukva7= "B+"; 	$ball7 = "3.33";		$d7=$ball7*$c[7];}
if (($totalmark7 >=90) and  ($totalmark7 <=94)) 	{$bukva7= "A-";	$ball7 = "3.67";		$d7=$ball7*$c[7];}
if (($totalmark7 >=95) and  ($totalmark7 <=100)) 	{$bukva7= "A"; 	$ball7 = "4.0";		$d7=$ball7*$c[7];}

//////////////////////////////////////////// 8 //////////////////////////////////////////////////////////////
if ($t[8]==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[8] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark8 = round((($myrow71['Mark']+$myrow72['Mark']+$myrow73['Mark']+$myrow74['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[8]==2) 
{

	$result6 = mysql_query ("select * from disved where ved=$a[8] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark8 = round((($myrow71['Mark']+$myrow72['Mark']+$myrow73['Mark']+$myrow74['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
//echo "$t[8] - $a[8] $myrow71[Mark] - $myrow72[Mark] - $myrow73[Mark] - $myrow74[Mark]  - $myrow5[ball] = $totalmark8<br>";
}
if ($t[8]==3) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[8] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow7 = mysql_fetch_array($result7);

	$totalmark8 = round((($myrow71['Mark']+$myrow72['Mark']+$myrow73['Mark']+$myrow74['Mark'])/4)*0.6+((($myrow5['ball']+$myrow7['ball'])/2)*0.4));
//echo "$t[8] - $a[8] $myrow71[Mark] - $myrow72[Mark] - $myrow73[Mark] - $myrow74[Mark]  - $myrow5[ball] - $myrow7[ball] = $totalmark8<br>";

}
if (($totalmark8 >=0) and    ($totalmark8 <=49))   	{$bukva8= "F"; 	$ball8 = "0";		$d8=$ball8*$c[8];}
if (($totalmark8 >=50) and  ($totalmark8 <=54)) 	{$bukva8= "D"; 	$ball8 = "1.0";		$d8=$ball8*$c[8];}
if (($totalmark8 >=55) and  ($totalmark8 <=59)) 	{$bukva8= "D+"; 	$ball8 = "1.33";		$d8=$ball8*$c[8];}
if (($totalmark8 >=60) and  ($totalmark8 <=64)) 	{$bukva8= "C-";	$ball8 = "1.67";		$d8=$ball8*$c[8];}
if (($totalmark8 >=65) and  ($totalmark8 <=69)) 	{$bukva8= "C"; 	$ball8 = "2.0";		$d8=$ball8*$c[8];}
if (($totalmark8 >=70) and  ($totalmark8 <=74)) 	{$bukva8= "C+"; 	$ball8 = "2.33";		$d8=$ball8*$c[8];}
if (($totalmark8 >=75) and  ($totalmark8 <=79)) 	{$bukva8= "B-";	$ball8 = "2.67";		$d8=$ball8*$c[8];}
if (($totalmark8 >=80) and  ($totalmark8 <=84)) 	{$bukva8= "B"; 	$ball8 = "3.0";		$d8=$ball8*$c[8];}
if (($totalmark8 >=85) and  ($totalmark8 <=89)) 	{$bukva8= "B+"; 	$ball8 = "3.33";		$d8=$ball8*$c[8];}
if (($totalmark8 >=90) and  ($totalmark8 <=94)) 	{$bukva8= "A-";	$ball8 = "3.67";		$d8=$ball8*$c[8];}
if (($totalmark8 >=95) and  ($totalmark8 <=100)) 	{$bukva8= "A"; 	$ball8 = "4.0";		$d8=$ball8*$c[8];}

//////////////////////////////////////////// 9 //////////////////////////////////////////////////////////////
if ($t[9]==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[9] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark9 = round((($myrow81['Mark']+$myrow82['Mark']+$myrow83['Mark']+$myrow84['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[9]==2) 
{

	$result6 = mysql_query ("select * from disved where ved=$a[9] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark9 = round((($myrow81['Mark']+$myrow82['Mark']+$myrow83['Mark']+$myrow84['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
//echo "$t[9] - $a[9] $myrow81[Mark] - $myrow82[Mark] - $myrow83[Mark] - $myrow84[Mark]  - $myrow5[ball] = $totalmark9<br>";
}
if ($t[9]==3) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[9] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow7 = mysql_fetch_array($result7);

	$totalmark9 = round((($myrow81['Mark']+$myrow82['Mark']+$myrow83['Mark']+$myrow84['Mark'])/4)*0.6+((($myrow5['ball']+$myrow7['ball'])/2)*0.4));
//echo "$t[9] - $a[9] $myrow81[Mark] - $myrow82[Mark] - $myrow83[Mark] - $myrow84[Mark]  - $myrow5[ball] - $myrow7[ball] = $totalmark9<br>";

}
if (($totalmark9 >=0) and    ($totalmark9 <=49))   	{$bukva9= "F"; 	$ball9 = "0";	$d9=$ball9*$c[9];}
if (($totalmark9 >=50) and  ($totalmark9 <=54)) 	{$bukva9= "D"; 	$ball9 = "1.0";		$d9=$ball9*$c[9];}
if (($totalmark9 >=55) and  ($totalmark9 <=59)) 	{$bukva9= "D+"; 	$ball9 = "1.33";		$d9=$ball9*$c[9];}
if (($totalmark9 >=60) and  ($totalmark9 <=64)) 	{$bukva9= "C-";	$ball9 = "1.67";		$d9=$ball9*$c[9];}
if (($totalmark9 >=65) and  ($totalmark9 <=69)) 	{$bukva9= "C"; 	$ball9 = "2.0";		$d9=$ball9*$c[9];}
if (($totalmark9 >=70) and  ($totalmark9 <=74)) 	{$bukva9= "C+"; 	$ball9 = "2.33";		$d9=$ball9*$c[9];}
if (($totalmark9 >=75) and  ($totalmark9 <=79)) 	{$bukva9= "B-";	$ball9 = "2.67";		$d9=$ball9*$c[9];}
if (($totalmark9 >=80) and  ($totalmark9 <=84)) 	{$bukva9= "B"; 	$ball9 = "3.0";		$d9=$ball9*$c[9];}
if (($totalmark9 >=85) and  ($totalmark9 <=89)) 	{$bukva9= "B+"; 	$ball9 = "3.33";		$d9=$ball9*$c[9];}
if (($totalmark9 >=90) and  ($totalmark9 <=94)) 	{$bukva9= "A-";	$ball9 = "3.67";		$d9=$ball9*$c[9];}
if (($totalmark9 >=95) and  ($totalmark9 <=100)) 	{$bukva9= "A"; 	$ball9 = "4.0";	$d9=$ball9*$c[9];}

//////////////////////////////////////////// 10 //////////////////////////////////////////////////////////////
if ($t[10]==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[10] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark10 = round((($myrow91['Mark']+$myrow92['Mark']+$myrow93['Mark']+$myrow94['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
//echo "$t[10] - $a[10] $myrow6[testID]  $myrow91[Mark] - $myrow92[Mark] - $myrow93[Mark] - $myrow94[Mark]  - $myrow5[ball] = $totalmark10<br>";
}
if ($t[10]==2) 
{

	$result6 = mysql_query ("select * from disved where ved=$a[10] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark10 = round((($myrow91['Mark']+$myrow92['Mark']+$myrow93['Mark']+$myrow94['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
//echo "$t[10] - $a[10] $myrow91[Mark] - $myrow92[Mark] - $myrow93[Mark] - $myrow94[Mark]  - $myrow5[ball] = $totalmark10<br>";
}
if ($t[10]==3) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[10] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow7 = mysql_fetch_array($result7);

	$totalmark10 = round((($myrow91['Mark']+$myrow92['Mark']+$myrow93['Mark']+$myrow94['Mark'])/4)*0.6+((($myrow5['ball']+$myrow7['ball'])/2)*0.4));
//echo "$t[10] - $a[10] $myrow91[Mark] - $myrow92[Mark] - $myrow93[Mark] - $myrow94[Mark]  - $myrow5[ball] - $myrow7[ball] = $totalmark10<br>";

}
if (($totalmark10 >=0) and    ($totalmark10 <=49))   	{$bukva10= "F"; 	$ball10 = "0";		$d10=$ball10*$c[10];}
if (($totalmark10 >=50) and  ($totalmark10 <=54)) 	{$bukva10= "D"; 	$ball10 = "1.0";		$d10=$ball10*$c[10];}
if (($totalmark10 >=55) and  ($totalmark10 <=59)) 	{$bukva10= "D+"; 	$ball10 = "1.33";		$d10=$ball10*$c[10];}
if (($totalmark10 >=60) and  ($totalmark10 <=64)) 	{$bukva10= "C-";	$ball10 = "1.67";		$d10=$ball10*$c[10];}
if (($totalmark10 >=65) and  ($totalmark10 <=69)) 	{$bukva10= "C"; 	$ball10 = "2.0";		$d10=$ball10*$c[10];}
if (($totalmark10 >=70) and  ($totalmark10 <=74)) 	{$bukva10= "C+"; 	$ball10 = "2.33";		$d10=$ball10*$c[10];}
if (($totalmark10 >=75) and  ($totalmark10 <=79)) 	{$bukva10= "B-";	$ball10 = "2.67";		$d10=$ball10*$c[10];}
if (($totalmark10 >=80) and  ($totalmark10 <=84)) 	{$bukva10= "B"; 	$ball10 = "3.0";		$d10=$ball10*$c[10];}
if (($totalmark10 >=85) and  ($totalmark10 <=89)) 	{$bukva10= "B+"; 	$ball10 = "3.33";		$d10=$ball10*$c[10];}
if (($totalmark10 >=90) and  ($totalmark10 <=94)) 	{$bukva10= "A-";	$ball10 = "3.67";		$d10=$ball10*$c[10];}
if (($totalmark10 >=95) and  ($totalmark10 <=100)) 	{$bukva10= "A"; 	$ball10 = "4.0";		$d10=$ball10*$c[10];}

//////////////////////////////////////////// 11 //////////////////////////////////////////////////////////////
if ($t[11]==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[11] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark11 = round((($myrow101['Mark']+$myrow102['Mark']+$myrow103['Mark']+$myrow104['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[11]==2) 
{

	$result6 = mysql_query ("select * from disved where ved=$a[11] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark11 = round((($myrow101['Mark']+$myrow102['Mark']+$myrow103['Mark']+$myrow104['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
//echo "$t[11] - $a[11] $myrow101[Mark] - $myrow102[Mark] - $myrow103[Mark] - $myrow104[Mark]  - $myrow5[ball] = $totalmark11<br>";
}
if ($t[11]==3) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[11] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow7 = mysql_fetch_array($result7);

	$totalmark11 = round((($myrow101['Mark']+$myrow102['Mark']+$myrow103['Mark']+$myrow104['Mark'])/4)*0.6+((($myrow5['ball']+$myrow7['ball'])/2)*0.4));
//echo "$t[11] - $a[11] $myrow101[Mark] - $myrow102[Mark] - $myrow103[Mark] - $myrow104[Mark]  - $myrow5[ball] - $myrow7[ball] = $totalmark11<br>";

}
if (($totalmark11 >=0) and    ($totalmark11 <=49))   	{$bukva11= "F"; 	$ball11 = "0";		$d11=$ball11*$c[11];}
if (($totalmark11 >=50) and  ($totalmark11 <=54)) 	{$bukva11= "D"; 	$ball11 = "1.0";		$d11=$ball11*$c[11];}
if (($totalmark11 >=55) and  ($totalmark11 <=59)) 	{$bukva11= "D+"; 	$ball11 = "1.33";		$d11=$ball11*$c[11];}
if (($totalmark11 >=60) and  ($totalmark11 <=64)) 	{$bukva11= "C-";	$ball11 = "1.67";		$d11=$ball11*$c[11];}
if (($totalmark11 >=65) and  ($totalmark11 <=69)) 	{$bukva11= "C"; 	$ball11 = "2.0";		$d11=$ball11*$c[11];}
if (($totalmark11 >=70) and  ($totalmark11 <=74)) 	{$bukva11= "C+"; 	$ball11 = "2.33";		$d11=$ball11*$c[11];}
if (($totalmark11 >=75) and  ($totalmark11 <=79)) 	{$bukva11= "B-";	$ball11 = "2.67";		$d11=$ball11*$c[11];}
if (($totalmark11 >=80) and  ($totalmark11 <=84)) 	{$bukva11= "B"; 	$ball11 = "3.0";		$d11=$ball11*$c[11];}
if (($totalmark11 >=85) and  ($totalmark11 <=89)) 	{$bukva11= "B+"; 	$ball11 = "3.33";		$d11=$ball11*$c[11];}
if (($totalmark11 >=90) and  ($totalmark11 <=94)) 	{$bukva11= "A-";	$ball11 = "3.67";		$d11=$ball11*$c[11];}
if (($totalmark11 >=95) and  ($totalmark11 <=100)) 	{$bukva11= "A"; 	$ball11 = "4.0";		$d11=$ball11*$c[11];}

//////////////////////////////////////////// 12 //////////////////////////////////////////////////////////////
if ($t[12]==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[12] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark12 = round((($myrow111['Mark']+$myrow112['Mark']+$myrow113['Mark']+$myrow114['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}
if ($t[12]==2) 
{

	$result6 = mysql_query ("select * from disved where ved=$a[12] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark12 = round((($myrow111['Mark']+$myrow112['Mark']+$myrow113['Mark']+$myrow114['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
//echo "$t[12] - $a[12] $myrow111[Mark] - $myrow112[Mark] - $myrow113[Mark] - $myrow114[Mark]  - $myrow5[ball] = $totalmark12<br>";
}
if ($t[12]==3) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[12] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[id] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow7 = mysql_fetch_array($result7);

	$totalmark12 = round((($myrow111['Mark']+$myrow112['Mark']+$myrow113['Mark']+$myrow114['Mark'])/4)*0.6+((($myrow5['ball']+$myrow7['ball'])/2)*0.4));
//echo "$t[12] - $a[12] $myrow111[Mark] - $myrow112[Mark] - $myrow113[Mark] - $myrow114[Mark]  - $myrow5[ball] - $myrow7[ball] = $totalmark12<br>";

}
if (($totalmark12 >=0) and    ($totalmark12 <=49))   	{$bukva12= "F"; 	$ball12 = "0";		$d12=$ball12*$c[12];}
if (($totalmark12 >=50) and  ($totalmark12 <=54)) 	{$bukva12= "D"; 	$ball12 = "1.0";		$d12=$ball12*$c[12];}
if (($totalmark12 >=55) and  ($totalmark12 <=59)) 	{$bukva12= "D+"; 	$ball12 = "1.33";		$d12=$ball12*$c[12];}
if (($totalmark12 >=60) and  ($totalmark12 <=64)) 	{$bukva12= "C-";	$ball12 = "1.67";		$d12=$ball12*$c[12];}
if (($totalmark12 >=65) and  ($totalmark12 <=69)) 	{$bukva12= "C"; 	$ball12 = "2.0";		$d12=$ball12*$c[12];}
if (($totalmark12 >=70) and  ($totalmark12 <=74)) 	{$bukva12= "C+"; 	$ball12 = "2.33";		$d12=$ball12*$c[12];}
if (($totalmark12 >=75) and  ($totalmark12 <=79)) 	{$bukva12= "B-";	$ball12 = "2.67";		$d12=$ball12*$c[12];}
if (($totalmark12 >=80) and  ($totalmark12 <=84)) 	{$bukva12= "B"; 	$ball12 = "3.0";		$d12=$ball12*$c[12];}
if (($totalmark12 >=85) and  ($totalmark12 <=89)) 	{$bukva12= "B+"; 	$ball12 = "3.33";		$d12=$ball12*$c[12];}
if (($totalmark12 >=90) and  ($totalmark12 <=94)) 	{$bukva12= "A-";	$ball12 = "3.67";		$d12=$ball12*$c[12];}
if (($totalmark12 >=95) and  ($totalmark12 <=100)) 	{$bukva12= "A"; 	$ball12 = "4.0";		$d12=$ball12*$c[12];}







$dd=$d1+$d2+$d3+$d4+$d5+$d6+$d7+$d8+$d9;
$gpa=round($dd/$credit,2);
//$sum =round(($totalmark + $myrow102['totalmark'] + $myrow103['totalmark'] + $myrow104['totalmark'] + $myrow105['totalmark'] + $myrow106['totalmark'] + $myrow107['totalmark'] + $myrow108['totalmark'] + $myrow109['totalmark'] + $myrow110['totalmark'] + $myrow111['totalmark'])/11);
printf("<tr><td>$x.</td><td>%s %s %s</td><td align=center> %s</td><td align=center> %s</td><td>$bukva1</td><td>$ball1</td><td align=center> %s</td><td>$bukva2</td><td>$ball2</td><td align=center> %s</td><td>$bukva3</td><td>$ball3</td><td align=center> %s</td><td>$bukva4</td><td>$ball4</td><td align=center> %s</td><td>$bukva5</td><td>$ball5</td><td align=center> %s</td><td>$bukva6</td><td>$ball6</td><td align=center> %s</td><td>$bukva7</td><td>$ball7</td><td align=center> %s</td><td>$bukva8</td><td>$ball8</td><td align=center> %s</td><td>$bukva9</td><td>$ball9</td><td align=center> %s</td><td>$bukva10</td><td>$ball10</td><td align=center> %s</td><td>$bukva11</td><td>$ball11</td><td align=center> %s</td><td>$bukva12</td><td>$ball12</td><td>$gpa</td></tr>",$myrow['lastname'],$myrow['firstname'],$myrow['patronymic'],$myrow['zachetka'],$totalmark1,$totalmark2,$totalmark3,$totalmark4,$totalmark5,$totalmark6,$totalmark7,$totalmark8,$totalmark9,$totalmark10,$totalmark11,$totalmark12);

}
while ($myrow = mysql_fetch_array($result));




echo "</table>";
		

?>

<table width=100%><tr><td><p><b>Мониторинг және білім сапасын бағалау <br>бөлімінің бастығы	<br>полиция полковнигі</p></td><td align=right><b>Б.К.Жилкибаев</b></td></tr></table>














                
                
                
                
      
</body>
</html>
