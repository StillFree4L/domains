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
                <td><h1>Просмотр тестовых результатов!</h1></td>
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
$result = mysql_query ("select * from groups order by name");
$myrow = mysql_fetch_array($result);
echo "<p>";
	do
		{
			  echo "<a href=view_test.php?groups=$myrow[groupID]&kurs=$myrow[kurs]&lang=$myrow[lang]>$myrow[name]</a> | ";
		}
	while ($myrow = mysql_fetch_array($result));
echo "</p>";

if ((isset($_REQUEST['groups'])) and (isset($_REQUEST['kurs'])))
{
$kurs = $_REQUEST['kurs'];
$lang = $_REQUEST['lang'];
$groups=$_REQUEST['groups'];
$result = mysql_query ("select * from test where kurs=$kurs and lang = '$lang'");
$myrow = mysql_fetch_array($result);
echo "<table cellpadding=0 cellspacing=0 border=1 bordercolor=#7fa6bc>";
echo "<tr>";
echo "<td class=le-content><b>Названия теста</td>";
echo "<td class=le-content><b>Язык сдачи</td>";
echo "<td class=le-content><b>Курс</td>";
echo "<td class=le-content><b>Семестр</td>";
echo "<td class=le-content><b>Институты</td>";
echo "<td class=le-content><b>Компьютерлік тестілеу/Компьютерное тестирование</td>";
echo "<td class=le-content><b>Ауызша нысан/Устная форма</td>";
echo "<td class=le-content><b>Құрамдастару нысаны/Комбинирования и форма</td>";
echo "<td class=le-content><b>Арнайы жаттығулар/Специальные упражнения</td>";
echo "</tr>";
do
{
$result1 = mysql_query ("select * from test_type where id=$myrow[type]");
$myrow1 = mysql_fetch_array($result1);

echo "<tr><td class=le-content><a href=view_test.php?kurs=$myrow[kurs]&testID=$myrow[id]&type=$myrow[type]&groupID=$groups>$myrow[namedis]</td><td class=le-content>$myrow[lang]</td><td class=le-content>$myrow[kurs]</td><td class=le-content>$myrow[semestr]</td>";
if ($myrow['institut']==1) {echo "<td class=le-content>ФОО</td>";}
if ($myrow['institut']==2) {echo "<td class=le-content>ИПО</td>";}
if ($myrow['type']==1) {echo "<td class=le-content align=center>+</td><td class=le-content> </td><td class=le-content> </td><td class=le-content> </td></tr>";}
if ($myrow['type']==2) {echo "<td class=le-content> </td><td class=le-content  align=center> + </td><td class=le-content> </td><td class=le-content> </td></tr>";}
if ($myrow['type']==3) {echo "<td class=le-content> </td><td class=le-content> </td><td class=le-content  align=center> + </td><td class=le-content> </td></tr>";}
if ($myrow['type']==4) {echo "<td class=le-content> </td><td class=le-content> </td><td class=le-content> </td><td class=le-content  align=center> + </td></tr>";}
}
while ($myrow = mysql_fetch_array($result));
echo "</table>";
}
if ((isset($_REQUEST['type'])) and (isset($_REQUEST['kurs']))  and  (isset($_REQUEST['testID'])) and (isset($_REQUEST['groupID'])))
{
$kurs = $_REQUEST['kurs'];
$type=$_REQUEST['type'];
$testID = $_REQUEST['testID'];
$groupID=$_REQUEST['groupID'];

	if ($type==3)
		{
			$result = mysql_query ("select * from test_type where id=$type");
			$myrow = mysql_fetch_array($result);
			echo "<h1>$myrow[type_test]</h1>";

			$result1 = mysql_query ("select * from test_work where groupID=$groupID and testID=$testID");
			$myrow1 = mysql_fetch_array($result1);
			$i=0;
			echo "<table border=0><tr><td align=center><b>Ф.И.О.</td><td align=center><b>Баллы за тестирования</td><td align=center><b>Баллы за устное</td></tr>";
			do
				{
					$i++;
					$result2 = mysql_query ("select * from students2 where StudentID=$myrow1[studentID]");
					$myrow2 = mysql_fetch_array($result2);
					$result3 = mysql_query ("select * from oral_exam where groupID=$groupID and testID=$testID and studentID=$myrow1[studentID]");
					$myrow3 = mysql_fetch_array($result3);

					echo "<tr><td>$myrow2[StudentID] $myrow2[lastname] $myrow2[firstname]  $myrow2[patronymic]</td><td><input type=text name=ball$i value=$myrow1[ball]></td><td><input type=text name=oral$i value=$myrow3[ball]></td></tr>";
				}
			while ($myrow1 = mysql_fetch_array($result1));
			echo "</table>";
		}
if ($type==1)
{
			$result = mysql_query ("select * from test_type where id=$type");
			$myrow = mysql_fetch_array($result);
			echo "<h1>$myrow[type_test]</h1>";
$result1 = mysql_query ("select * from test_work where groupID=$groupID and testID=$testID");
			$myrow1 = mysql_fetch_array($result1);
			$i=0;
			echo "<table border=0><tr><td align=center><b>Ф.И.О.</td><td align=center><b>Баллы за тестирования</td></tr>";
			do
				{
					$i++;
					$result2 = mysql_query ("select * from students2 where StudentID=$myrow1[studentID]");
					$myrow2 = mysql_fetch_array($result2);
					echo "<tr><td>$myrow2[StudentID] $myrow2[lastname] $myrow2[firstname]  $myrow2[patronymic]</td><td><input type=text name=ball$i value=$myrow1[ball]></td></tr>";
				}
			while ($myrow1 = mysql_fetch_array($result1));
			echo "</table>";

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
