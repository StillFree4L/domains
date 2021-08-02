<?
include("include/bd.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Карагандинская академия МВД РК имени Б.Бейсенова</title>

</head>

<body>
<?
if ((isset($_REQUEST['type'])) and (isset($_REQUEST['kurs']))  and  (isset($_REQUEST['testID'])) and (isset($_REQUEST['groupID'])))
{
$kurs = $_REQUEST['kurs'];
$type=$_REQUEST['type'];
$testID = $_REQUEST['testID'];
$groupID=$_REQUEST['groupID'];

			$result = mysql_query ("select * from test_type where id=$type");
			$myrow = mysql_fetch_array($result);
			echo "<h1 align=center>$myrow[type_test]</h1>";

			$result3 = mysql_query ("select * from disved where testID=$testID and god=2017");
			$myrow3 = mysql_fetch_array($result3);
			echo "<h1  align=center>$myrow3[namedis]</h1> ";

			$result1 = mysql_query ("select * from students2017 where groupID=$groupID");
			$myrow1 = mysql_fetch_array($result1);
$i=0;
echo "<table border=1  align=center><tr><td>Ф.И.О.</td><td>Оценка за тест</td><td>Оценка за устный</td></tr>";
do
{
$i++;
$result2 = mysql_query ("select * from oral_exam where studentID='$myrow1[StudentID]' and testID='$testID' and groupID='$groupID' and god=2017" );
$myrow2 = mysql_fetch_array($result2);

$result4 = mysql_query ("select * from disved where id='$testID'");
$myrow4 = mysql_fetch_array($result4);


$result3 = mysql_query ("select * from test_work where studentID='$myrow1[StudentID]' and testID='$myrow4[testID]' and groupID='$groupID'  and god=2017 ");
$myrow3 = mysql_fetch_array($result3);


echo "<tr><td>$i -$myrow1[lastname] $myrow1[firstname]  $myrow1[patronymic]</td><td align=center>$myrow3[ball]</td><td align=center>$myrow2[ball]</td></tr>";
}
while ($myrow1 = mysql_fetch_array($result1));

echo "</table>";

}


?>

</body>
</html>
