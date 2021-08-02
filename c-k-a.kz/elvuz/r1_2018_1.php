<?
include("include/bd.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
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
            <td class="menu">

</td>
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
                <td><h1>Ведомость рубежного контроля 1</h1></td>
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
<table border=1>
<tr>
<?
$result = mysql_query ("select * from fak");
$myrow = mysql_fetch_array($result);
echo "<td valign=top>";
	do
		{
			  echo "<p><a href=r1_2018_1.php?fak=$myrow[id]>$myrow[fak]</a></p>  ";
		}
	while ($myrow = mysql_fetch_array($result));
echo "</td>";

if (isset($_REQUEST['fak']))
{
echo "<td  valign=top>";
$fak=$_REQUEST['fak'];
//id
//////grup	
//fak
//form
//otd
//spec	
//course
//changed_course
//has_practice
//show

$result = mysql_query ("select * from grup order by changed_course");
$myrow = mysql_fetch_array($result);
do
{
  echo "<a href=r1_2018_1.php?fak=$fak&grup=$myrow[id]>$myrow[grup] | $myrow[changed_course] | $myrow[course] </a><br>";
}
while ($myrow = mysql_fetch_array($result));

echo "</td>";
}




if (isset($_REQUEST['grup']))
{
echo "<td valign=top>";
$grup=$_REQUEST['grup'];
$fak=$_REQUEST['fak'];

//id
//grup	
//dis	
//semestr

$result = mysql_query ("select * from test_gr_dis where grup=$grup");
$myrow = mysql_fetch_array($result);
do
{
$result1 = mysql_query ("select * from dis where id=$myrow[dis]");
$myrow1 = mysql_fetch_array($result1);

  echo "<a href=r1_2018_1.php?fak=$fak&grup=$grup&dis=$myrow[dis]>$myrow[dis] - $myrow1[dis]</a><br>";
}
while ($myrow = mysql_fetch_array($result));

echo "</td>";
}

if (isset($_REQUEST['dis']))
{
$grup=$_REQUEST['grup'];
$fak=$_REQUEST['fak'];
$dis=$_REQUEST['dis'];
echo "<td><table border=1>";

$result = mysql_query ("select * from marks where dis=$dis ");
$myrow = mysql_fetch_array($result);

do
{ 
//satu1
//$result1 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=1");
//$myrow1 = mysql_fetch_array($result1);
//r1
//$result2 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=1");
//$myrow2 = mysql_fetch_array($result2);
//satu2
//$result3 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=2");
//$myrow3 = mysql_fetch_array($result3);
//r2
//$result4 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=2");
//$myrow4 = mysql_fetch_array($result4);



//id
//value
//date
//smstr
//dis	
//ui_id
//type
//tid
//hidden_date
//ltype_id
//theme

//$result1 = mysql_query ("select * from marks where ui_id=$myrow[user_id] ");
//$myrow1 = mysql_fetch_array($result1);

$result1 = mysql_query ("select * from dis where id=$myrow[dis] ");
$myrow1 = mysql_fetch_array($result1);

$result2 = mysql_query ("select * from users_info where user_id=$myrow[ui_id] ");
$myrow2 = mysql_fetch_array($result2);

echo "<tr><td>$myrow[dis] - $myrow1[dis]  </td> <td>$myrow[ui_id] - $myrow2[last_name] $myrow2[first_name] $myrow2[middle_name]</td><td>$myrow[type]</td><td>$myrow[value]</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

//echo "<tr><td align=right class=border-ram>$i.</td><td class=border-ram>$myrow[last_name] $myrow[first_name] $myrow[middle_name] </td><td align=center class=border-ram></td><td align=center class=border-ram>$mark</td><td align=center class=border-ram>$bukva</td><td align=center class=border-ram>$ball</td><td class=border-ram1></td></tr>";
$i++;
}
while ($myrow = mysql_fetch_array($result));

echo "</table></td>";
}








?>
</tr>
</table>
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
    <td height="1" bgcolor="#1A658C" class="bottom_addr"></td>
  </tr>
</table>
</body>
</html>
