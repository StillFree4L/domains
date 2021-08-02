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
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>

<?
$result = mysql_query("select * from nitro.students2017");
$myrow=mysql_fetch_array($result);
do
{
$result2 = mysql_query("UPDATE  `nitro`.`students2018` SET  `zachetka` =  '$myrow[zachetka]' WHERE  `students2018`.`StudentID` =$myrow[StudentID];");
}
while ($myrow=mysql_fetch_array($result));


?>
</body>
</html>
