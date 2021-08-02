<?
include("include/bd.php");
$user_id=$_REQUEST['user_id'];
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
            <td width="1" height="100%" valign="top">
              <? include("include/menu.php");
		   ?>
			  
             
            
            
            
            </td>
            <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><h1>Дополнительные ведомости</h1></td>
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
  echo "<a href=komissia.php?groupID=$myrow[groupID]>$myrow[name]</a> | ";
}
while ($myrow = mysql_fetch_array($result));

echo "<br>";
if (isset($_REQUEST['groupID']))
{
	$groupID = $_REQUEST['groupID'];

	$result = mysql_query ("select * from disved where groupID=$groupID and god=2017 order by dis");
	$myrow = mysql_fetch_array($result);
	do
	{
  		echo "<p><a href=komissia.php?ved=$myrow[ved]&groupID=$groupID>$myrow[dis]</a> </p> ";
	}
	while ($myrow = mysql_fetch_array($result));
}

if (isset($_REQUEST['ved']))
{
	$groupID = $_REQUEST['groupID'];
	$ved = $_REQUEST['ved'];
	echo "<form method=get><table border=1>";
	$result = mysql_query ("select * from students2017  where groupID=$groupID");
	$myrow = mysql_fetch_array($result);
	$i=0;
	do
	{
	$i++;
	$result11 = mysql_query ("select * from  totalmarks18 where studygroupID=$ved and studentID=$myrow[StudentID]");
	$myrow11 = mysql_fetch_array($result11);
	
		  echo "<tr><td>$myrow[lastname] $myrow[firstname] $myrow[patronymic]<input type=hidden name=studentID$i value=$myrow[StudentID]></td><td><input type=text name=totalmark$i value=$myrow11[totalmark]></td></tr>";
	}
	while ($myrow = mysql_fetch_array($result));
	echo "<input type=hidden name=kol1 value=$i><input type=hidden name=ved1 value=$ved><tr><td><input type=submit name=submit value=OK></td></tr></table></form>";
}

if  (isset($_REQUEST['submit']))

{
			$kol = $_REQUEST['kol1'];
			$ved = $_REQUEST['ved1'];
			echo "<br>$ved<br>";
			for ($i=1; $i<=$kol; $i++)
				{
					$studentID=$_REQUEST['studentID' . $i];
					$totalmark=$_REQUEST['totalmark' . $i];
					echo "$studentID $totalmark<br>";
$res=mysql_query("UPDATE  `nitro`.`totalmarks18` SET  totalmark=$totalmark, ap_totalmark=$totalmark WHERE  `totalmarks18`.studygroupID=$ved and `totalmarks18`.studentID=$studentID");					
					
					
					
					
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
