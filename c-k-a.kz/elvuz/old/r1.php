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
                <td><h1>Ведомость рубежного контроля 1 </h1></td>
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
$groups=$_REQUEST['groups']; 
$ved=$_REQUEST['ved']; 
$kurs=$_REQUEST['kurs']; 
//echo $ved;

$result = mysql_query ("select * from students where groupID=$groups");
$myrow = mysql_fetch_array($result);
echo "<table border=1><tr><td>fio</td><td>Проценты</td><td>Баллы</td><td>Буквенная</td></tr>";
do
{ 
if ($kurs==1)
{
//satu1
$result1 = mysql_query ("select * from journal4 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=1");
$myrow1 = mysql_fetch_array($result1);
//r1
$result2 = mysql_query ("select * from journal4 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=1");
$myrow2 = mysql_fetch_array($result2);
//satu2
$result3 = mysql_query ("select * from journal4 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=2");
$myrow3 = mysql_fetch_array($result3);
//r2
$result4 = mysql_query ("select * from journal4 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=2");
$myrow4 = mysql_fetch_array($result4);

}
else {
//satu1
$result1 = mysql_query ("select * from journal where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=1");
$myrow1 = mysql_fetch_array($result1);
//r1
$result2 = mysql_query ("select * from journal where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=1");
$myrow2 = mysql_fetch_array($result2);
//satu2
$result3 = mysql_query ("select * from journal where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=2");
$myrow3 = mysql_fetch_array($result3);
//r2
$result4 = mysql_query ("select * from journal where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=2");
$myrow4 = mysql_fetch_array($result4);
}

echo "<tr><td>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td><td>$myrow2[Mark]</td><td>";



echo "aasssssss</td><td>";



echo "</td></tr>";
//Mark
//markTypeID
//number
}
while ($myrow = mysql_fetch_array($result));
echo "</table>";



} else {


if (isset($_REQUEST['groups'])) 
{
$groups=$_REQUEST['groups']; 
$kurs=$_REQUEST['kurs']; 
$result = mysql_query ("select * from disved where groupID=$groups");
$myrow = mysql_fetch_array($result);
do
{
  echo "<a href=r1.php?ved=$myrow[ved]&groups=$groups&kurs=$kurs>$myrow[dis]</a><br>";
}
while ($myrow = mysql_fetch_array($result));

} else {


?>

  <form action="r1.php" method="get" name="group">
   <p>Выберите группу:  <select name="groups">
<?   
$result = mysql_query ("select * from groups order by name");
$myrow = mysql_fetch_array($result);
echo "<input type=hidden name=kurs value=$myrow[kurs]>";
do
{
  echo "<option value='$myrow[groupID]'>$myrow[name]</option>";
}
while ($myrow = mysql_fetch_array($result));
?>   
   </select> <input type="submit" value="ОK"></p>
  </form>
<? } }?>

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
