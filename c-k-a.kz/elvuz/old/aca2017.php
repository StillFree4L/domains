<?
include("include/bd.php");
$user_id=$_REQUEST['user_id'];
$ids=$_REQUEST['ids'];
$exam=$_REQUEST['exam'];
$result =mysql_query ("UPDATE  `nitro`.`aca_razn_2017` SET  `exam` =  '$exam' WHERE  `aca_razn_2017`.`id` =$ids  ;");

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
<h1>Академическая разница</h1>                
                
                
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
$result = mysql_query ("select * from aca_razn_2017");
$myrow = mysql_fetch_array($result);
echo "<table border=1><tr><td align=center><b>ФИО курсанта</td><td align=center><b>Дисциплина</td><td align=center><b>СР1</td><td align=center><b>Р1</td><td align=center><b>СР2</td><td align=center><b>Р2</td><td align=center><b>РD</td><td align=center><b>Экз</td><td align=center><b>Итог</td></tr>";
do
{
$rd = round(($myrow['r1'] + $myrow['s1'] + $myrow['r2'] + $myrow['s2'])/4);
echo "<form method=get name=exam1>";
  echo "<tr><td>$myrow[student]</td><td>$myrow[dis]</td><td>$myrow[s1]</td><td><a href=p1aca.php?id=$myrow[id]>$myrow[r1]</a></td><td>$myrow[s2]</td><td><a href=p2aca.php?id=$myrow[id]>$myrow[r2]</a></td><td><a href=pdaca.php?id=$myrow[id]>$rd</a></td><td>";

echo "<input type=text name=exam value=$myrow[exam]>";
echo "<input type=hidden name=user_id value=$user_id>";
echo "<input type=hidden name=ids value=$myrow[id]>";
echo "<input type=submit value=ok>";
echo "</form>";

echo "<a href=exaca.php?id=$myrow[id]>Печать экз.вед</a></td><td>$myrow[itog]</td></tr>";
}
while ($myrow = mysql_fetch_array($result));

echo "</table>";

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
