﻿<?
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
            <td width="1" height="100%" valign="top">
              <? include("include/menu.php");
		   ?>
			  
             
            
            
            
            </td>
            <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><h1>Ведомость рубежного контроля 1<?
if (isset($_REQUEST['ved'])) 
{
		$ved=$_REQUEST['ved']; 
		$result = mysql_query ("select * from disved where ved=$ved");
		$myrow = mysql_fetch_array($result);
echo "<br>$myrow[dis]";
}
?> </h1></td>
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

$result = mysql_query ("select * from students2 where groupID=$groups");
$myrow = mysql_fetch_array($result);
$i = 1;
echo "<a href=p1.php?ved=$ved&groups=$groups&kurs=$kurs>Печать</a><table border=1><tr><td>№</td><td align=center><b>Ф.И.О. слушатели</td><td><b>Номер зачетной книжки</td><td><b>Проценты</td><td><b>Баллы</td><td><b>Буквенная</td></tr>";
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
else
{
//satu1
$result1 = mysql_query ("select * from journal2 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=1");
$myrow1 = mysql_fetch_array($result1);
//r1
$result2 = mysql_query ("select * from journal2 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=1");
$myrow2 = mysql_fetch_array($result2);
//satu2
$result3 = mysql_query ("select * from journal2 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=2");
$myrow3 = mysql_fetch_array($result3);
//r2
$result4 = mysql_query ("select * from journal2 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=2");
$myrow4 = mysql_fetch_array($result4);
}
if ($myrow2['Mark']<0) {$mark="Неявка по ув.п.";				$bukva= ""; 		$ball = "";}
if (($myrow2['Mark'] =='') or  ($myrow2['Mark'] ==0))		{$mark="";				$bukva= ""; 		$ball = "";}
if (($myrow2['Mark'] >=1) and  ($myrow2['Mark'] <=49))   	{$mark=$myrow2['Mark'];	$bukva= "F"; 		$ball = "0";}
if (($myrow2['Mark'] >=50) and  ($myrow2['Mark'] <=54)) 	{$mark=$myrow2['Mark'];	$bukva= "D"; 		$ball = "1.0";}
if (($myrow2['Mark'] >=55) and  ($myrow2['Mark'] <=59)) 	{$mark=$myrow2['Mark'];	$bukva= "D+"; 	$ball = "1.33";}
if (($myrow2['Mark'] >=60) and  ($myrow2['Mark'] <=64)) 	{$mark=$myrow2['Mark'];	$bukva= "C-";	$ball = "1.67";}
if (($myrow2['Mark'] >=65) and  ($myrow2['Mark'] <=69)) 	{$mark=$myrow2['Mark'];	$bukva= "C"; 		$ball = "2.0";}
if (($myrow2['Mark'] >=70) and  ($myrow2['Mark'] <=74)) 	{$mark=$myrow2['Mark'];	$bukva= "C+"; 	$ball = "2.33";}
if (($myrow2['Mark'] >=75) and  ($myrow2['Mark'] <=79)) 	{$mark=$myrow2['Mark'];	$bukva= "B-";	$ball = "2.67";}
if (($myrow2['Mark'] >=80) and  ($myrow2['Mark'] <=84)) 	{$mark=$myrow2['Mark'];	$bukva= "B"; 		$ball = "3.0";}
if (($myrow2['Mark'] >=85) and  ($myrow2['Mark'] <=89)) 	{$mark=$myrow2['Mark'];	$bukva= "B+"; 	$ball = "3.33";}
if (($myrow2['Mark'] >=90) and  ($myrow2['Mark'] <=94)) 	{$mark=$myrow2['Mark'];	$bukva= "A-";	$ball = "3.67";}
if (($myrow2['Mark'] >=95) and  ($myrow2['Mark'] <=100)) 	{$mark=$myrow2['Mark'];	$bukva= "A"; 		$ball = "4.0";}



echo "<tr><td align=right>$i.</td><td>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td><td align=center>$myrow[zachetka]</td><td  align=center>$mark</td><td  align=center>$bukva</td><td  align=center>$ball</td></tr>";
$i++;
}
while ($myrow = mysql_fetch_array($result));
echo "</table>";



} else {


if (isset($_REQUEST['groups'])) 
{
$groups=$_REQUEST['groups']; 
$kurs=$_REQUEST['kurs']; 
$sem=$_REQUEST['sem'];

$result = mysql_query ("select * from disved where groupID=$groups and sem=$sem");
$myrow = mysql_fetch_array($result);
do
{
  echo "<a href=r1_2.php?ved=$myrow[ved]&groups=$groups&kurs=$kurs>$myrow[dis]</a><br><br>";
}
while ($myrow = mysql_fetch_array($result));

} else {

$sem=$_REQUEST['sem'];
$result = mysql_query ("select * from groups order by name");
$myrow = mysql_fetch_array($result);
echo "<p>";
	do
		{
			  echo "<a href=r1_2.php?groups=$myrow[groupID]&sem=2&kurs=$myrow[kurs]>$myrow[name]</a> | ";
		}
	while ($myrow = mysql_fetch_array($result));
echo "</p>";





} }?>

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
