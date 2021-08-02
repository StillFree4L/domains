<?
include("include/bd.php");
//include ("include/auth.php"); 
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Карагандинская академия МВД РК имени Б.Бейсенова</title>
<meta name="description" content="Education website">
<meta name="keywords" content="education, learning, teaching">

</head>

<body>

<?

if (isset($_REQUEST['id']))
{
	$studentID=$_REQUEST['studentID' ];
	$id=$_REQUEST['id' ];
	$dis1=$_REQUEST['dis1' ];
	$dis2=$_REQUEST['dis2' ];
	$dis3=$_REQUEST['dis3' ];
	$ball=$_REQUEST['ball' ];
//echo "$dis1 - $dis2 - $dis3<br>";	
$result = mysql_query("UPDATE  `nitro`.`test_work4` SET  `ball` =  '$ball',`dis1` =  '$dis1',`dis2` =  '$dis2',`dis3` =  '$dis3' WHERE  `test_work4`.`id` =$id");	
	
}

if (isset($_REQUEST['del'])) 
{

$del=$_REQUEST['del'];
$result = mysql_query("delete from test_work4 WHERE  `test_work4`.`id` =$del");	



}




echo "<table border=1><tr><td>Студент</td><td>Дисциплина 1</td><td>Дисциплина 2</td><td>Дисциплина 3</td><td>Общий</td></tr>";
$result = mysql_query ("select * from test_work4 order by studentID");
$myrow = mysql_fetch_array($result);
do
{
$result1 = mysql_query ("select * from Students2017 where studentID=$myrow[studentID]");
$myrow1 = mysql_fetch_array($result1);

echo "<tr><td><form name=form1 method = get>$myrow1[lastname] $myrow1[firstname] <input type=hidden name=studentID value=$myrow[studentID]></td> <td align=center>$myrow[dis1]</td> <td align=center>$myrow[dis2]</td> <td align=center>$myrow[dis3]</td> <td align=center>$myrow[ball]</td> </tr>";
}
while ($myrow = mysql_fetch_array($result));
echo "<tr><td></td></tr></table>";
?>
</body>
</html>
