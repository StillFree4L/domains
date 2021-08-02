<?
include("include/bd.php");
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
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="1"><img src="images/ml.gif" width="7" height="35"></td>
        <td background="images/mbg.gif" class="bgx"><table border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td class="menu">Личный киабинет мониторинга</td>
          </tr>
        </table></td>
        <td width="1"><img src="images/mr.gif" width="8" height="35"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="1" background="images/cbgl.gif" class="bgy"><img src="images/cbgl.gif" width="7" height="1"></td>
        <td class="cbg"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="1" height="100%" valign="top">
              <? include("include/menu.php");
		   ?>
            </td>
            <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><h1>Ведомости</h1></td>
              </tr>
              <tr>
                <td>
                
                
                
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr align="center">
                    <td width="100%" height="1" align="center">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><img src="images/spacer.gif" width="13" height="1"></td>
                        <td width="100%" bgcolor="#1A658C"><img src="images/spacer.gif" width="1" height="4"></td>
                        <td><img src="images/spacer.gif" width="5" height="1"></td>
                        
                      </tr>
                    </table></td>
                    
                  </tr>
                  <tr>
                    <td valign="top" class="body_txt">


<?   

if (isset($_REQUEST['grup']))
{
$grup = $_REQUEST['grup'];
$idd = $_REQUEST['idd'];
$result = $mysql_query ("UPDATE  arhifved SET  groupID =  $grup WHERE  id = $idd");
if ($result == 'true') {echo "<p>Ваш урок успешно обновлен!</p>";}
else {echo "<p>Ваш урок не обновлен!</p>";}
}

echo "<p><a href=arhifved.php?god=2014&sem=1>2014-2015 Семестр 1</a> <br>";
echo "<a href=arhifved.php?god=2014&sem=2>2014-2015 Семестр 2</a> <br>";
echo "<a href=arhifved.php?god=2015&sem=1>2015-2016 Семестр 1</a> <br>";
echo "<a href=arhifved.php?god=2015&sem=2>2015-2016 Семестр 2</a> </p>";

