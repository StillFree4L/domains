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
            <td width="1" height="100%" valign="top">
              <? 
              //include("include/menu.php");
		   ?>
			  
             
            
            
            
            </td>
            <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><h1>Экзаменационная ведомасть<?
if (isset($_REQUEST['ved'])) 
{
		$ved=$_REQUEST['ved']; 
		$result = mysql_query ("select * from disved where ved=$ved");
		$myrow = mysql_fetch_array($result);
echo "<br>$myrow[dis]";
}
?></h1></td>
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


$i=0;			
$credit=0;
echo "<h3>$myrow[name]</h3><table border=1>";
//
		$result20 = mysql_query ("select * from disved where groupID=77 and god=2017 order by dis");
		$myrow20 = mysql_fetch_array($result20);
		echo "<tr><td align=center><b>ФИО студента</td>";
		do
		{
$i++;			
$a[$i]=$myrow20['ved'];
$c[$i]=$myrow20['credit'];
$credit=$credit+$myrow20['credit'];
$t=$myrow20['type'];
echo "<td align=center colspan=3><b>$myrow20[dis] <br>$myrow20[ved]</td>";
		}
		while ($myrow20 = mysql_fetch_array($result20));
echo "<td><b>Итоговый</td><td align=center><b>GPA</td></tr>";

$result = mysql_query ("select * from students2017 where groupID=77"); 
$myrow = mysql_fetch_array($result);
do
{
for ($j=1; $j<=$i;$j++)
{
//satu1
	$result1 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[$j] and markTypeID=6 and number=1");
	$myrow1 = mysql_fetch_array($result1);
//r1
	$result2 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[$j] and markTypeID=2 and number=1");
	$myrow2 = mysql_fetch_array($result2);
//satu2
	$result3 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[$j] and markTypeID=6 and number=2");
	$myrow3 = mysql_fetch_array($result3);
//r2
	$result4 = mysql_query ("select * from journal2017 where StudentID=$myrow[StudentID] and StudyGroupID=$a[$j] and markTypeID=2 and number=2");
	$myrow4 = mysql_fetch_array($result4);

if ($t==1) 
{
	$result6 = mysql_query ("select * from disved where ved=$a[$j] and god=2017");
	$myrow6 = mysql_fetch_array($result6);

	$result5 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
	$myrow5 = mysql_fetch_array($result5);

	$totalmark = round((($myrow1['Mark']+$myrow2['Mark']+$myrow3['Mark']+$myrow4['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}


if ($t==2) 
{
$result6 = mysql_query ("select * from disved where ved=$a[$j] and god=2017");
$myrow6 = mysql_fetch_array($result6);

$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
$myrow5 = mysql_fetch_array($result5);

$totalmark = round((($myrow1['Mark']+$myrow2['Mark']+$myrow3['Mark']+$myrow4['Mark'])/4)*0.6+(($myrow5['ball'])*0.4));
}

if ($t==3) 
{
$result6 = mysql_query ("select * from disved where ved=$a[$j] and god=2017");
$myrow6 = mysql_fetch_array($result6);

$result5 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
$myrow5 = mysql_fetch_array($result5);

$result7 = mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID=$myrow6[testID] and god=2017");
$myrow7 = mysql_fetch_array($result7);

$totalmark = round((($myrow1['Mark']+$myrow2['Mark']+$myrow3['Mark']+$myrow4['Mark'])/4)*0.6+((($myrow5['ball']+$myrow5['ball'])/2)*0.4));
}



if (($totalmark >=0) and    ($totalmark <=49))   	{$bukva1= "F"; 	$ball1 = "0";		$d1=$ball1*$c[1];}
if (($totalmark >=50) and  ($totalmark <=54)) 	{$bukva1= "D"; 	$ball1 = "1.0";		$d1=$ball1*$c[1];}
if (($totalmark >=55) and  ($totalmark <=59)) 	{$bukva1= "D+"; 	$ball1 = "1.33";		$d1=$ball1*$c[1];}
if (($totalmark >=60) and  ($totalmark <=64)) 	{$bukva1= "C-";	$ball1 = "1.67";		$d1=$ball1*$c[1];}
if (($totalmark >=65) and  ($totalmark <=69)) 	{$bukva1= "C"; 	$ball1 = "2.0";		$d1=$ball1*$c[1];}
if (($totalmark >=70) and  ($totalmark <=74)) 	{$bukva1= "C+"; 	$ball1 = "2.33";		$d1=$ball1*$c[1];}
if (($totalmark >=75) and  ($totalmark <=79)) 	{$bukva1= "B-";	$ball1 = "2.67";		$d1=$ball1*$c[1];}
if (($totalmark >=80) and  ($totalmark <=84)) 	{$bukva1= "B"; 	$ball1 = "3.0";		$d1=$ball1*$c[1];}
if (($totalmark >=85) and  ($totalmark <=89)) 	{$bukva1= "B+"; 	$ball1 = "3.33";		$d1=$ball1*$c[1];}
if (($totalmark >=90) and  ($totalmark <=94)) 	{$bukva1= "A-";	$ball1 = "3.67";		$d1=$ball1*$c[1];}
if (($totalmark >=95) and  ($totalmark <=100)) 	{$bukva1= "A"; 	$ball1 = "4.0";		$d1=$ball1*$c[1];}


//$dd=$d1+$d2+$d3+$d4+$d5+$d6+$d7+$d8+$d9;
//$gpa=round($dd/$credit,2);
//$sum =round(($totalmark + $myrow102['totalmark'] + $myrow103['totalmark'] + $myrow104['totalmark'] + $myrow105['totalmark'] + $myrow106['totalmark'] + $myrow107['totalmark'] + $myrow108['totalmark'] + $myrow109['totalmark'] + $myrow110['totalmark'] + $myrow111['totalmark'])/11);
printf("<tr><td>%s %s %s</td><td align=center> $myrow1[Mark]+$myrow2[Mark]+$myrow3[Mark]+$myrow4[Mark] $myrow5[ball] %s</td><td>$bukva1</td><td>$ball1</td><td align=center> %s</td><td>$bukva2</td><td>$ball2</td><td align=center> %s</td><td>$bukva3</td><td>$ball3</td><td align=center> %s</td><td>$bukva4</td><td>$ball4</td><td align=center> %s</td><td>$bukva5</td><td>$ball5</td><td align=center> %s</td><td>$bukva6</td><td>$ball6</td><td align=center> %s</td><td>$bukva7</td><td>$ball7</td><td align=center> %s</td><td>$bukva8</td><td>$ball8</td><td align=center> %s</td><td>$bukva9</td><td>$ball9</td><td >%s</td><td>$gpa</td></tr>",$myrow['lastname'],$myrow['firstname'],$myrow['patronymic'],$totalmark,$myrow102['totalmark'],$myrow103['totalmark'],$myrow104['totalmark'],$myrow105['totalmark'],$myrow106['totalmark'],$myrow107['totalmark'],$myrow108['totalmark'],$myrow109['totalmark'],$sum);
}
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
