f72<?
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
                <td><h1>Добро пожаловать!</h1></td>
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
$result = mysql_query ("select * from groups2017 where stateID=0 order by name");
$myrow = mysql_fetch_array($result);
do
{
  echo "<a href=con20173.php?groupID=$myrow[groupID]&kurs=$myrow[kurs]>$myrow[name]</a> | ";
}
while ($myrow = mysql_fetch_array($result));


if (isset($_REQUEST['groupID']))
{
	$groupID = $_REQUEST['groupID'];
$kurs=$_REQUEST['kurs'];
	$result = mysql_query ("select * from disved where groupID=$groupID and god=2017 and sem=2 order by dis");
	$myrow = mysql_fetch_array($result);
echo "<br><br><form action=con20173.php method=get name=edit><table><tr><td></td><td align=center><b>Наименование дисциплины</td> <td  align=center><b>Кредит</td> <td  align=center><b>Преподаватель</td> <td  align=center><b>Год</td> <td  align=center><b>Семестр</td><td  align=center><b>Просмотр</td></tr>";
$i=1;
	do
		{
			echo "<tr><td>$i - <input type=hidden name=disID$i value=$myrow[id]></td>";
			echo "<td>                      <input size=100 type=text name=dis$i value='$myrow[dis]' ></td>";
			echo "<td align=center><input size=3 type=text name=credit$i value='$myrow[credit]'></td>";
			echo "<td>                     <input size=3 type=text name=teacher$i  value='$myrow[teacher]'></td>";
			echo "<td align=center>$myrow[god]</td>";
			echo "<td align=center>$myrow[sem]</td>";
			echo "<td><a href=con20173.php?ved=$myrow[ved]&groupID=$groupID&kurs=$kurs>VIEW</a></td></tr>";
			$i++;
		}
	while ($myrow = mysql_fetch_array($result));
$i--;
echo "<tr><td colspan=7>";
echo "<input type=hidden name=kol value=$i>";
echo "<input type=hidden name=kurs value=$kurs>";
echo "<input type=submit value='Сохранить изменения'></td></table></form>";
}

	if (isset($_REQUEST['kol']))
		{
			$kol = $_REQUEST['kol'];

			for ($i=1; $i<=$kol; $i++)
				{
					$disID=$_REQUEST['disID' . $i];
					$dis=$_REQUEST['dis' . $i];
					$credit=$_REQUEST['credit' . $i];
					$teacher=$_REQUEST['teacher' . $i];
					
		$result = mysql_query("update disved set teacher='$teacher',credit='$credit', dis='$dis' where id='$disID'");  
		if ($result == 'true') { echo " Данные успешно обновлены";} else {echo " Данные не обновлены";}				

				}				

		}

	if (isset($_REQUEST['kol1']))
		{
			$kol = $_REQUEST['kol1'];
			$ved = $_REQUEST['ved1'];
			$kurs = $_REQUEST['kurs'];
			echo "<br>$ved<br>";
			for ($i=1; $i<=$kol; $i++)
				{
					$StudentID=$_REQUEST['StudentID' . $i];
					$sotu1=$_REQUEST['sotu1' . $i];
					$r1=$_REQUEST['r1' . $i];
					$sotu2=$_REQUEST['sotu2' . $i];
					$r2=$_REQUEST['r2' . $i];

					 echo "$i - $StudentID - $sotu1 - $r1 - $sotu2 - $r2<br>";

	$result1 = mysql_query ("select * from journal172 where StudentID='$StudentID' and StudyGroupID='$ved' and markTypeID=6 and number=1");
	$myrow1 = mysql_fetch_array($result1);

	$result2 = mysql_query ("select * from journal172 where StudentID='$StudentID' and StudyGroupID='$ved' and markTypeID=2 and number=1");
	$myrow2 = mysql_fetch_array($result2);
	$result3 = mysql_query ("select * from journal175 where StudentID='$StudentID' and StudyGroupID='$ved' and markTypeID=6 and number=2");
	$myrow3 = mysql_fetch_array($result3);

	$result4 = mysql_query ("select * from journal175 where StudentID='$StudentID' and StudyGroupID='$ved' and markTypeID=2 and number=2");
	$myrow4 = mysql_fetch_array($result4);

if ($myrow1['Mark']=='') 
	{  $result5= mysql_query("INSERT INTO `nitro`.`journal172` (`StudentID`, `Mark`, `MarkDate`, `StudyGroupID`, `markTypeID`, `number`) VALUES ('$StudentID', '$sotu1', '2017-04-20', '$ved', '6', '1');");	}
else 
	{ $result = mysql_query("update journal172 set Mark='$sotu1'  where StudentID='$StudentID' and StudyGroupID='$ved' and markTypeID=6 and number=1"); 	}

if ($myrow2['Mark']=='') 
	{ $result5= mysql_query("INSERT INTO `nitro`.`journal172` (`StudentID`, `Mark`, `MarkDate`, `StudyGroupID`, `markTypeID`, `number`) VALUES ('$StudentID', '$r1', '2017-04-20', '$ved', '2', '1');");	}
else { $result = mysql_query("update journal172 set Mark='$r1'  where StudentID='$StudentID' and StudyGroupID='$ved' and markTypeID=2 and number=1"); }

if ($myrow3['Mark']=='') 
	{ $result5= mysql_query("INSERT INTO `nitro`.`journal175` (`StudentID`, `Mark`, `MarkDate`, `StudyGroupID`, `markTypeID`, `number`) VALUES ('$StudentID', '$sotu2', '2017-04-20', '$ved', '6', '2');");	}
else { $result = mysql_query("update journal175 set Mark='$sotu2'  where StudentID='$StudentID' and StudyGroupID='$ved' and markTypeID=6 and number=2"); }

if ($myrow4['Mark']=='') 
	{ $result5= mysql_query("INSERT INTO `nitro`.`journal175` (`StudentID`, `Mark`, `MarkDate`, `StudyGroupID`, `markTypeID`, `number`) VALUES ('$StudentID', '$r2', '2017-04-20', '$ved', '2', '2');");	}
else 
{ $result = mysql_query("update journal175 set Mark='$r2'  where StudentID='$StudentID' and StudyGroupID='$ved' and markTypeID=2 and number=2"); }


//if ($sotu1<>'')		
//if ($sotu2<>'')		
//if ($r1<>'')			
//if ($r2<>'')			




//		if ($result == 'true') { echo " Данные успешно обновлены";} else {echo " Данные не обновлены";}				

				}				

		}
