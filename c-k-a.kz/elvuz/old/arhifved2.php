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
<table width="765" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
		<div style="position:absolute; top:60px; margin-left:7px; width:200px">
<? include("include/top.php")?>

		</div>
		<img src="images/t1.jpg" width="207" height="237"></td>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><img src="images/t2.gif" width="355" height="37"></td>
          </tr>
          <tr>
            <td><img src="images/t2-5.jpg" width="355" height="200"></td>
          </tr>
        </table></td>
        <td><img src="images/t3.jpg" width="203" height="237"></td>
      </tr>
    </table></td>
  </tr>
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
if (isset($_REQUEST['ved'])) 
{
$ved = $_REQUEST['ved'];
$result = mysql_query ("select * from erwithappealreports where reportID=$ved");
$myrow = mysql_fetch_array($result);
$groupID = $myrow['groupID'];
echo "$groupID";


//satu1
$result1 = mysql_query ("select * from journal where StudyGroupID=$groupID and markTypeID=2 and number=1");
$myrow1 = mysql_fetch_array($result1);
//echo "$myrow1[Mark]";

} 
else {
$result = mysql_query ("select * from arhifved");
$myrow = mysql_fetch_array($result);
echo "<table  border=1> <tr><td>Номер ведомасти</td><td>Наименование дисциплины</td><td>Преподаватель</td><td>Код дисциплины</td><td>Год</td><td>Семестр</td></tr>";
do
{
echo "<tr><td><a href=arhifved.php?ved=$myrow[ved]>$myrow[ved]</a></td><td>$myrow[namedis]</td><td>$myrow[teacher]</td><td>$myrow[code]</td><td>$myrow[god]</td><td>$myrow[sem]</td></tr>";
}
while ($myrow = mysql_fetch_array($result));
echo "</table>";
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
