<?
include("include/bd.php");

$god = $_REQUEST['god'];
$testID = $_REQUEST['testID'];
$groupID=$_REQUEST['groupID'];

// Названия группы
$result1 = mysql_query ("select * from groups2017 where groupID = '$groupID'");
$myrow1 = mysql_fetch_array($result1);
echo "<p align=center><b>$myrow1[name] взвод</b></p>";



// Названия дисциплины
$result = mysql_query ("select * from disved where god=2017 and testID = '$testID'");
$myrow = mysql_fetch_array($result);
echo "<p align=center><b>Дисциплина $myrow[dis]</b></p>";
?>
<table border=1 align=center>
<tr> 
<td>№</td>
<td>Ф.И.О. курсанта</td>
<td>Баллы</td>
<td>Начало тестирования</td>
<td>Конец тестирования</td>
</tr>

<?

//$result12= mysql_query ("select * from test_work where groupID = '$groupID' and testID=$testID and god=$god");
$result2= mysql_query ("select * from students2017 where groupID = '$groupID'");
$myrow2 = mysql_fetch_array($result2);
$i=1;
do
{
//$result3= mysql_query ("select * from test_work where groupID = 'groupID' and testID='$testID' and studentID='$myrow2[StudentID]'");
$result3 = mysql_query ("select * from test_work where studentID='$myrow2[StudentID]' and testID='$testID' and groupID='$groupID' and god=$god");


$myrow3 = mysql_fetch_array($result3);

  echo "<tr><td>$i</td><td>$myrow2[lastname] $myrow2[firstname]  $myrow2[patronymic]</td><td>$myrow3[ball]</td><td>$myrow3[datab]</td><td>$myrow3[data]</td></tr>";
$i++;
}
while ($myrow2 = mysql_fetch_array($result2));


?>





</table>

</body>
</html>
