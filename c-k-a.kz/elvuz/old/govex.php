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
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
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
            <td width="1" height="100%" valign="top">  <? include("include/menu.php");  ?> </td>
            <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><h1> </h1></td>
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
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=1>"; ?> 1 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=2>"; ?>2 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=3>"; ?>3 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=4>"; ?>4 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=5>"; ?>5 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=6>"; ?>6 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=7>"; ?>7 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=8>"; ?>8 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=9>"; ?>9 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=10>"; ?>10 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=11>"; ?>11 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=12>"; ?>12 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=13>"; ?>13 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=14>"; ?>14 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=15>"; ?>15 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=16>"; ?>16 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=17>"; ?>17 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=18>"; ?>18 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=19>"; ?>19 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=20>"; ?>20 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=21>"; ?>21 </a>
| 
<? echo "<a href=ex2017.php?user_id=$user_id&podgroupID=22>"; ?>22 </a><br>


                    </td>
                    </tr>
<tr><td class="body_txt"><h3>Подгруппа 16</h3>
<table><tr><td>ФИО студента</td><td>Зачетка</td><td>Стау1</td><td>Р1</td><td>Сату2</td><td>Р2</td><td>Экзамен</td></tr><form name=form1 method=post>
<?
$result = mysql_query ("select * from govex where podgroupID=16");

$myrow = mysql_fetch_array($result);
$i=0;
do
{ 
$i++;
$ved = $myrow['disID'];
$result11 = mysql_query ("select * from students2017 where StudentID=$myrow[studentID]");
$myrow11 = mysql_fetch_array($result11);

//satu1
	$result1 = mysql_query ("select * from journal2017 where StudentID=$myrow[studentID] and StudyGroupID=$ved and markTypeID=6 and number=1");
	$myrow1 = mysql_fetch_array($result1);
//r1
	$result2 = mysql_query ("select * from journal2017 where StudentID=$myrow[studentID] and StudyGroupID=$ved and markTypeID=2 and number=1");
	$myrow2 = mysql_fetch_array($result2);
//satu2
	$result3 = mysql_query ("select * from journal2017 where StudentID=$myrow[studentID] and StudyGroupID=$ved and markTypeID=6 and number=2");
	$myrow3 = mysql_fetch_array($result3);
//r2
	$result4 = mysql_query ("select * from journal2017 where StudentID=$myrow[studentID] and StudyGroupID=$ved and markTypeID=2 and number=2");
	$myrow4 = mysql_fetch_array($result4);
//$mark = round(($myrow1['Mark']+$myrow2['Mark']+$myrow3['Mark']+$myrow4['Mark'])/4);

$result6 = mysql_query ("select * from disved where ved=$ved");
$myrow6 = mysql_fetch_array($result6);

//exam
	$result5 = mysql_query ("select * from oral_exam where StudentID=$myrow[studentID] and testID=$myrow6[testID] and god=2017");
	$myrow5 = mysql_fetch_array($result5);



echo "<tr><td><input type=hidden name=StudentID$i value=$myrow11[StudentID]> $myrow11[lastname] $myrow11[firstname] $myrow11[patronymic]</td> ";
echo "<td>$myrow11[zachetka]</td>";
echo "<td><input type=text name=satu$i value=$myrow1[Mark]></td><td><input type=text name=r$i value=$myrow2[Mark]></td> <td><input type=text name=sat$i value=$myrow3[Mark]></td> <td><input type=text name=ru$i value=$myrow4[Mark]></td><td><input type=text name=exam$i value=$myrow5[ball]></td>";
echo "</tr>";
}
while ($myrow = mysql_fetch_array($result));

?>









<tr><td colspan=3>
<?
echo "<input type=hidden name=ved value=$ved><input type=hidden name=i value=$i>";
?>


<input type =submit name=submit value=OK></form> </td></tr>
</table>
</td></tr>
                </table>
<?
if (isset($_REQUEST['satu1']))
{
$kol=$_REQUEST['i'];
$ved=$_REQUEST['ved'];

			for ($i=1; $i<=$kol; $i++)
				{
					$satu1=$_REQUEST['satu' . $i];
					$r1=$_REQUEST['r' . $i];
					$satu2=$_REQUEST['sat' . $i];
					$r2=$_REQUEST['ru' . $i];
					$exam=$_REQUEST['exam' . $i];
					$StudentID=$_REQUEST['StudentID' . $i];

echo "<p>$satu1 $r1 $satu2 $r2 $exam</p>";
					
$result6 = mysql_query ("select * from disved where ved=$ved");
$myrow6 = mysql_fetch_array($result6);



$result5 = mysql_query ("update oral_exam set ball ='$exam' where StudentID=$StudentID and testID=$myrow6[testID] and god=2017");

$result1 = mysql_query("update journal2017 set Mark='$satu1' where StudentID=$StudentID and StudyGroupID=$ved and markTypeID=6 and number=1");  
$result2 = mysql_query("update journal2017 set Mark='$r1' where StudentID=$StudentID and StudyGroupID=$ved and markTypeID=2 and number=1");
$result3 = mysql_query("update journal2017 set Mark='$satu2' where StudentID=$StudentID and StudyGroupID=$ved and markTypeID=6 and number=2");
$result4 = mysql_query("update journal2017 set Mark='$r2' where StudentID=$StudentID and StudyGroupID=$ved and markTypeID=2 and number=2");


//		if ($result == 'true') { echo " Данные успешно обновлены";} else {echo " Данные не обновлены";}				

				}				





}

?>
                
                
                
                
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