if (isset($_REQUEST['id']))
{
$id = $_REQUEST['id'];
echo "<form name=gr action=arhifved.php method=get><input type=text name=grup><input type=text name=idd value=$id><input type=submit value=OK></form>";
//$mysql_query ("UPDATE  `nitro`.`arhifved` SET  `groupID` =  '1' WHERE  `arhifved`.`id` = $id;");
}
else
{

if (isset($_REQUEST['vedom'])) 
{
$vedom = $_REQUEST['vedom'];
$dis = $_REQUEST['dis'];

$result = mysql_query ("select * from totalmarks where studygroupID=$vedom order by studentID");
$myrow = mysql_fetch_array($result);

$result1 = mysql_query ("select * from arhifved where id=$dis");
$myrow1 = mysql_fetch_array($result1);

//echo "<table border=1><tr><td align=center><b>ФИО</td><td align=center><b>Сату1</td><td align=center><b>P1</td><td align=center><b>Cату2</td><td align=center><b>P2</td><td align=center><b>Рейтинг допуска</td><td align=center><b>Экзамен</td><td align=center><b>Итоговый</td></tr>";
echo "<h1>$myrow1[namedis]</h1><form action=arhifved.php method=get name=total><table><tr><td align=center><b>ФИО</td><td align=center><b>Рейтинг допуска</td><td align=center><b>Экзамен</td><td align=center><b>Итоговый</td></tr>";
$i=1;
do
{ 
//students
$result10 = mysql_query ("select * from students where StudentID=$myrow[studentID]");
$myrow10 = mysql_fetch_array($result10);

//satu1
$result1 = mysql_query ("select * from journal where StudentID=$myrow10[StudentID] and StudyGroupID=$vedom and markTypeID=6 and number=1");
$myrow1 = mysql_fetch_array($result1);
//r1
$result2 = mysql_query ("select * from journal where StudentID=$myrow10[StudentID] and StudyGroupID=$vedom and markTypeID=2 and number=1");
$myrow2 = mysql_fetch_array($result2);
//satu2
$result3 = mysql_query ("select * from journal where StudentID=$myrow10[StudentID] and StudyGroupID=$vedom and markTypeID=6 and number=2");
$myrow3 = mysql_fetch_array($result3);
//r2
$result4 = mysql_query ("select * from journal where StudentID=$myrow10[StudentID] and StudyGroupID=$vedom and markTypeID=2 and number=2");
$myrow4 = mysql_fetch_array($result4);
//exam itog rd
//$result5 = mysql_query ("select * from totalmarks where StudentID=$myrow[StudentID] and StudyGroupID=$ved ");
//$myrow5 = mysql_fetch_array($result5);

//echo "<tr><td>$myrow10[StudentID] - $myrow10[groupID] -  $myrow10[lastname] $myrow10[firstname] $myrow10[patronymic] </td><td align=center>$myrow1[Mark]</td><td align=center>$myrow2[Mark]</td><td align=center>$myrow3[Mark]</td><td align=center>$myrow4[Mark]</td><td align=center>$myrow[ratings]</td><td align=center>$myrow[exammark]</td><td align=center>$myrow[totalmark]</td></tr>";

echo "<tr><td>$i -         <input type=hidden size=3 name=StudentID$i value=$myrow10[StudentID]>$myrow10[StudentID] $myrow10[lastname] $myrow10[firstname] $myrow10[patronymic] </td>";
echo "<td align=center><input type=text size=3 name=rating$i value=$myrow[ratings]></td>";
echo "<td align=center><input type=text size=3 name=exammark$i value=$myrow[exammark]></td>";
echo "<td align=center><input type=text size=3 name=totalmark$i value=$myrow[totalmark]></td></tr>";


//Mark
//markTypeID
//number
$i++;
}
while ($myrow = mysql_fetch_array($result));
$i--;
echo "<tr><td colspan=4><input type=text size=3 name=kol value=$i><input type=text size=3 name=vedomast value=$vedom><input type=submit value='Сохранить изменения'></td></tr></table>";

} else{








//$result = mysql_query ("select * from erwithappealreports where reportID=$ved");
//$myrow = mysql_fetch_array($result);
//$groupID = $myrow['groupID'];
//echo "$groupID";


//satu1
//$result1 = mysql_query ("select * from journal where StudyGroupID=$groupID and markTypeID=2 and number=1");
//$myrow1 = mysql_fetch_array($result1);
//echo "$myrow1[Mark]";

if ((isset($_REQUEST['god'])) and (isset($_REQUEST['sem'])))
{
$god=$_REQUEST['god'];
$sem=$_REQUEST['sem'];
$result = mysql_query ("select * from arhifved where god=$god and sem=$sem");
$myrow = mysql_fetch_array($result);
echo "<table  border=1> <tr><td>Номер ведомасти</td><td>Номер ведомасти в журнале</td><td>Наименование дисциплины</td><td>Преподаватель</td><td>Код дисциплины</td><td>Год</td><td>Сем</td><td>groupID</td></tr>";
do
{
//$result1 = mysql_query ("select * from erwithappealreports where reportID=$myrow[ved]");
//$myrow1 = mysql_fetch_array($result1);
//$result2 = mysql_query ("update nitro.arhifved set vedom=$myrow1[groupID] where ved=$myrow[ved]");
if ($myrow['groupID'] == 0) {
echo "<tr><td><a href=arhifved.php?ved=$myrow[ved]>$myrow[ved]</a></td><td><a href=arhifved.php?vedom=$myrow[vedom]&dis=$myrow[id]>$myrow[vedom]</a></td><td>$myrow[namedis]</td><td>$myrow[teacher]</td><td>$myrow[code]</td><td>$myrow[god]</td><td>$myrow[sem]</td><td><a href=arhifved.php?id=$myrow[id]>+</a></td></tr>";
}
}
while ($myrow = mysql_fetch_array($result));
echo "</table>";
}
}
}

if (isset($_REQUEST['vedomast']))
{
$vedomast=$_REQUEST['vedomast'];
$kol=$_REQUEST['kol'];
	for ($i=1; $i<=$kol; $i++)
		{
			$StudentID=$_REQUEST['StudentID' . $i];
			$rating=$_REQUEST['rating' . $i];
			$exammark=$_REQUEST['exammark' . $i];
			$totalmark=$_REQUEST['totalmark' . $i];

					 echo "$i - $vedomast - $StudentID - $rating - $exammark - $totalmark<br>";
//sotu1
		 $result = mysql_query("update totalmarks set rating=$rating ,exammark=$exammark, totalmark=$totalmark  where studentID='$StudentID' and studygroupID='$vedomast' "); 



		if ($result == 'true') { echo " Данные успешно обновлены";} else {echo " Данные не обновлены";}				

				}	


}

?>   


                    </td>
                    </tr>
                </table>
                
                
                
                
                
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td width="1" background="images/cbgr.gif" class="bgy"><img src="images/cbgr.gif" width="8" height="1"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="images/bml.gif" width="7" height="24"></td>
        <td width="100%" bgcolor="#0C5282" class="bottom-menu"><a href="#">Главная</a>  |  <a href="#">Форум</a>  |  <a href="#">Контакты</a> </td>
        <td><img src="images/bmr.gif" width="8" height="24"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="1" bgcolor="#1A658C" class="bottom_addr">&copy; 2011-2017 Карагандинская академия МВД РК имени Б.Бейсенова</td>
  </tr>
</table>
</body>
</html>
