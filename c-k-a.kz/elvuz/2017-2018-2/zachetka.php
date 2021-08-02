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


if (isset($_REQUEST['i']))
{
$kol = $_REQUEST['i'];

			for ($i=1; $i<=$kol; $i++)
				{
					$a=$_REQUEST['a' . $i];
					$zach=$_REQUEST['zach' . $i];
//					$credit=$_REQUEST['credit' . $i];
//					$teacher=$_REQUEST['teacher' . $i];
					
//		$result = mysql_query("update students2017 set zachetka='$zach' where StudentID='$a'");  
//		if ($result == 'true') { echo " <p>Данные успешно обновлены</p>";} else {echo " <p>Данные не обновлены</p>";}				

				}				



}




$result1 = mysql_query("select * from groups2017 order by name");
$myrow1 = mysql_fetch_array($result1);

do
{
echo "<h1>$myrow1[name]<h1>";
$result = mysql_query("select * from students2017 where groupID=$myrow1[groupID] order by StudentID");
$myrow = mysql_fetch_array($result);
echo "<table><tr><td>ФИО</td><td>Номср зачетки</td></tr><form method=post name=form1>";
$i=0;
do
{
$i++;
$result1 = mysql_query("UPDATE  `nitro`.`students2017` SET  `zachetka` =  '$myrow[zachetka]' WHERE  `students2017`.`StudentID` =$myrow[StudentID] ;"); 
echo "<tr><td>$i - $myrow[lastname] $myrow[firstname]  $myrow[patronymic]</td><td><input type=text name=zach$i value=$myrow[zachetka]></td><td> <input type=text value=$myrow[StudentID] name=a$i></td></tr>";


}
while ($myrow = mysql_fetch_array($result));
echo "<tr><td colspan=2><input name=i value=$i type=text><input type=submit name=submit></td></tr></form></table>";


}
while ($myrow1 = mysql_fetch_array($result1));


?>


</body>
</html>
