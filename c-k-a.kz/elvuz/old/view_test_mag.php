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
                <td><h1>Просмотр тестов!</h1></td>
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

$kurs = 5;

echo "<table cellpadding=0 cellspacing=0 border=1 bordercolor=#7fa6bc>";
echo "<tr>";
echo "<td class=le-content><b>Названия теста</td>";
echo "<td class=le-content><b>Язык сдачи</td>";
echo "</tr>";

$result = mysql_query ("select * from test where kurs=$kurs");
$myrow = mysql_fetch_array($result);
do
{
echo "<tr><td><a href=view_test_mag.php?testID=$myrow[id]&lang=$myrow[lang]>$myrow[namedis]</a></td><td>$myrow[lang]</td><td><a href=res_mag.php?testID=$myrow[id]&lang=$myrow[lang]>Ведомасть</a></td></tr>";  
}
while ($myrow = mysql_fetch_array($result));
echo "</table>";

if (isset($_REQUEST['testID']))
{
$i=0;
$testID=$_REQUEST['testID'];
$lang=$_REQUEST['lang'];
$result1 = mysql_query ("select * from magstu where lang='$lang'");
$myrow1 = mysql_fetch_array($result1);
echo "<table><form action=view_test_mag.php method=get name=ins>";
do
{
$i++;
$result2 = mysql_query ("select * from test_work2 where testID=$testID and studentID=$myrow1[studentID]");
$myrow2 = mysql_fetch_array($result2);
echo "<tr><td>$i - <input name=studentID$i type=hidden value=$myrow1[studentID]> - $myrow1[fio]</td>";
echo "<td><input name=bal$i type=text value=$myrow2[ball]></td></tr>";
}
while ($myrow1 = mysql_fetch_array($result1));
echo "</table>";


echo "<tr><td colspan=2><input name=testID type=text value=$testID></td></tr>";
echo "<tr><td colspan=2><input name=lang type=text value=$lang></td></tr>";
echo "<tr><td colspan=2><input name=kol type=text value=$i></td></tr>";
echo "<tr><td colspan=2><input name=submit type=submit value=Сохранить></td></tr>";

}


if (isset($_REQUEST['kol']))
		{
			$kol = $_REQUEST['kol'];
			$testID = $_REQUEST['testID'];
			$lang = $_REQUEST['lang'];
			echo "<br>$testID - $groupID<br>";
			for ($i=1; $i<=$kol; $i++)
				{
					$studentID=$_REQUEST['studentID' . $i];
					$ball=$_REQUEST['bal' . $i];
//					 echo "$i - $StudentID - $ball<br>";
					$result1 = mysql_query ("select * from test_work2 where studentID='$studentID' and testID='$testID' ");
					$myrow1 = mysql_fetch_array($result1);
						if ($myrow1['ball']=='') 
							{  $result5= mysql_query("INSERT INTO `nitro`.`test_work2` (id,studentID,testID,ball) VALUES (' ' ,'$studentID','$testID','$ball' );");	}
						else 
							{ 
echo "$studentID-$testID-$ball<br>";
$result = mysql_query("update test_work2 set ball='$ball'  where studentID='$studentID' and testID='$testID'"); 	}
					
				}	
if ($result == 'true') { echo " Данные успешно обновлены";} else {echo " Данные не обновлены";}							
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
