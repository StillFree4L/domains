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

<?

$result5 = mysql_query ("select * from totalmarks18 where totalmark > 0");
$myrow5 = mysql_fetch_array($result5);
do
{
$i++;
$res=mysql_query("UPDATE  `nitro`.`totalmarks172` SET  `exammark` =  '$myrow5[exammark]' ,`ap_exammark` =  '$myrow5[ap_exammark]', ratings=$myrow5[ratings], ap_ratings=$myrow5[ap_ratings], rating=$myrow5[rating], ap_rating=$myrow5[ap_rating], totalmark=$myrow5[totalmark], ap_totalmark=$myrow5[totalmark] WHERE  totalmarks172.studygroupID=totalmarks18.studygroupID and totalmarks172.studentID=totalmarks18.studentID");

echo "$i - $myrow5[totalmark] - $myrow5[studentID] - $myrow5[studygroupID]<br>";
}
while ($myrow5 = mysql_fetch_array($result5));
//echo $i;
?>















                
                
                
                
      
</body>
</html>
