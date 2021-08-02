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
$result = mysql_query ("select * from groups2017 order by name");
$myrow = mysql_fetch_array($result);
echo "<p>";
	do
		{
			  echo "<a href=exam.php?groups=$myrow[groupID]&sem=2&kurs=$myrow[kurs]>$myrow[name]</a> | ";
		}
	while ($myrow = mysql_fetch_array($result));
echo "</p>";


if (isset($_REQUEST['groups'])) 
	{
		$groups=$_REQUEST['groups']; 
		$sem=$_REQUEST['sem'];
		$kurs=$_REQUEST['kurs']; 
		$result = mysql_query ("select * from disved where groupID=$groups and god=2017");
		$myrow = mysql_fetch_array($result);
			do
				{
					  echo "<p><a href=ex.php?ved=$myrow[ved]&groups=$groups&testID=$myrow[testID]&t=$myrow[type]&kurs=$kurs>$myrow[dis]</a></p>";
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

		$result = mysql_query ("select * from students2017 where groupID=$groups");
		$myrow = mysql_fetch_array($result);
		$i = 1;
		echo "<a href=ex.php?ved=$ved&groups=$groups&t=$t&kurs=$kurs>Печать</a>Соту1 + Р1 + Соту2 + Р2=РД<table border=1><tr><td>№</td><td align=center><b>Ф.И.О. слушатели</td><td><b>Номер зачетной	книжки</td><td><b>Проценты</td><td><b>Баллы</td><td><b>Буквенная</td></tr>";
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
			$result3 = mysql_query ("select * from journal3 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=2");
			$myrow3 = mysql_fetch_array($result3);
			//r2
			$result4 = mysql_query ("select * from journal3 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=2");
			$myrow4 = mysql_fetch_array($result4);
}
$sotu1=$myrow1['Mark'];
$r1=$myrow2['Mark'];
$sotu2=$myrow3['Mark'];
$r2=$myrow4['Mark'];

if ($sotu1<50) {$sotu1=0;}
if ($sotu2<50) {$sotu2=0;}
if ($r1<50) {$r1=0;}
if ($r2<50) {$r2=0;}
$mark = round(($sotu1+$r1+$sotu2+$r2)/4);


			if ($mark<0) 							{$mark="Неявка по ув.п.";	$bukva= ""; 		$ball = "";}
			if (($mark =='') or       ($mark ==0))		{$mark="";				$bukva= ""; 		$ball = "";}
			if (($mark >=1) and    ($mark <=49))   	{	$bukva= "F"; 		$ball = "0";}
			if (($mark >=50) and  ($mark <=54)) 		{	$bukva= "D"; 		$ball = "1.0";}
			if (($mark >=55) and  ($mark <=59)) 		{	$bukva= "D+"; 	$ball = "1.33";}
			if (($mark >=60) and  ($mark <=64)) 		{	$bukva= "C-";	$ball = "1.67";}
			if (($mark >=65) and  ($mark <=69)) 		{	$bukva= "C"; 		$ball = "2.0";}
			if (($mark >=70) and  ($mark <=74)) 		{	$bukva= "C+"; 	$ball = "2.33";}
			if (($mark >=75) and  ($mark <=79)) 		{	$bukva= "B-";	$ball = "2.67";}
			if (($mark >=80) and  ($mark <=84)) 		{	$bukva= "B"; 		$ball = "3.0";}
			if (($mark >=85) and  ($mark <=89)) 		{	$bukva= "B+"; 	$ball = "3.33";}
			if (($mark >=90) and  ($mark <=94)) 		{	$bukva= "A-";	$ball = "3.67";}
			if (($mark >=95) and  ($mark <=100)) 	{	$bukva= "A"; 		$ball = "4.0";}




//			echo "<tr><td align=right>$i.</td><td>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td><td align=center>$myrow[zachetka]</td><td  align=center>$myrow1[Mark]+$myrow2[Mark]+$myrow3[Mark]+$myrow4[Mark]=$mark</td><td  align=center>$bukva</td><td  align=center>$ball</td></tr>";
			$i++;
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
