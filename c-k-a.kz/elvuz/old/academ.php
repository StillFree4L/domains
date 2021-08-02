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
            <td width="1" height="100%" valign="top">             <? include("include/menu.php");  ?></td>
            <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><h1>Академическая разница</h1></td>
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
<a href=academ.php?add>Добавить слушателя</a> |  <a href=academ.php?view>Просмотр слушателя</a>


<?

//////////////////////////////////////add to database
if (isset($_REQUEST['i']))
{
$i=$_REQUEST['i'];

	for ($x=1; $x<=$i; $x++)
	{
		$studentID=$_REQUEST['studentID' . $x];
		$rd=$_REQUEST['rd' . $x];
		$exam=$_REQUEST['exam' . $x];
		$total=$_REQUEST['total' . $x];
		$testID=$_REQUEST['testID' . $x];

		if ($rd>0) {$result = mysql_query("update academ_student set rd=$rd where studentID=$studentID and testID=$testID");}
		if ($exam>0) {$result = mysql_query("update academ_student set exam=$exam where studentID=$studentID and testID=$testID");}
		if ($total>0) {$result = mysql_query("update academ_student set total=$total where studentID=$studentID and testID=$testID");}

	}


}
//////////////////////////////////////ISERT studentID
if (isset($_REQUEST['add'])) 
{
$result = mysql_query ("select * from groups order by name");
$myrow = mysql_fetch_array($result);
echo "<br><br><p>";
	do
		{
			  echo "<a href=academ.php?groups=$myrow[groupID]&sem=2&kurs=$myrow[kurs]>$myrow[name]</a> | ";
		}
	while ($myrow = mysql_fetch_array($result));
echo "</p>";

}


if (isset($_REQUEST['groups'])) 
	{
		$groups=$_REQUEST['groups']; 
		$sem=$_REQUEST['sem'];
		$kurs=$_REQUEST['kurs']; 
		$result = mysql_query ("select * from students2 where groupID=$groups");
		$myrow = mysql_fetch_array($result);
echo "<br><br>";
			do
				{
					  echo "<p><a href=academ.php?group=$groups&sem=$sem&t=$myrow[type]&kurs=$kurs&studentID=$myrow[StudentID]>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </a></p>";
				}
			while ($myrow = mysql_fetch_array($result));
	}

if (isset($_REQUEST['studentID'])) 
	{
		$group=$_REQUEST['group']; 
		$sem=$_REQUEST['sem'];
		$kurs=$_REQUEST['kurs']; 
		$studentID=$_REQUEST['studentID']; 
		$result = mysql_query ("select * from academy_razn");
		$myrow = mysql_fetch_array($result);
echo "<table>";
			do
				{
					  echo "<tr><td >$myrow[sem]</td><td>$myrow[type]</td><td><p><a href=academ.php?ved=$myrow[id]&group=$group&sem=$sem&t=$myrow[type]&kurs=$kurs&studentID=$studentID>$myrow[dis]</a> </p></td></tr>";
				}
			while ($myrow = mysql_fetch_array($result));
echo "</table>";
	}


if (isset($_REQUEST['ved'])) 
	{
		$group=$_REQUEST['group']; 
		$sem=$_REQUEST['sem'];
		$ved=$_REQUEST['ved'];
		$kurs=$_REQUEST['kurs']; 
		$studentID=$_REQUEST['studentID']; 
		$result = mysql_query ("INSERT INTO  `nitro`.`academ_student` (`id` ,`kurs` ,`groupID` ,`studentID` ,`testID`)VALUES (NULL ,  '$kurs',  '$group',  '$studentID',  '$ved');");
if ($result==true) {echo "Данные успешно добавлены";} else {echo "Данные не добавлены";}
		//$myrow = mysql_fetch_array($result);
		//	do
		//		{
		//			  echo "<p><a href=academ.php?ved=$myrow[ved]&group=$group&sem=$sem&t=$myrow[type]&kurs=$kurs&studentID=$studentID>$myrow[dis]</a></p>";
		//		}
		//	while ($myrow = mysql_fetch_array($result));
	}

////////////////////////////////delete student

if (isset($_REQUEST['del']))
{
$del=$_REQUEST['del'];
$result2 = mysql_query ("delete  from academ_student where id=$del");
}
///////////////////////////////////////////////////////////VIEW studentID
if (isset($_REQUEST['view'])) 
{
$result = mysql_query ("select * from academ_student order by studentID");
$myrow = mysql_fetch_array($result);
$i=0;
echo "<form action=academ.php method=get name=ins><table><tr><td>Удаление</td><td>Группа</td><td>Тип оценки</td><td>ФИО</td><td>Рейтинг допуска</td><td>Экзамен</td><td>Итоговый</td><td colspan=2>Дисциплина</td></tr>";
	do
		{
$i++;
$result1 = mysql_query ("select * from groups where groupID=$myrow[groupID]");
$myrow1 = mysql_fetch_array($result1);

$result2 = mysql_query ("select * from students2 where StudentID=$myrow[studentID]");
$myrow2 = mysql_fetch_array($result2);

		$result3 = mysql_query ("select * from academy_razn where id=$myrow[testID]");
		$myrow3 = mysql_fetch_array($result3);

  echo "<tr><td><a href=academ.php?view=1&del=$myrow[id]>Удалить</a></td>";
  echo "<td>$myrow1[name]</td>";
if ($myrow3['type']==1)
  {echo "<td>Экзамен</td>";} else {echo "<td>Курсовая</td>";}

  echo "<td><input type=hidden name=studentID$i value=$myrow[studentID]>$myrow2[lastname] $myrow2[firstname] $myrow2[patronymic]</td>";
  echo "<td><input size=3 type=text name=rd$i value=$myrow[rd]></td>";
  echo "<td><input size=3 type=text name=exam$i value=$myrow[exam]></td>";
if (($myrow['rd']>0) and ($myrow['exam']>0)) 
{
$total=round(($myrow['exam']*0.4)+($myrow['rd']*0.6));
 echo "<td><input size=3 type=text name=total$i value=$total></td>";
} 


  echo "<td><input type=hidden name=i value=$i><input type=hidden name=testID$i value=$myrow[testID]>$myrow[testID]</td>";
if ($myrow3['type']==1)
{
  echo "<td><a href=aca_ex.php?ved=$myrow[testID]&studentID=$myrow[studentID]>$myrow3[dis]</td></tr>";
}
else 
{
  echo "<td><a href=aca_cur.php?ved=$myrow[testID]&studentID=$myrow[studentID]>$myrow3[dis]</td></tr>";
}
		}
	while ($myrow = mysql_fetch_array($result));
echo "</table>";



echo "<input type=submit name=submit value='Сохранить'></form>";

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
