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
              <? include("include/menu.php");
		   ?>
			  
             
            
            
            
            </td>
            <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><h1>Рейтинг допуска <?
if (isset($_REQUEST['ved'])) 
{
		$ved=$_REQUEST['ved']; 
		$result = mysql_query ("select * from disved where ved=$ved and god=2018");
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
$result = mysql_query ("select * from groups2018 order by name");
$myrow = mysql_fetch_array($result);
echo "<p>";
	do
		{
			  echo "<a href=rd2018.php?groups=$myrow[groupID]&sem=1&kurs=$myrow[kurs]>$myrow[name]</a> | ";
		}
	while ($myrow = mysql_fetch_array($result));
echo "</p>";


if (isset($_REQUEST['groups'])) 
	{
		$groups=$_REQUEST['groups']; 
		$sem=$_REQUEST['sem'];
		$kurs=$_REQUEST['kurs'];
		$result = mysql_query ("select * from disved where groupID=$groups and god=2018 and sem=1");
		$myrow = mysql_fetch_array($result);
			do
				{
					  echo "<p><a href=rd2018.php?ved=$myrow[ved]&groups=$groups&sem=$sem&kurs=$kurs>$myrow[dis]</a></p>";
				}
			while ($myrow = mysql_fetch_array($result));
	}

if (isset($_REQUEST['ved'])) 
	{
		$sem=$_REQUEST['sem'];
		$groups=$_REQUEST['groups']; 
		$ved=$_REQUEST['ved']; 
		$kurs=$_REQUEST['kurs'];
		//echo $ved;

		$result = mysql_query ("select * from students2018 where groupID=$groups");
		$myrow = mysql_fetch_array($result);
		$i = 1;
		echo "<a href=pd2018.php?ved=$ved&groups=$groups&kurs=$kurs>Печать</a><table border=1><tr><td>№</td><td align=center><b>Ф.И.О. слушатели</td><td><b>Номер зачетной	книжки</td><td><b>Рейтинг 1</td><td><b>Рейтинг 2</td><td><b>Рейтинг допуска</td><td><b>Баллы</td><td><b>Буквенная</td></tr>";
	do
		{ 
		//satu1
			$result1 = mysql_query ("select * from journal20182 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=1");
			$myrow1 = mysql_fetch_array($result1);
			//r1
			$result2 = mysql_query ("select * from journal20182 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=1");
			$myrow2 = mysql_fetch_array($result2);
			//satu2
			$result3 = mysql_query ("select * from journal20182 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=2");
			$myrow3 = mysql_fetch_array($result3);
			//r2
			$result4 = mysql_query ("select * from journal20182 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=2");
			$myrow4 = mysql_fetch_array($result4);



$sotu1 = $myrow1['Mark'];
$r1 = $myrow2['Mark'];
$rei1=round(($sotu1+$r1)/2);
$sotu2 = round($myrow3['Mark']);
$r2 = $myrow4['Mark'];
$rei2=round(($sotu2+$r2)/2);
$rd = round(($sotu1+$r1+$sotu2+$r2)/4);
			if ($rd<0) {$mark="Неявка по ув.п.";				$bukva= ""; 		$ball = "";}
			if (($rd =='') or       ($rd ==0))		{$mark="";				$bukva= ""; 		$ball = "";}
			if (($rd >=1) and    ($rd <=49))   	{$mark=$rd;	$bukva= "F"; 		$ball = "0";}
			if (($rd >=50) and  ($rd <=54)) 	{$mark=$rd;	$bukva= "D"; 		$ball = "1.0";}
			if (($rd >=55) and  ($rd <=59)) 	{$mark=$rd;	$bukva= "D+"; 	$ball = "1.33";}
			if (($rd >=60) and  ($rd <=64)) 	{$mark=$rd;	$bukva= "C-";	$ball = "1.67";}
			if (($rd >=65) and  ($rd <=69)) 	{$mark=$rd;	$bukva= "C"; 		$ball = "2.0";}
			if (($rd >=70) and  ($rd <=74)) 	{$mark=$rd;	$bukva= "C+"; 	$ball = "2.33";}
			if (($rd >=75) and  ($rd <=79)) 	{$mark=$rd;	$bukva= "B-";	$ball = "2.67";}
			if (($rd >=80) and  ($rd <=84)) 	{$mark=$rd;	$bukva= "B"; 		$ball = "3.0";}
			if (($rd >=85) and  ($rd <=89)) 	{$mark=$rd;	$bukva= "B+"; 	$ball = "3.33";}
			if (($rd >=90) and  ($rd <=94)) 	{$mark=$rd;	$bukva= "A-";	$ball = "3.67";}
			if (($rd >=95) and  ($rd <=100)) 	{$mark=$rd;	$bukva= "A"; 		$ball = "4.0";}
if ($rd>50)
{			echo "<tr><td align=right>$i.</td><td>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td><td align=center>$myrow[zachetka]</td><td  align=center> $sotu1+ $r1= $rei1</td><td  align=center>$sotu2+$r2=$rei2</td><td  align=center>$mark</td><td  align=center>$bukva</td><td  align=center>$ball</td></tr>";
			$i++;
}
else
{
			echo "<tr bgcolor=##aaffff><td align=right>$i.</td><td>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td><td align=center>$myrow[zachetka]</td><td  align=center> $sotu1+ $r1= $rei1</td><td  align=center>$sotu2+$r2=$rei2</td><td  align=center>$mark</td><td  align=center>$bukva</td><td  align=center>$ball</td></tr>";
			$i++;
}
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
    <td height="1" bgcolor="#1A658C" class="bottom_addr">&copy; 2011-2018 Карагандинская академия МВД РК имени Б.Бейсенова</td>
  </tr>
</table>
</body>
</html>