if (isset($_REQUEST['ved']))
{
	$ved = $_REQUEST['ved'];
	$kurs = $_REQUEST['kurs'];
	$groupID = $_REQUEST['groupID'];
	$result = mysql_query ("select * from students2017  where groupID=$groupID");
	$myrow = mysql_fetch_array($result);
echo "<br><br><form action=con20173.php method=get name=ins><table><tr><td align=center ><b>Код слушателя</td><td align=center><b>ФИО слушателя</td><td align=center><b>Зачетка</td><td align=center><b>Соту 1</td><td align=center><b>Р1</td><td align=center><b>Соту 2</td><td align=center><b>Р2</td><td align=center><b>Рейтинг допуска</td></tr>";
$i=1;
	do
		{
			

		if  ($kurs==1){
			$result1 = mysql_query ("select * from journal172 where StudyGroupID=$ved and StudentID=$myrow[StudentID] and markTypeID=6 and number=1");
			$myrow1 = mysql_fetch_array($result1);

			$result2 = mysql_query ("select * from journal172 where StudyGroupID=$ved and StudentID=$myrow[StudentID] and markTypeID=2 and number=1");
			$myrow2 = mysql_fetch_array($result2);
			
			$result3 = mysql_query ("select * from journal175 where StudyGroupID=$ved and StudentID=$myrow[StudentID] and markTypeID=6 and number=2");
			$myrow3 = mysql_fetch_array($result3);

			$result4 = mysql_query ("select * from journal175 where StudyGroupID=$ved and StudentID=$myrow[StudentID] and markTypeID=2 and number=2");
			$myrow4 = mysql_fetch_array($result4);

}
else {
$result1 = mysql_query ("select * from journal172 where StudyGroupID=$ved and StudentID=$myrow[StudentID] and markTypeID=6 and number=1");
			$myrow1 = mysql_fetch_array($result1);

			$result2 = mysql_query ("select * from journal172 where StudyGroupID=$ved and StudentID=$myrow[StudentID] and markTypeID=2 and number=1");
			$myrow2 = mysql_fetch_array($result2);

			$result3 = mysql_query ("select * from journal175 where StudyGroupID=$ved and StudentID=$myrow[StudentID] and markTypeID=6 and number=2");
			$myrow3 = mysql_fetch_array($result3);

			$result4 = mysql_query ("select * from journal175 where StudyGroupID=$ved and StudentID=$myrow[StudentID] and markTypeID=2 and number=2");
			$myrow4 = mysql_fetch_array($result4);
}


			$rd = round(($myrow1['Mark'] + $myrow2['Mark']+$myrow3['Mark']+$myrow4['Mark'])/4);

if ($rd<50) 
		{
				echo "<tr><td>$i - <input type=hidden size=4 name=StudentID$i value=$myrow[StudentID]> </td>";
				echo "<td>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td>";
				echo "<td>$myrow[zachetka]</td>";
				echo "<td><input type=text size=3 name=sotu1$i value=$myrow1[Mark]></td>";
				echo "<td><input type=text size=3 name=r1$i value=$myrow2[Mark]></td>";
				echo "<td><input type=text size=3 name=sotu2$i value=$myrow3[Mark]></td>";
				echo "<td><input type=text size=3 name=r2$i value=$myrow4[Mark]></td>";

//				echo "<td>$myrow1[Mark]</td>";
//				echo "<td>$myrow2[Mark]</td>";
//				echo "<td>$myrow3[Mark]</td>";
//				echo "<td>$myrow4[Mark]</td>";

				echo "<td align=center  bgcolor=#d3d3d3>$rd</td>";
				echo "<td></td>";
				echo "<td></td></tr>";
		}
else 
		{
				echo "<tr><td>$i - <input type=hidden size=4 name=StudentID$i value=$myrow[StudentID]>  </td>";
				echo "<td>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td>";
				echo "<td>$myrow[zachetka]</td>";
			echo "<td><input type=text size=3 name=sotu1$i value=$myrow1[Mark]></td>";
				echo "<td><input type=text size=3 name=r1$i value=$myrow2[Mark]></td>";
				echo "<td><input type=text size=3 name=sotu2$i value=$myrow3[Mark]></td>";
				echo "<td><input type=text size=3 name=r2$i value=$myrow4[Mark]></td>";

//				echo "<td>$myrow1[Mark]</td>";
//				echo "<td>$myrow2[Mark]</td>";
//				echo "<td>$myrow3[Mark]</td>";
//				echo "<td>$myrow4[Mark]</td>";



				echo "<td align=center >$rd</td>";


				echo "<td></td>";
				echo "<td></td></tr>";
		}
$i++;
}
	while ($myrow = mysql_fetch_array($result));
echo "<tr><td colspan=8>";
$i--;
echo "<input type=hidden name=ved1 value=$ved>";
echo "<input type=hidden name=kol1 value=$i>";
echo "<input type=submit value='Сохранить изменения'></td></table></form>";
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
