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
                <td><h1></h1></td>
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
$result = mysql_query ("select * from test_work where ball<50 order by studentID");
$myrow = mysql_fetch_array($result);
$i=0;
echo "<table border=1>";
	do
		{
$i++;
$result2 = mysql_query ("select * from students2 where StudentID=$myrow[studentID] order by studentID");
$myrow2 = mysql_fetch_array($result2);
$result3 = mysql_query ("select * from disved where testID=$myrow[testID]");
$myrow3 = mysql_fetch_array($result3);
if ($myrow['ball']==-1) 
{			  
	echo "<tr><td>$i</td><td>$myrow2[lastname] $myrow2[firstname] $myrow2[patronymic]</td><td>$myrow3[dis]</td><td>Не явка</td></tr>";
}
else 
{			  
	echo "<tr><td>$i</td><td>$myrow2[lastname] $myrow2[firstname] $myrow2[patronymic]</td><td>$myrow3[dis]</td><td>$myrow[ball]</td></tr>";
}

		}
	while ($myrow = mysql_fetch_array($result));
echo "</table>";


if (isset($_REQUEST['groups'])) 
	{
		$groups=$_REQUEST['groups']; 
		$sem=$_REQUEST['sem'];
		$result = mysql_query ("select * from disved where groupID=$groups and sem=$sem");
		$myrow = mysql_fetch_array($result);
			do
				{
					  echo "<p><a href=ex1.php?ved=$myrow[ved]&groups=$groups&sem=$sem&t=$myrow[type]>$myrow[dis]</a></p>";
				}
			while ($myrow = mysql_fetch_array($result));
	}

//if (isset($_REQUEST['ved'])) 
//	{
//		$sem=$_REQUEST['sem'];
//		$groups=$_REQUEST['groups']; 
//		$ved=$_REQUEST['ved']; 
		//echo $ved;

//		$result = mysql_query ("select * from students2 where groupID=$groups");
//		$myrow = mysql_fetch_array($result);
//		$i = 1;
//		echo "<a href=ex.php?ved=$ved&groups=$groups&t=$t>Печать</a>Соту1 + Р1 + Соту2 + Р2=РД<table border=1><tr><td>№</td><td align=center><b>Ф.И.О. слушатели</td><td><b>Номер зачетной	книжки</td><td><b>Проценты</td><td><b>Баллы</td><td><b>Буквенная</td></tr>";
//	do
//		{ 
//			//satu1
//			$result1 = mysql_query ("select * from journal2 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=1");
//			$myrow1 = mysql_fetch_array($result1);
//			//r1
//			$result2 = mysql_query ("select * from journal2 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=1");
//			$myrow2 = mysql_fetch_array($result2);
			//satu2
//			$result3 = mysql_query ("select * from journal3 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=2");
//			$myrow3 = mysql_fetch_array($result3);
			//r2
//			$result4 = mysql_query ("select * from journal3 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=2");
//			$myrow4 = mysql_fetch_array($result4);

//$mark = round(($myrow1['Mark']+$myrow2['Mark']+$myrow3['Mark']+$myrow4['Mark'])/4);


//			if ($mark<0) 							{$mark="Неявка по ув.п.";	$bukva= ""; 		$ball = "";}
///			if (($mark =='') or       ($mark ==0))		{$mark="";				$bukva= ""; 		$ball = "";}
//			if (($mark >=1) and    ($mark <=49))   	{	$bukva= "F"; 		$ball = "0";}
//			if (($mark >=50) and  ($mark <=54)) 		{	$bukva= "D"; 		$ball = "1.0";}
//			if (($mark >=55) and  ($mark <=59)) 		{	$bukva= "D+"; 	$ball = "1.33";}
//			if (($mark >=60) and  ($mark <=64)) 		{	$bukva= "C-";	$ball = "1.67";}
//			if (($mark >=65) and  ($mark <=69)) 		{	$bukva= "C"; 		$ball = "2.0";}
//			if (($mark >=70) and  ($mark <=74)) 		{	$bukva= "C+"; 	$ball = "2.33";}
//			if (($mark >=75) and  ($mark <=79)) 		{	$bukva= "B-";	$ball = "2.67";}
//			if (($mark >=80) and  ($mark <=84)) 		{	$bukva= "B"; 		$ball = "3.0";}
//			if (($mark >=85) and  ($mark <=89)) 		{	$bukva= "B+"; 	$ball = "3.33";}
//			if (($mark >=90) and  ($mark <=94)) 		{	$bukva= "A-";	$ball = "3.67";}
//			if (($mark >=95) and  ($mark <=100)) 	{	$bukva= "A"; 		$ball = "4.0";}




//echo "<tr>";
//echo "<td align=right>$i.</td>";
//echo "<td>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td>";
//echo "<td align=center>$myrow[zachetka]</td>";
//echo "<td  align=center>$myrow1[Mark]+$myrow2[Mark]+$myrow3[Mark]+$myrow4[Mark]=$mark</td>";/
//echo "<td  align=center>$bukva</td>";
//echo "<td  align=center>$ball</td>";
//echo "</tr>";
//			$i++;
//		}
//		while ($myrow = mysql_fetch_array($result));
//		echo "</table>";
//}

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
