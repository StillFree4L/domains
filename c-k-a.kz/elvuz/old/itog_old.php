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

$result = mysql_query ("select * from groups where kurs=1 order by name");
$myrow = mysql_fetch_array($result);

	do
		{
$i=0;			
$credit=0;
echo "<h3>$myrow[name]</h3><table border=1>";
//
		$result2 = mysql_query ("select * from disved where groupID=$myrow[groupID] and sem=2 order by dis");
		$myrow2 = mysql_fetch_array($result2);
		echo "<tr><td align=center><b>ФИО студента</td>";
		do
		{
$i++;			
$a[$i]=$myrow2['ved'];
$c[$i]=$myrow2['credit'];
$credit=$credit+$myrow2['credit'];
echo "<td align=center colspan=3><b>$myrow2[dis] <br>$myrow2[ved]</td>";
		}
		while ($myrow2 = mysql_fetch_array($result2));
echo "<td><b>Итоговый</td><td align=center><b>GPA</td></tr>";
$result1 = mysql_query ("select * from students2 where groupID=$myrow[groupID]"); 
$myrow1 = mysql_fetch_array($result1);
do
{
$result101 = mysql_query ("select * from totalmarks2 where studygroupID=$a[1] and studentID=$myrow1[StudentID]"); 
$myrow101 = mysql_fetch_array($result101);

if (($myrow101['totalmark'] >=0) and    ($myrow101['totalmark'] <=49))   	{$bukva1= "F"; 	$ball1 = "0";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=50) and  ($myrow101['totalmark'] <=54)) 	{$bukva1= "D"; 	$ball1 = "1.0";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=55) and  ($myrow101['totalmark'] <=59)) 	{$bukva1= "D+"; 	$ball1 = "1.33";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=60) and  ($myrow101['totalmark'] <=64)) 	{$bukva1= "C-";	$ball1 = "1.67";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=65) and  ($myrow101['totalmark'] <=69)) 	{$bukva1= "C"; 	$ball1 = "2.0";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=70) and  ($myrow101['totalmark'] <=74)) 	{$bukva1= "C+"; 	$ball1 = "2.33";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=75) and  ($myrow101['totalmark'] <=79)) 	{$bukva1= "B-";	$ball1 = "2.67";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=80) and  ($myrow101['totalmark'] <=84)) 	{$bukva1= "B"; 	$ball1 = "3.0";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=85) and  ($myrow101['totalmark'] <=89)) 	{$bukva1= "B+"; 	$ball1 = "3.33";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=90) and  ($myrow101['totalmark'] <=94)) 	{$bukva1= "A-";	$ball1 = "3.67";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=95) and  ($myrow101['totalmark'] <=100)) 	{$bukva1= "A"; 	$ball1 = "4.0";		$d1=$ball1*$c[1];}

$result102 = mysql_query ("select * from totalmarks2 where studygroupID=$a[2] and studentID=$myrow1[StudentID]"); 
$myrow102 = mysql_fetch_array($result102);

if (($myrow102['totalmark'] >=0) and    ($myrow102['totalmark'] <=49))   	{$bukva2= "F"; 	$ball2 = "0";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=50) and  ($myrow102['totalmark'] <=54)) 	{$bukva2= "D"; 	$ball2 = "1.0";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=55) and  ($myrow102['totalmark'] <=59)) 	{$bukva2= "D+"; 	$ball2 = "1.33";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=60) and  ($myrow102['totalmark'] <=64)) 	{$bukva2= "C-";	$ball2 = "1.67";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=65) and  ($myrow102['totalmark'] <=69)) 	{$bukva2= "C"; 	$ball2 = "2.0";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=70) and  ($myrow102['totalmark'] <=74)) 	{$bukva2= "C+"; 	$ball2 = "2.33";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=75) and  ($myrow102['totalmark'] <=79)) 	{$bukva2= "B-";	$ball2 = "2.67";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=80) and  ($myrow102['totalmark'] <=84)) 	{$bukva2= "B"; 	$ball2 = "3.0";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=85) and  ($myrow102['totalmark'] <=89)) 	{$bukva2= "B+"; 	$ball2 = "3.33";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=90) and  ($myrow102['totalmark'] <=94)) 	{$bukva2= "A-";	$ball2 = "3.67";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=95) and  ($myrow102['totalmark'] <=100)) 	{$bukva2= "A"; 	$ball2 = "4.0";		$d2=$ball2*$c[2];}

$result103 = mysql_query ("select * from totalmarks2 where studygroupID=$a[3] and studentID=$myrow1[StudentID]"); 
$myrow103 = mysql_fetch_array($result103);

if (($myrow103['totalmark'] >=0) and    ($myrow103['totalmark'] <=49))   	{$bukva3= "F"; 	$ball3 = "0";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=50) and  ($myrow103['totalmark'] <=54)) 	{$bukva3= "D"; 	$ball3 = "1.0";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=55) and  ($myrow103['totalmark'] <=59)) 	{$bukva3= "D+"; 	$ball3 = "1.33";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=60) and  ($myrow103['totalmark'] <=64)) 	{$bukva3= "C-";	$ball3 = "1.67";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=65) and  ($myrow103['totalmark'] <=69)) 	{$bukva3= "C"; 	$ball3 = "2.0";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=70) and  ($myrow103['totalmark'] <=74)) 	{$bukva3= "C+"; 	$ball3 = "2.33";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=75) and  ($myrow103['totalmark'] <=79)) 	{$bukva3= "B-";	$ball3 = "2.67";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=80) and  ($myrow103['totalmark'] <=84)) 	{$bukva3= "B"; 	$ball3 = "3.0";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=85) and  ($myrow103['totalmark'] <=89)) 	{$bukva3= "B+"; 	$ball3 = "3.33";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=90) and  ($myrow103['totalmark'] <=94)) 	{$bukva3= "A-";	$ball3 = "3.67";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=95) and  ($myrow103['totalmark'] <=100)) 	{$bukva3= "A"; 	$ball3 = "4.0";		$d3=$ball3*$c[3];}

$result104 = mysql_query ("select * from totalmarks2 where studygroupID=$a[4] and studentID=$myrow1[StudentID]"); 
$myrow104 = mysql_fetch_array($result104);

if (($myrow104['totalmark'] >=0) and    ($myrow104['totalmark'] <=49))   	{$bukva4= "F"; 	$ball4 = "0";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=50) and  ($myrow104['totalmark'] <=54)) 	{$bukva4= "D"; 	$ball4 = "1.0";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=55) and  ($myrow104['totalmark'] <=59)) 	{$bukva4= "D+"; 	$ball4 = "1.33";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=60) and  ($myrow104['totalmark'] <=64)) 	{$bukva4= "C-";	$ball4 = "1.67";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=65) and  ($myrow104['totalmark'] <=69)) 	{$bukva4= "C"; 	$ball4 = "2.0";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=70) and  ($myrow104['totalmark'] <=74)) 	{$bukva4= "C+"; 	$ball4 = "2.33";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=75) and  ($myrow104['totalmark'] <=79)) 	{$bukva4= "B-";	$ball4 = "2.67";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=80) and  ($myrow104['totalmark'] <=84)) 	{$bukva4= "B"; 	$ball4 = "3.0";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=85) and  ($myrow104['totalmark'] <=89)) 	{$bukva4= "B+"; 	$ball4 = "3.33";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=90) and  ($myrow104['totalmark'] <=94)) 	{$bukva4= "A-";	$ball4 = "3.67";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=95) and  ($myrow104['totalmark'] <=100)) 	{$bukva4= "A"; 	$ball4 = "4.0";		$d4=$ball4*$c[4];}

$result105 = mysql_query ("select * from totalmarks2 where studygroupID=$a[5] and studentID=$myrow1[StudentID]"); 
$myrow105 = mysql_fetch_array($result105);

if (($myrow105['totalmark'] >=0) and    ($myrow105['totalmark'] <=49))   	{$bukva5= "F"; 	$ball5 = "0";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=50) and  ($myrow105['totalmark'] <=54)) 	{$bukva5= "D"; 	$ball5 = "1.0";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=55) and  ($myrow105['totalmark'] <=59)) 	{$bukva5= "D+"; 	$ball5 = "1.33";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=60) and  ($myrow105['totalmark'] <=64)) 	{$bukva5= "C-";	$ball5 = "1.67";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=65) and  ($myrow105['totalmark'] <=69)) 	{$bukva5= "C"; 	$ball5 = "2.0";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=70) and  ($myrow105['totalmark'] <=74)) 	{$bukva5= "C+"; 	$ball5 = "2.33";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=75) and  ($myrow105['totalmark'] <=79)) 	{$bukva5= "B-";	$ball5 = "2.67";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=80) and  ($myrow105['totalmark'] <=84)) 	{$bukva5= "B"; 	$ball5 = "3.0";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=85) and  ($myrow105['totalmark'] <=89)) 	{$bukva5= "B+"; 	$ball5 = "3.33";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=90) and  ($myrow105['totalmark'] <=94)) 	{$bukva5= "A-";	$ball5 = "3.67";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=95) and  ($myrow105['totalmark'] <=100)) 	{$bukva5= "A"; 	$ball5 = "4.0";		$d5=$ball5*$c[5];}

$result106 = mysql_query ("select * from totalmarks2 where studygroupID=$a[6] and studentID=$myrow1[StudentID]"); 
$myrow106 = mysql_fetch_array($result106);

if (($myrow106['totalmark'] >=0) and    ($myrow106['totalmark'] <=49))   	{$bukva6= "F"; 	$ball6 = "0";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=50) and  ($myrow106['totalmark'] <=54)) 	{$bukva6= "D"; 	$ball6 = "1.0";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=55) and  ($myrow106['totalmark'] <=59)) 	{$bukva6= "D+"; 	$ball6 = "1.33";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=60) and  ($myrow106['totalmark'] <=64)) 	{$bukva6= "C-";	$ball6 = "1.67";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=65) and  ($myrow106['totalmark'] <=69)) 	{$bukva6= "C"; 	$ball6 = "2.0";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=70) and  ($myrow106['totalmark'] <=74)) 	{$bukva6= "C+"; 	$ball6 = "2.33";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=75) and  ($myrow106['totalmark'] <=79)) 	{$bukva6= "B-";	$ball6 = "2.67";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=80) and  ($myrow106['totalmark'] <=84)) 	{$bukva6= "B"; 	$ball6 = "3.0";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=85) and  ($myrow106['totalmark'] <=89)) 	{$bukva6= "B+"; 	$ball6 = "3.33";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=90) and  ($myrow106['totalmark'] <=94)) 	{$bukva6= "A-";	$ball6 = "3.67";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=95) and  ($myrow106['totalmark'] <=100)) 	{$bukva6= "A"; 	$ball6 = "4.0";		$d6=$ball6*$c[6];}

$result107 = mysql_query ("select * from totalmarks2 where studygroupID=$a[7] and studentID=$myrow1[StudentID]"); 
$myrow107 = mysql_fetch_array($result107);

if (($myrow107['totalmark'] >=0) and    ($myrow107['totalmark'] <=49))   	{$bukva7= "F"; 	$ball7 = "0";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=50) and  ($myrow107['totalmark'] <=54)) 	{$bukva7= "D"; 	$ball7 = "1.0";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=55) and  ($myrow107['totalmark'] <=59)) 	{$bukva7= "D+"; 	$ball7 = "1.33";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=60) and  ($myrow107['totalmark'] <=64)) 	{$bukva7= "C-";	$ball7 = "1.67";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=65) and  ($myrow107['totalmark'] <=69)) 	{$bukva7= "C"; 	$ball7 = "2.0";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=70) and  ($myrow107['totalmark'] <=74)) 	{$bukva7= "C+"; 	$ball7 = "2.33";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=75) and  ($myrow107['totalmark'] <=79)) 	{$bukva7= "B-";	$ball7 = "2.67";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=80) and  ($myrow107['totalmark'] <=84)) 	{$bukva7= "B"; 	$ball7 = "3.0";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=85) and  ($myrow107['totalmark'] <=89)) 	{$bukva7= "B+"; 	$ball7 = "3.33";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=90) and  ($myrow107['totalmark'] <=94)) 	{$bukva7= "A-";	$ball7 = "3.67";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=95) and  ($myrow107['totalmark'] <=100)) 	{$bukva7= "A"; 	$ball7 = "4.0";		$d7=$ball7*$c[7];}

$result108 = mysql_query ("select * from totalmarks2 where studygroupID=$a[8] and studentID=$myrow1[StudentID]"); 
$myrow108 = mysql_fetch_array($result108);

if (($myrow108['totalmark'] >=0) and    ($myrow108['totalmark'] <=49))   	{$bukva8= "F"; 	$ball8 = "0";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=50) and  ($myrow108['totalmark'] <=54)) 	{$bukva8= "D"; 	$ball8 = "1.0";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=55) and  ($myrow108['totalmark'] <=59)) 	{$bukva8= "D+"; 	$ball8 = "1.33";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=60) and  ($myrow108['totalmark'] <=64)) 	{$bukva8= "C-";	$ball8 = "1.67";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=65) and  ($myrow108['totalmark'] <=69)) 	{$bukva8= "C"; 	$ball8 = "2.0";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=70) and  ($myrow108['totalmark'] <=74)) 	{$bukva8= "C+"; 	$ball8 = "2.33";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=75) and  ($myrow108['totalmark'] <=79)) 	{$bukva8= "B-";	$ball8 = "2.67";		$d8=$ball8*$c[8];}	
if (($myrow108['totalmark'] >=80) and  ($myrow108['totalmark'] <=84)) 	{$bukva8= "B"; 	$ball8 = "3.0";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=85) and  ($myrow108['totalmark'] <=89)) 	{$bukva8= "B+"; 	$ball8 = "3.33";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=90) and  ($myrow108['totalmark'] <=94)) 	{$bukva8= "A-";	$ball8 = "3.67";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=95) and  ($myrow108['totalmark'] <=100)) 	{$bukva8= "A"; 	$ball8 = "4.0";		$d8=$ball8*$c[8];}

$result109 = mysql_query ("select * from totalmarks2 where studygroupID=$a[9] and studentID=$myrow1[StudentID]"); 
$myrow109 = mysql_fetch_array($result109);

if (($myrow109['totalmark'] >=0) and    ($myrow109['totalmark'] <=49))   	{$bukva9= "F"; 	$ball9 = "0";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=50) and  ($myrow109['totalmark'] <=54)) 	{$bukva9= "D"; 	$ball9 = "1.0";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=55) and  ($myrow109['totalmark'] <=59)) 	{$bukva9= "D+"; 	$ball9 = "1.33";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=60) and  ($myrow109['totalmark'] <=64)) 	{$bukva9= "C-";	$ball9 = "1.67";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=65) and  ($myrow109['totalmark'] <=69)) 	{$bukva9= "C"; 	$ball9 = "2.0";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=70) and  ($myrow109['totalmark'] <=74)) 	{$bukva9= "C+"; 	$ball9 = "2.33";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=75) and  ($myrow109['totalmark'] <=79)) 	{$bukva9= "B-";	$ball9 = "2.67";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=80) and  ($myrow109['totalmark'] <=84)) 	{$bukva9= "B"; 	$ball9 = "3.0";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=85) and  ($myrow109['totalmark'] <=89)) 	{$bukva9= "B+"; 	$ball9 = "3.33";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=90) and  ($myrow109['totalmark'] <=94)) 	{$bukva9= "A-";	$ball9 = "3.67";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=95) and  ($myrow109['totalmark'] <=100)) 	{$bukva9= "A"; 	$ball9 = "4.0";		$d9=$ball9*$c[9];}

//$result110 = mysql_query ("select * from totalmarks2 where studygroupID=$a[10] and studentID=$myrow1[StudentID]"); 
//$myrow110 = mysql_fetch_array($result110);

//if (($myrow110['totalmark'] >=0) and    ($myrow110['totalmark'] <=49))   	{$bukva10= "F"; 	$ball10 = "0";		$d10=$ball10*$c[10];}
//if (($myrow110['totalmark'] >=50) and  ($myrow110['totalmark'] <=54)) 	{$bukva10= "D"; 	$ball10 = "1.0";		$d10=$ball10*$c[10];}
//if (($myrow110['totalmark'] >=55) and  ($myrow110['totalmark'] <=59)) 	{$bukva10= "D+"; 	$ball10 = "1.33";	$d10=$ball10*$c[10];}
//if (($myrow110['totalmark'] >=60) and  ($myrow110['totalmark'] <=64)) 	{$bukva10= "C-";	$ball10 = "1.67";	$d10=$ball10*$c[10];}
//if (($myrow110['totalmark'] >=65) and  ($myrow110['totalmark'] <=69)) 	{$bukva10= "C"; 	$ball10 = "2.0";		$d10=$ball10*$c[10];}
//if (($myrow110['totalmark'] >=70) and  ($myrow110['totalmark'] <=74)) 	{$bukva10= "C+"; 	$ball10 = "2.33";	$d10=$ball10*$c[10];}
//if (($myrow110['totalmark'] >=75) and  ($myrow110['totalmark'] <=79)) 	{$bukva10= "B-";	$ball10 = "2.67";	$d10=$ball10*$c[10];}
//if (($myrow110['totalmark'] >=80) and  ($myrow110['totalmark'] <=84)) 	{$bukva10= "B"; 	$ball10 = "3.0";		$d10=$ball10*$c[10];}
//if (($myrow110['totalmark'] >=85) and  ($myrow110['totalmark'] <=89)) 	{$bukva10= "B+"; 	$ball10 = "3.33";	$d10=$ball10*$c[10];}
//if (($myrow110['totalmark'] >=90) and  ($myrow110['totalmark'] <=94)) 	{$bukva10= "A-";	$ball10 = "3.67";	$d10=$ball10*$c[10];}
//if (($myrow110['totalmark'] >=95) and  ($myrow110['totalmark'] <=100)) 	{$bukva10= "A"; 	$ball10 = "4.0";		$d10=$ball10*$c[10];}

//$result111 = mysql_query ("select * from totalmarks2 where studygroupID=$a[11] and studentID=$myrow1[StudentID]"); 
//$myrow111 = mysql_fetch_array($result111);

//if (($myrow111['totalmark'] >=0) and    ($myrow111['totalmark'] <=49))   	{$bukva11= "F"; 	$ball11 = "0";		$d11=$ball11*$c[11];}
//if (($myrow111['totalmark'] >=50) and  ($myrow111['totalmark'] <=54)) 	{$bukva11= "D"; 	$ball11 = "1.0";		$d11=$ball11*$c[11];}
//if (($myrow111['totalmark'] >=55) and  ($myrow111['totalmark'] <=59)) 	{$bukva11= "D+"; 	$ball11 = "1.33";	$d11=$ball11*$c[11];}
//if (($myrow111['totalmark'] >=60) and  ($myrow111['totalmark'] <=64)) 	{$bukva11= "C-";	$ball11 = "1.67";	$d11=$ball11*$c[11];}
//if (($myrow111['totalmark'] >=65) and  ($myrow111['totalmark'] <=69)) 	{$bukva11= "C"; 	$ball11 = "2.0";		$d11=$ball11*$c[11];}
//if (($myrow111['totalmark'] >=70) and  ($myrow111['totalmark'] <=74)) 	{$bukva11= "C+"; 	$ball11 = "2.33";	$d11=$ball11*$c[11];}
//if (($myrow111['totalmark'] >=75) and  ($myrow111['totalmark'] <=79)) 	{$bukva11= "B-";	$ball11 = "2.67";	$d11=$ball11*$c[11];}
//if (($myrow111['totalmark'] >=80) and  ($myrow111['totalmark'] <=84)) 	{$bukva11= "B"; 	$ball11 = "3.0";		$d11=$ball11*$c[11];}
//if (($myrow111['totalmark'] >=85) and  ($myrow111['totalmark'] <=89)) 	{$bukva11= "B+"; 	$ball11 = "3.33";	$d11=$ball11*$c[11];}
//if (($myrow111['totalmark'] >=90) and  ($myrow111['totalmark'] <=94)) 	{$bukva11= "A-";	$ball11 = "3.67";	$d11=$ball11*$c[11];}
//if (($myrow111['totalmark'] >=95) and  ($myrow111['totalmark'] <=100)) 	{$bukva11= "A"; 	$ball11 = "4.0";		$d11=$ball11*$c[11];}

$dd=$d1+$d2+$d3+$d4+$d5+$d6+$d7+$d8+$d9;
$gpa=round($dd/$credit,2);
$sum =round(($myrow101['totalmark'] + $myrow102['totalmark'] + $myrow103['totalmark'] + $myrow104['totalmark'] + $myrow105['totalmark'] + $myrow106['totalmark'] + $myrow107['totalmark'] + $myrow108['totalmark'] + $myrow109['totalmark'] + $myrow110['totalmark'] + $myrow111['totalmark'])/11);
printf("<tr><td>%s %s %s</td><td align=center> %s</td><td>$bukva1</td><td>$ball1</td><td align=center> %s</td><td>$bukva2</td><td>$ball2</td><td align=center> %s</td><td>$bukva3</td><td>$ball3</td><td align=center> %s</td><td>$bukva4</td><td>$ball4</td><td align=center> %s</td><td>$bukva5</td><td>$ball5</td><td align=center> %s</td><td>$bukva6</td><td>$ball6</td><td align=center> %s</td><td>$bukva7</td><td>$ball7</td><td align=center> %s</td><td>$bukva8</td><td>$ball8</td><td align=center> %s</td><td>$bukva9</td><td>$ball9</td><td >%s</td><td>$gpa</td></tr>",$myrow1['lastname'],$myrow1['firstname'],$myrow1['patronymic'],$myrow101['totalmark'],$myrow102['totalmark'],$myrow103['totalmark'],$myrow104['totalmark'],$myrow105['totalmark'],$myrow106['totalmark'],$myrow107['totalmark'],$myrow108['totalmark'],$myrow109['totalmark'],$sum);
}
while ($myrow1 = mysql_fetch_array($result1));




echo "</table>";
		}
	while ($myrow = mysql_fetch_array($result));















$result = mysql_query ("select * from groups where kurs=2 order by name");
$myrow = mysql_fetch_array($result);

	do
		{
$i=0;			
$credit=0;
echo "<h3>$myrow[name]</h3><table border=1>";
//
		$result2 = mysql_query ("select * from disved where groupID=$myrow[groupID] and sem=2 order by dis");
		$myrow2 = mysql_fetch_array($result2);
		echo "<tr><td align=center><b>ФИО студента</td>";
		do
		{
$i++;			
$a[$i]=$myrow2['ved'];
$c[$i]=$myrow2['credit'];
$credit=$credit+$myrow2['credit'];
echo "<td align=center colspan=3><b>$myrow2[dis] <br>$myrow2[ved]</td>";
		}
		while ($myrow2 = mysql_fetch_array($result2));
echo "<td><b>Итоговый</td><td align=center><b>GPA</td></tr>";
$result1 = mysql_query ("select * from students2 where groupID=$myrow[groupID]"); 
$myrow1 = mysql_fetch_array($result1);
do
{
$result101 = mysql_query ("select * from totalmarks2 where studygroupID=$a[1] and studentID=$myrow1[StudentID]"); 
$myrow101 = mysql_fetch_array($result101);

if (($myrow101['totalmark'] >=0) and    ($myrow101['totalmark'] <=49))   	{$bukva1= "F"; 	$ball1 = "0";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=50) and  ($myrow101['totalmark'] <=54)) 	{$bukva1= "D"; 	$ball1 = "1.0";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=55) and  ($myrow101['totalmark'] <=59)) 	{$bukva1= "D+"; 	$ball1 = "1.33";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=60) and  ($myrow101['totalmark'] <=64)) 	{$bukva1= "C-";	$ball1 = "1.67";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=65) and  ($myrow101['totalmark'] <=69)) 	{$bukva1= "C"; 	$ball1 = "2.0";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=70) and  ($myrow101['totalmark'] <=74)) 	{$bukva1= "C+"; 	$ball1 = "2.33";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=75) and  ($myrow101['totalmark'] <=79)) 	{$bukva1= "B-";	$ball1 = "2.67";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=80) and  ($myrow101['totalmark'] <=84)) 	{$bukva1= "B"; 	$ball1 = "3.0";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=85) and  ($myrow101['totalmark'] <=89)) 	{$bukva1= "B+"; 	$ball1 = "3.33";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=90) and  ($myrow101['totalmark'] <=94)) 	{$bukva1= "A-";	$ball1 = "3.67";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=95) and  ($myrow101['totalmark'] <=100)) 	{$bukva1= "A"; 	$ball1 = "4.0";		$d1=$ball1*$c[1];}

$result102 = mysql_query ("select * from totalmarks2 where studygroupID=$a[2] and studentID=$myrow1[StudentID]"); 
$myrow102 = mysql_fetch_array($result102);

if (($myrow102['totalmark'] >=0) and    ($myrow102['totalmark'] <=49))   	{$bukva2= "F"; 	$ball2 = "0";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=50) and  ($myrow102['totalmark'] <=54)) 	{$bukva2= "D"; 	$ball2 = "1.0";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=55) and  ($myrow102['totalmark'] <=59)) 	{$bukva2= "D+"; 	$ball2 = "1.33";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=60) and  ($myrow102['totalmark'] <=64)) 	{$bukva2= "C-";	$ball2 = "1.67";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=65) and  ($myrow102['totalmark'] <=69)) 	{$bukva2= "C"; 	$ball2 = "2.0";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=70) and  ($myrow102['totalmark'] <=74)) 	{$bukva2= "C+"; 	$ball2 = "2.33";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=75) and  ($myrow102['totalmark'] <=79)) 	{$bukva2= "B-";	$ball2 = "2.67";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=80) and  ($myrow102['totalmark'] <=84)) 	{$bukva2= "B"; 	$ball2 = "3.0";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=85) and  ($myrow102['totalmark'] <=89)) 	{$bukva2= "B+"; 	$ball2 = "3.33";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=90) and  ($myrow102['totalmark'] <=94)) 	{$bukva2= "A-";	$ball2 = "3.67";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=95) and  ($myrow102['totalmark'] <=100)) 	{$bukva2= "A"; 	$ball2 = "4.0";		$d2=$ball2*$c[2];}

$result103 = mysql_query ("select * from totalmarks2 where studygroupID=$a[3] and studentID=$myrow1[StudentID]"); 
$myrow103 = mysql_fetch_array($result103);

if (($myrow103['totalmark'] >=0) and    ($myrow103['totalmark'] <=49))   	{$bukva3= "F"; 	$ball3 = "0";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=50) and  ($myrow103['totalmark'] <=54)) 	{$bukva3= "D"; 	$ball3 = "1.0";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=55) and  ($myrow103['totalmark'] <=59)) 	{$bukva3= "D+"; 	$ball3 = "1.33";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=60) and  ($myrow103['totalmark'] <=64)) 	{$bukva3= "C-";	$ball3 = "1.67";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=65) and  ($myrow103['totalmark'] <=69)) 	{$bukva3= "C"; 	$ball3 = "2.0";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=70) and  ($myrow103['totalmark'] <=74)) 	{$bukva3= "C+"; 	$ball3 = "2.33";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=75) and  ($myrow103['totalmark'] <=79)) 	{$bukva3= "B-";	$ball3 = "2.67";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=80) and  ($myrow103['totalmark'] <=84)) 	{$bukva3= "B"; 	$ball3 = "3.0";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=85) and  ($myrow103['totalmark'] <=89)) 	{$bukva3= "B+"; 	$ball3 = "3.33";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=90) and  ($myrow103['totalmark'] <=94)) 	{$bukva3= "A-";	$ball3 = "3.67";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=95) and  ($myrow103['totalmark'] <=100)) 	{$bukva3= "A"; 	$ball3 = "4.0";		$d3=$ball3*$c[3];}

$result104 = mysql_query ("select * from totalmarks2 where studygroupID=$a[4] and studentID=$myrow1[StudentID]"); 
$myrow104 = mysql_fetch_array($result104);

if (($myrow104['totalmark'] >=0) and    ($myrow104['totalmark'] <=49))   	{$bukva4= "F"; 	$ball4 = "0";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=50) and  ($myrow104['totalmark'] <=54)) 	{$bukva4= "D"; 	$ball4 = "1.0";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=55) and  ($myrow104['totalmark'] <=59)) 	{$bukva4= "D+"; 	$ball4 = "1.33";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=60) and  ($myrow104['totalmark'] <=64)) 	{$bukva4= "C-";	$ball4 = "1.67";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=65) and  ($myrow104['totalmark'] <=69)) 	{$bukva4= "C"; 	$ball4 = "2.0";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=70) and  ($myrow104['totalmark'] <=74)) 	{$bukva4= "C+"; 	$ball4 = "2.33";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=75) and  ($myrow104['totalmark'] <=79)) 	{$bukva4= "B-";	$ball4 = "2.67";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=80) and  ($myrow104['totalmark'] <=84)) 	{$bukva4= "B"; 	$ball4 = "3.0";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=85) and  ($myrow104['totalmark'] <=89)) 	{$bukva4= "B+"; 	$ball4 = "3.33";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=90) and  ($myrow104['totalmark'] <=94)) 	{$bukva4= "A-";	$ball4 = "3.67";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=95) and  ($myrow104['totalmark'] <=100)) 	{$bukva4= "A"; 	$ball4 = "4.0";		$d4=$ball4*$c[4];}

$result105 = mysql_query ("select * from totalmarks2 where studygroupID=$a[5] and studentID=$myrow1[StudentID]"); 
$myrow105 = mysql_fetch_array($result105);

if (($myrow105['totalmark'] >=0) and    ($myrow105['totalmark'] <=49))   	{$bukva5= "F"; 	$ball5 = "0";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=50) and  ($myrow105['totalmark'] <=54)) 	{$bukva5= "D"; 	$ball5 = "1.0";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=55) and  ($myrow105['totalmark'] <=59)) 	{$bukva5= "D+"; 	$ball5 = "1.33";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=60) and  ($myrow105['totalmark'] <=64)) 	{$bukva5= "C-";	$ball5 = "1.67";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=65) and  ($myrow105['totalmark'] <=69)) 	{$bukva5= "C"; 	$ball5 = "2.0";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=70) and  ($myrow105['totalmark'] <=74)) 	{$bukva5= "C+"; 	$ball5 = "2.33";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=75) and  ($myrow105['totalmark'] <=79)) 	{$bukva5= "B-";	$ball5 = "2.67";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=80) and  ($myrow105['totalmark'] <=84)) 	{$bukva5= "B"; 	$ball5 = "3.0";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=85) and  ($myrow105['totalmark'] <=89)) 	{$bukva5= "B+"; 	$ball5 = "3.33";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=90) and  ($myrow105['totalmark'] <=94)) 	{$bukva5= "A-";	$ball5 = "3.67";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=95) and  ($myrow105['totalmark'] <=100)) 	{$bukva5= "A"; 	$ball5 = "4.0";		$d5=$ball5*$c[5];}

$result106 = mysql_query ("select * from totalmarks2 where studygroupID=$a[6] and studentID=$myrow1[StudentID]"); 
$myrow106 = mysql_fetch_array($result106);

if (($myrow106['totalmark'] >=0) and    ($myrow106['totalmark'] <=49))   	{$bukva6= "F"; 	$ball6 = "0";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=50) and  ($myrow106['totalmark'] <=54)) 	{$bukva6= "D"; 	$ball6 = "1.0";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=55) and  ($myrow106['totalmark'] <=59)) 	{$bukva6= "D+"; 	$ball6 = "1.33";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=60) and  ($myrow106['totalmark'] <=64)) 	{$bukva6= "C-";	$ball6 = "1.67";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=65) and  ($myrow106['totalmark'] <=69)) 	{$bukva6= "C"; 	$ball6 = "2.0";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=70) and  ($myrow106['totalmark'] <=74)) 	{$bukva6= "C+"; 	$ball6 = "2.33";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=75) and  ($myrow106['totalmark'] <=79)) 	{$bukva6= "B-";	$ball6 = "2.67";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=80) and  ($myrow106['totalmark'] <=84)) 	{$bukva6= "B"; 	$ball6 = "3.0";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=85) and  ($myrow106['totalmark'] <=89)) 	{$bukva6= "B+"; 	$ball6 = "3.33";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=90) and  ($myrow106['totalmark'] <=94)) 	{$bukva6= "A-";	$ball6 = "3.67";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=95) and  ($myrow106['totalmark'] <=100)) 	{$bukva6= "A"; 	$ball6 = "4.0";		$d6=$ball6*$c[6];}

$result107 = mysql_query ("select * from totalmarks2 where studygroupID=$a[7] and studentID=$myrow1[StudentID]"); 
$myrow107 = mysql_fetch_array($result107);

if (($myrow107['totalmark'] >=0) and    ($myrow107['totalmark'] <=49))   	{$bukva7= "F"; 	$ball7 = "0";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=50) and  ($myrow107['totalmark'] <=54)) 	{$bukva7= "D"; 	$ball7 = "1.0";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=55) and  ($myrow107['totalmark'] <=59)) 	{$bukva7= "D+"; 	$ball7 = "1.33";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=60) and  ($myrow107['totalmark'] <=64)) 	{$bukva7= "C-";	$ball7 = "1.67";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=65) and  ($myrow107['totalmark'] <=69)) 	{$bukva7= "C"; 	$ball7 = "2.0";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=70) and  ($myrow107['totalmark'] <=74)) 	{$bukva7= "C+"; 	$ball7 = "2.33";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=75) and  ($myrow107['totalmark'] <=79)) 	{$bukva7= "B-";	$ball7 = "2.67";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=80) and  ($myrow107['totalmark'] <=84)) 	{$bukva7= "B"; 	$ball7 = "3.0";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=85) and  ($myrow107['totalmark'] <=89)) 	{$bukva7= "B+"; 	$ball7 = "3.33";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=90) and  ($myrow107['totalmark'] <=94)) 	{$bukva7= "A-";	$ball7 = "3.67";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=95) and  ($myrow107['totalmark'] <=100)) 	{$bukva7= "A"; 	$ball7 = "4.0";		$d7=$ball7*$c[7];}

$result108 = mysql_query ("select * from totalmarks2 where studygroupID=$a[8] and studentID=$myrow1[StudentID]"); 
$myrow108 = mysql_fetch_array($result108);

if (($myrow108['totalmark'] >=0) and    ($myrow108['totalmark'] <=49))   	{$bukva8= "F"; 	$ball8 = "0";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=50) and  ($myrow108['totalmark'] <=54)) 	{$bukva8= "D"; 	$ball8 = "1.0";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=55) and  ($myrow108['totalmark'] <=59)) 	{$bukva8= "D+"; 	$ball8 = "1.33";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=60) and  ($myrow108['totalmark'] <=64)) 	{$bukva8= "C-";	$ball8 = "1.67";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=65) and  ($myrow108['totalmark'] <=69)) 	{$bukva8= "C"; 	$ball8 = "2.0";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=70) and  ($myrow108['totalmark'] <=74)) 	{$bukva8= "C+"; 	$ball8 = "2.33";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=75) and  ($myrow108['totalmark'] <=79)) 	{$bukva8= "B-";	$ball8 = "2.67";		$d8=$ball8*$c[8];}	
if (($myrow108['totalmark'] >=80) and  ($myrow108['totalmark'] <=84)) 	{$bukva8= "B"; 	$ball8 = "3.0";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=85) and  ($myrow108['totalmark'] <=89)) 	{$bukva8= "B+"; 	$ball8 = "3.33";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=90) and  ($myrow108['totalmark'] <=94)) 	{$bukva8= "A-";	$ball8 = "3.67";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=95) and  ($myrow108['totalmark'] <=100)) 	{$bukva8= "A"; 	$ball8 = "4.0";		$d8=$ball8*$c[8];}

$result109 = mysql_query ("select * from totalmarks2 where studygroupID=$a[9] and studentID=$myrow1[StudentID]"); 
$myrow109 = mysql_fetch_array($result109);

if (($myrow109['totalmark'] >=0) and    ($myrow109['totalmark'] <=49))   	{$bukva9= "F"; 	$ball9 = "0";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=50) and  ($myrow109['totalmark'] <=54)) 	{$bukva9= "D"; 	$ball9 = "1.0";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=55) and  ($myrow109['totalmark'] <=59)) 	{$bukva9= "D+"; 	$ball9 = "1.33";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=60) and  ($myrow109['totalmark'] <=64)) 	{$bukva9= "C-";	$ball9 = "1.67";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=65) and  ($myrow109['totalmark'] <=69)) 	{$bukva9= "C"; 	$ball9 = "2.0";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=70) and  ($myrow109['totalmark'] <=74)) 	{$bukva9= "C+"; 	$ball9 = "2.33";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=75) and  ($myrow109['totalmark'] <=79)) 	{$bukva9= "B-";	$ball9 = "2.67";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=80) and  ($myrow109['totalmark'] <=84)) 	{$bukva9= "B"; 	$ball9 = "3.0";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=85) and  ($myrow109['totalmark'] <=89)) 	{$bukva9= "B+"; 	$ball9 = "3.33";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=90) and  ($myrow109['totalmark'] <=94)) 	{$bukva9= "A-";	$ball9 = "3.67";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=95) and  ($myrow109['totalmark'] <=100)) 	{$bukva9= "A"; 	$ball9 = "4.0";		$d9=$ball9*$c[9];}

$result110 = mysql_query ("select * from totalmarks2 where studygroupID=$a[10] and studentID=$myrow1[StudentID]"); 
$myrow110 = mysql_fetch_array($result110);

if (($myrow110['totalmark'] >=0) and    ($myrow110['totalmark'] <=49))   	{$bukva10= "F"; 	$ball10 = "0";		$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=50) and  ($myrow110['totalmark'] <=54)) 	{$bukva10= "D"; 	$ball10 = "1.0";		$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=55) and  ($myrow110['totalmark'] <=59)) 	{$bukva10= "D+"; 	$ball10 = "1.33";	$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=60) and  ($myrow110['totalmark'] <=64)) 	{$bukva10= "C-";	$ball10 = "1.67";	$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=65) and  ($myrow110['totalmark'] <=69)) 	{$bukva10= "C"; 	$ball10 = "2.0";		$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=70) and  ($myrow110['totalmark'] <=74)) 	{$bukva10= "C+"; 	$ball10 = "2.33";	$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=75) and  ($myrow110['totalmark'] <=79)) 	{$bukva10= "B-";	$ball10 = "2.67";	$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=80) and  ($myrow110['totalmark'] <=84)) 	{$bukva10= "B"; 	$ball10 = "3.0";		$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=85) and  ($myrow110['totalmark'] <=89)) 	{$bukva10= "B+"; 	$ball10 = "3.33";	$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=90) and  ($myrow110['totalmark'] <=94)) 	{$bukva10= "A-";	$ball10 = "3.67";	$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=95) and  ($myrow110['totalmark'] <=100)) 	{$bukva10= "A"; 	$ball10 = "4.0";		$d10=$ball10*$c[10];}

$result111 = mysql_query ("select * from totalmarks2 where studygroupID=$a[11] and studentID=$myrow1[StudentID]"); 
$myrow111 = mysql_fetch_array($result111);

if (($myrow111['totalmark'] >=0) and    ($myrow111['totalmark'] <=49))   	{$bukva11= "F"; 	$ball11 = "0";		$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=50) and  ($myrow111['totalmark'] <=54)) 	{$bukva11= "D"; 	$ball11 = "1.0";		$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=55) and  ($myrow111['totalmark'] <=59)) 	{$bukva11= "D+"; 	$ball11 = "1.33";	$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=60) and  ($myrow111['totalmark'] <=64)) 	{$bukva11= "C-";	$ball11 = "1.67";	$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=65) and  ($myrow111['totalmark'] <=69)) 	{$bukva11= "C"; 	$ball11 = "2.0";		$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=70) and  ($myrow111['totalmark'] <=74)) 	{$bukva11= "C+"; 	$ball11 = "2.33";	$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=75) and  ($myrow111['totalmark'] <=79)) 	{$bukva11= "B-";	$ball11 = "2.67";	$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=80) and  ($myrow111['totalmark'] <=84)) 	{$bukva11= "B"; 	$ball11 = "3.0";		$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=85) and  ($myrow111['totalmark'] <=89)) 	{$bukva11= "B+"; 	$ball11 = "3.33";	$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=90) and  ($myrow111['totalmark'] <=94)) 	{$bukva11= "A-";	$ball11 = "3.67";	$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=95) and  ($myrow111['totalmark'] <=100)) 	{$bukva11= "A"; 	$ball11 = "4.0";		$d11=$ball11*$c[11];}

$dd=$d1+$d2+$d3+$d4+$d5+$d6+$d7+$d8+$d9+$d10+$d11;
$gpa=round($dd/$credit,2);
$sum =round(($myrow101['totalmark'] + $myrow102['totalmark'] + $myrow103['totalmark'] + $myrow104['totalmark'] + $myrow105['totalmark'] + $myrow106['totalmark'] + $myrow107['totalmark'] + $myrow108['totalmark'] + $myrow109['totalmark'] + $myrow110['totalmark'] + $myrow111['totalmark'])/11);
printf("<tr><td>%s %s %s</td><td align=center> %s</td><td>$bukva1</td><td>$ball1</td><td align=center> %s</td><td>$bukva2</td><td>$ball2</td><td align=center> %s</td><td>$bukva3</td><td>$ball3</td><td align=center> %s</td><td>$bukva4</td><td>$ball4</td><td align=center> %s</td><td>$bukva5</td><td>$ball5</td><td align=center> %s</td><td>$bukva6</td><td>$ball6</td><td align=center> %s</td><td>$bukva7</td><td>$ball7</td><td align=center> %s</td><td>$bukva8</td><td>$ball8</td><td align=center> %s</td><td>$bukva9</td><td>$ball9</td><td align=center> %s</td><td>$bukva10</td><td>$ball10</td><td  align=center> %s</td><td>$bukva11</td><td>$ball11</td><td >%s</td><td>$gpa</td></tr>",$myrow1['lastname'],$myrow1['firstname'],$myrow1['patronymic'],$myrow101['totalmark'],$myrow102['totalmark'],$myrow103['totalmark'],$myrow104['totalmark'],$myrow105['totalmark'],$myrow106['totalmark'],$myrow107['totalmark'],$myrow108['totalmark'],$myrow109['totalmark'],$myrow110['totalmark'],$myrow111['totalmark'],$sum);
}
while ($myrow1 = mysql_fetch_array($result1));




echo "</table>";
		}
	while ($myrow = mysql_fetch_array($result));









$result = mysql_query ("select * from groups where kurs=3 order by name");
$myrow = mysql_fetch_array($result);

	do
		{
$i=0;			
echo "<h3>$myrow[name]</h3><table border=1>";
//
		$result2 = mysql_query ("select * from disved where groupID=$myrow[groupID] and sem=2 order by dis");
		$myrow2 = mysql_fetch_array($result2);
		echo "<tr><td align=center><b>ФИО студента</td>";
		do
		{
$i++;			
$a[$i]=$myrow2['ved'];
echo "<td align=center colspan=3><b>$myrow2[dis] <br>$myrow2[ved]</td>";
		}
		while ($myrow2 = mysql_fetch_array($result2));
echo "<td><b>Итоговый</td><td align=center><b>GPA</td></tr>";
$result1 = mysql_query ("select * from students2 where groupID=$myrow[groupID]"); 
$myrow1 = mysql_fetch_array($result1);
do
{
$result101 = mysql_query ("select * from totalmarks2 where studygroupID=$a[1] and studentID=$myrow1[StudentID]"); 
$myrow101 = mysql_fetch_array($result101);

if (($myrow101['totalmark'] >=0) and    ($myrow101['totalmark'] <=49))   	{$bukva1= "F"; 	$ball1 = "0";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=50) and  ($myrow101['totalmark'] <=54)) 	{$bukva1= "D"; 	$ball1 = "1.0";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=55) and  ($myrow101['totalmark'] <=59)) 	{$bukva1= "D+"; 	$ball1 = "1.33";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=60) and  ($myrow101['totalmark'] <=64)) 	{$bukva1= "C-";	$ball1 = "1.67";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=65) and  ($myrow101['totalmark'] <=69)) 	{$bukva1= "C"; 	$ball1 = "2.0";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=70) and  ($myrow101['totalmark'] <=74)) 	{$bukva1= "C+"; 	$ball1 = "2.33";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=75) and  ($myrow101['totalmark'] <=79)) 	{$bukva1= "B-";	$ball1 = "2.67";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=80) and  ($myrow101['totalmark'] <=84)) 	{$bukva1= "B"; 	$ball1 = "3.0";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=85) and  ($myrow101['totalmark'] <=89)) 	{$bukva1= "B+"; 	$ball1 = "3.33";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=90) and  ($myrow101['totalmark'] <=94)) 	{$bukva1= "A-";	$ball1 = "3.67";		$d1=$ball1*$c[1];}
if (($myrow101['totalmark'] >=95) and  ($myrow101['totalmark'] <=100)) 	{$bukva1= "A"; 	$ball1 = "4.0";		$d1=$ball1*$c[1];}

$result102 = mysql_query ("select * from totalmarks2 where studygroupID=$a[2] and studentID=$myrow1[StudentID]"); 
$myrow102 = mysql_fetch_array($result102);

if (($myrow102['totalmark'] >=0) and    ($myrow102['totalmark'] <=49))   	{$bukva2= "F"; 	$ball2 = "0";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=50) and  ($myrow102['totalmark'] <=54)) 	{$bukva2= "D"; 	$ball2 = "1.0";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=55) and  ($myrow102['totalmark'] <=59)) 	{$bukva2= "D+"; 	$ball2 = "1.33";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=60) and  ($myrow102['totalmark'] <=64)) 	{$bukva2= "C-";	$ball2 = "1.67";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=65) and  ($myrow102['totalmark'] <=69)) 	{$bukva2= "C"; 	$ball2 = "2.0";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=70) and  ($myrow102['totalmark'] <=74)) 	{$bukva2= "C+"; 	$ball2 = "2.33";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=75) and  ($myrow102['totalmark'] <=79)) 	{$bukva2= "B-";	$ball2 = "2.67";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=80) and  ($myrow102['totalmark'] <=84)) 	{$bukva2= "B"; 	$ball2 = "3.0";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=85) and  ($myrow102['totalmark'] <=89)) 	{$bukva2= "B+"; 	$ball2 = "3.33";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=90) and  ($myrow102['totalmark'] <=94)) 	{$bukva2= "A-";	$ball2 = "3.67";		$d2=$ball2*$c[2];}
if (($myrow102['totalmark'] >=95) and  ($myrow102['totalmark'] <=100)) 	{$bukva2= "A"; 	$ball2 = "4.0";		$d2=$ball2*$c[2];}

$result103 = mysql_query ("select * from totalmarks2 where studygroupID=$a[3] and studentID=$myrow1[StudentID]"); 
$myrow103 = mysql_fetch_array($result103);

if (($myrow103['totalmark'] >=0) and    ($myrow103['totalmark'] <=49))   	{$bukva3= "F"; 	$ball3 = "0";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=50) and  ($myrow103['totalmark'] <=54)) 	{$bukva3= "D"; 	$ball3 = "1.0";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=55) and  ($myrow103['totalmark'] <=59)) 	{$bukva3= "D+"; 	$ball3 = "1.33";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=60) and  ($myrow103['totalmark'] <=64)) 	{$bukva3= "C-";	$ball3 = "1.67";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=65) and  ($myrow103['totalmark'] <=69)) 	{$bukva3= "C"; 	$ball3 = "2.0";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=70) and  ($myrow103['totalmark'] <=74)) 	{$bukva3= "C+"; 	$ball3 = "2.33";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=75) and  ($myrow103['totalmark'] <=79)) 	{$bukva3= "B-";	$ball3 = "2.67";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=80) and  ($myrow103['totalmark'] <=84)) 	{$bukva3= "B"; 	$ball3 = "3.0";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=85) and  ($myrow103['totalmark'] <=89)) 	{$bukva3= "B+"; 	$ball3 = "3.33";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=90) and  ($myrow103['totalmark'] <=94)) 	{$bukva3= "A-";	$ball3 = "3.67";		$d3=$ball3*$c[3];}
if (($myrow103['totalmark'] >=95) and  ($myrow103['totalmark'] <=100)) 	{$bukva3= "A"; 	$ball3 = "4.0";		$d3=$ball3*$c[3];}

$result104 = mysql_query ("select * from totalmarks2 where studygroupID=$a[4] and studentID=$myrow1[StudentID]"); 
$myrow104 = mysql_fetch_array($result104);

if (($myrow104['totalmark'] >=0) and    ($myrow104['totalmark'] <=49))   	{$bukva4= "F"; 	$ball4 = "0";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=50) and  ($myrow104['totalmark'] <=54)) 	{$bukva4= "D"; 	$ball4 = "1.0";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=55) and  ($myrow104['totalmark'] <=59)) 	{$bukva4= "D+"; 	$ball4 = "1.33";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=60) and  ($myrow104['totalmark'] <=64)) 	{$bukva4= "C-";	$ball4 = "1.67";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=65) and  ($myrow104['totalmark'] <=69)) 	{$bukva4= "C"; 	$ball4 = "2.0";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=70) and  ($myrow104['totalmark'] <=74)) 	{$bukva4= "C+"; 	$ball4 = "2.33";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=75) and  ($myrow104['totalmark'] <=79)) 	{$bukva4= "B-";	$ball4 = "2.67";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=80) and  ($myrow104['totalmark'] <=84)) 	{$bukva4= "B"; 	$ball4 = "3.0";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=85) and  ($myrow104['totalmark'] <=89)) 	{$bukva4= "B+"; 	$ball4 = "3.33";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=90) and  ($myrow104['totalmark'] <=94)) 	{$bukva4= "A-";	$ball4 = "3.67";		$d4=$ball4*$c[4];}
if (($myrow104['totalmark'] >=95) and  ($myrow104['totalmark'] <=100)) 	{$bukva4= "A"; 	$ball4 = "4.0";		$d4=$ball4*$c[4];}

$result105 = mysql_query ("select * from totalmarks2 where studygroupID=$a[5] and studentID=$myrow1[StudentID]"); 
$myrow105 = mysql_fetch_array($result105);

if (($myrow105['totalmark'] >=0) and    ($myrow105['totalmark'] <=49))   	{$bukva5= "F"; 	$ball5 = "0";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=50) and  ($myrow105['totalmark'] <=54)) 	{$bukva5= "D"; 	$ball5 = "1.0";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=55) and  ($myrow105['totalmark'] <=59)) 	{$bukva5= "D+"; 	$ball5 = "1.33";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=60) and  ($myrow105['totalmark'] <=64)) 	{$bukva5= "C-";	$ball5 = "1.67";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=65) and  ($myrow105['totalmark'] <=69)) 	{$bukva5= "C"; 	$ball5 = "2.0";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=70) and  ($myrow105['totalmark'] <=74)) 	{$bukva5= "C+"; 	$ball5 = "2.33";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=75) and  ($myrow105['totalmark'] <=79)) 	{$bukva5= "B-";	$ball5 = "2.67";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=80) and  ($myrow105['totalmark'] <=84)) 	{$bukva5= "B"; 	$ball5 = "3.0";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=85) and  ($myrow105['totalmark'] <=89)) 	{$bukva5= "B+"; 	$ball5 = "3.33";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=90) and  ($myrow105['totalmark'] <=94)) 	{$bukva5= "A-";	$ball5 = "3.67";		$d5=$ball5*$c[5];}
if (($myrow105['totalmark'] >=95) and  ($myrow105['totalmark'] <=100)) 	{$bukva5= "A"; 	$ball5 = "4.0";		$d5=$ball5*$c[5];}

$result106 = mysql_query ("select * from totalmarks2 where studygroupID=$a[6] and studentID=$myrow1[StudentID]"); 
$myrow106 = mysql_fetch_array($result106);

if (($myrow106['totalmark'] >=0) and    ($myrow106['totalmark'] <=49))   	{$bukva6= "F"; 	$ball6 = "0";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=50) and  ($myrow106['totalmark'] <=54)) 	{$bukva6= "D"; 	$ball6 = "1.0";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=55) and  ($myrow106['totalmark'] <=59)) 	{$bukva6= "D+"; 	$ball6 = "1.33";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=60) and  ($myrow106['totalmark'] <=64)) 	{$bukva6= "C-";	$ball6 = "1.67";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=65) and  ($myrow106['totalmark'] <=69)) 	{$bukva6= "C"; 	$ball6 = "2.0";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=70) and  ($myrow106['totalmark'] <=74)) 	{$bukva6= "C+"; 	$ball6 = "2.33";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=75) and  ($myrow106['totalmark'] <=79)) 	{$bukva6= "B-";	$ball6 = "2.67";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=80) and  ($myrow106['totalmark'] <=84)) 	{$bukva6= "B"; 	$ball6 = "3.0";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=85) and  ($myrow106['totalmark'] <=89)) 	{$bukva6= "B+"; 	$ball6 = "3.33";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=90) and  ($myrow106['totalmark'] <=94)) 	{$bukva6= "A-";	$ball6 = "3.67";		$d6=$ball6*$c[6];}
if (($myrow106['totalmark'] >=95) and  ($myrow106['totalmark'] <=100)) 	{$bukva6= "A"; 	$ball6 = "4.0";		$d6=$ball6*$c[6];}

$result107 = mysql_query ("select * from totalmarks2 where studygroupID=$a[7] and studentID=$myrow1[StudentID]"); 
$myrow107 = mysql_fetch_array($result107);

if (($myrow107['totalmark'] >=0) and    ($myrow107['totalmark'] <=49))   	{$bukva7= "F"; 	$ball7 = "0";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=50) and  ($myrow107['totalmark'] <=54)) 	{$bukva7= "D"; 	$ball7 = "1.0";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=55) and  ($myrow107['totalmark'] <=59)) 	{$bukva7= "D+"; 	$ball7 = "1.33";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=60) and  ($myrow107['totalmark'] <=64)) 	{$bukva7= "C-";	$ball7 = "1.67";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=65) and  ($myrow107['totalmark'] <=69)) 	{$bukva7= "C"; 	$ball7 = "2.0";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=70) and  ($myrow107['totalmark'] <=74)) 	{$bukva7= "C+"; 	$ball7 = "2.33";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=75) and  ($myrow107['totalmark'] <=79)) 	{$bukva7= "B-";	$ball7 = "2.67";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=80) and  ($myrow107['totalmark'] <=84)) 	{$bukva7= "B"; 	$ball7 = "3.0";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=85) and  ($myrow107['totalmark'] <=89)) 	{$bukva7= "B+"; 	$ball7 = "3.33";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=90) and  ($myrow107['totalmark'] <=94)) 	{$bukva7= "A-";	$ball7 = "3.67";		$d7=$ball7*$c[7];}
if (($myrow107['totalmark'] >=95) and  ($myrow107['totalmark'] <=100)) 	{$bukva7= "A"; 	$ball7 = "4.0";		$d7=$ball7*$c[7];}

$result108 = mysql_query ("select * from totalmarks2 where studygroupID=$a[8] and studentID=$myrow1[StudentID]"); 
$myrow108 = mysql_fetch_array($result108);

if (($myrow108['totalmark'] >=0) and    ($myrow108['totalmark'] <=49))   	{$bukva8= "F"; 	$ball8 = "0";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=50) and  ($myrow108['totalmark'] <=54)) 	{$bukva8= "D"; 	$ball8 = "1.0";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=55) and  ($myrow108['totalmark'] <=59)) 	{$bukva8= "D+"; 	$ball8 = "1.33";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=60) and  ($myrow108['totalmark'] <=64)) 	{$bukva8= "C-";	$ball8 = "1.67";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=65) and  ($myrow108['totalmark'] <=69)) 	{$bukva8= "C"; 	$ball8 = "2.0";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=70) and  ($myrow108['totalmark'] <=74)) 	{$bukva8= "C+"; 	$ball8 = "2.33";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=75) and  ($myrow108['totalmark'] <=79)) 	{$bukva8= "B-";	$ball8 = "2.67";		$d8=$ball8*$c[8];}	
if (($myrow108['totalmark'] >=80) and  ($myrow108['totalmark'] <=84)) 	{$bukva8= "B"; 	$ball8 = "3.0";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=85) and  ($myrow108['totalmark'] <=89)) 	{$bukva8= "B+"; 	$ball8 = "3.33";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=90) and  ($myrow108['totalmark'] <=94)) 	{$bukva8= "A-";	$ball8 = "3.67";		$d8=$ball8*$c[8];}
if (($myrow108['totalmark'] >=95) and  ($myrow108['totalmark'] <=100)) 	{$bukva8= "A"; 	$ball8 = "4.0";		$d8=$ball8*$c[8];}

$result109 = mysql_query ("select * from totalmarks2 where studygroupID=$a[9] and studentID=$myrow1[StudentID]"); 
$myrow109 = mysql_fetch_array($result109);

if (($myrow109['totalmark'] >=0) and    ($myrow109['totalmark'] <=49))   	{$bukva9= "F"; 	$ball9 = "0";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=50) and  ($myrow109['totalmark'] <=54)) 	{$bukva9= "D"; 	$ball9 = "1.0";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=55) and  ($myrow109['totalmark'] <=59)) 	{$bukva9= "D+"; 	$ball9 = "1.33";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=60) and  ($myrow109['totalmark'] <=64)) 	{$bukva9= "C-";	$ball9 = "1.67";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=65) and  ($myrow109['totalmark'] <=69)) 	{$bukva9= "C"; 	$ball9 = "2.0";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=70) and  ($myrow109['totalmark'] <=74)) 	{$bukva9= "C+"; 	$ball9 = "2.33";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=75) and  ($myrow109['totalmark'] <=79)) 	{$bukva9= "B-";	$ball9 = "2.67";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=80) and  ($myrow109['totalmark'] <=84)) 	{$bukva9= "B"; 	$ball9 = "3.0";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=85) and  ($myrow109['totalmark'] <=89)) 	{$bukva9= "B+"; 	$ball9 = "3.33";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=90) and  ($myrow109['totalmark'] <=94)) 	{$bukva9= "A-";	$ball9 = "3.67";		$d9=$ball9*$c[9];}
if (($myrow109['totalmark'] >=95) and  ($myrow109['totalmark'] <=100)) 	{$bukva9= "A"; 	$ball9 = "4.0";		$d9=$ball9*$c[9];}

$result110 = mysql_query ("select * from totalmarks2 where studygroupID=$a[10] and studentID=$myrow1[StudentID]"); 
$myrow110 = mysql_fetch_array($result110);

if (($myrow110['totalmark'] >=0) and    ($myrow110['totalmark'] <=49))   	{$bukva10= "F"; 	$ball10 = "0";		$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=50) and  ($myrow110['totalmark'] <=54)) 	{$bukva10= "D"; 	$ball10 = "1.0";		$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=55) and  ($myrow110['totalmark'] <=59)) 	{$bukva10= "D+"; 	$ball10 = "1.33";	$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=60) and  ($myrow110['totalmark'] <=64)) 	{$bukva10= "C-";	$ball10 = "1.67";	$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=65) and  ($myrow110['totalmark'] <=69)) 	{$bukva10= "C"; 	$ball10 = "2.0";		$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=70) and  ($myrow110['totalmark'] <=74)) 	{$bukva10= "C+"; 	$ball10 = "2.33";	$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=75) and  ($myrow110['totalmark'] <=79)) 	{$bukva10= "B-";	$ball10 = "2.67";	$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=80) and  ($myrow110['totalmark'] <=84)) 	{$bukva10= "B"; 	$ball10 = "3.0";		$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=85) and  ($myrow110['totalmark'] <=89)) 	{$bukva10= "B+"; 	$ball10 = "3.33";	$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=90) and  ($myrow110['totalmark'] <=94)) 	{$bukva10= "A-";	$ball10 = "3.67";	$d10=$ball10*$c[10];}
if (($myrow110['totalmark'] >=95) and  ($myrow110['totalmark'] <=100)) 	{$bukva10= "A"; 	$ball10 = "4.0";		$d10=$ball10*$c[10];}

$result111 = mysql_query ("select * from totalmarks2 where studygroupID=$a[11] and studentID=$myrow1[StudentID]"); 
$myrow111 = mysql_fetch_array($result111);


if (($myrow111['totalmark'] >=0) and    ($myrow111['totalmark'] <=49))   	{$bukva11= "F"; 	$ball11 = "0";		$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=50) and  ($myrow111['totalmark'] <=54)) 	{$bukva11= "D"; 	$ball11 = "1.0";		$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=55) and  ($myrow111['totalmark'] <=59)) 	{$bukva11= "D+"; 	$ball11 = "1.33";	$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=60) and  ($myrow111['totalmark'] <=64)) 	{$bukva11= "C-";	$ball11 = "1.67";	$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=65) and  ($myrow111['totalmark'] <=69)) 	{$bukva11= "C"; 	$ball11 = "2.0";		$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=70) and  ($myrow111['totalmark'] <=74)) 	{$bukva11= "C+"; 	$ball11 = "2.33";	$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=75) and  ($myrow111['totalmark'] <=79)) 	{$bukva11= "B-";	$ball11 = "2.67";	$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=80) and  ($myrow111['totalmark'] <=84)) 	{$bukva11= "B"; 	$ball11 = "3.0";		$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=85) and  ($myrow111['totalmark'] <=89)) 	{$bukva11= "B+"; 	$ball11 = "3.33";	$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=90) and  ($myrow111['totalmark'] <=94)) 	{$bukva11= "A-";	$ball11 = "3.67";	$d11=$ball11*$c[11];}
if (($myrow111['totalmark'] >=95) and  ($myrow111['totalmark'] <=100)) 	{$bukva11= "A"; 	$ball11 = "4.0";		$d11=$ball11*$c[11];}

$result112 = mysql_query ("select * from totalmarks2 where studygroupID=$a[12] and studentID=$myrow1[StudentID]"); 
$myrow112 = mysql_fetch_array($result112);

if (($myrow112['totalmark'] >=0) and    ($myrow112['totalmark'] <=49))   	{$bukva12= "F"; 	$ball12 = "0";		$d12=$ball12*$c[12];}
if (($myrow112['totalmark'] >=50) and  ($myrow112['totalmark'] <=54)) 	{$bukva12= "D"; 	$ball12 = "1.0";		$d12=$ball12*$c[12];}
if (($myrow112['totalmark'] >=55) and  ($myrow112['totalmark'] <=59)) 	{$bukva12= "D+"; 	$ball12 = "1.33";	$d12=$ball12*$c[12];}
if (($myrow112['totalmark'] >=60) and  ($myrow112['totalmark'] <=64)) 	{$bukva12= "C-";	$ball12 = "1.67";	$d12=$ball12*$c[12];}
if (($myrow112['totalmark'] >=65) and  ($myrow112['totalmark'] <=69)) 	{$bukva12= "C"; 	$ball12 = "2.0";		$d12=$ball12*$c[12];}
if (($myrow112['totalmark'] >=70) and  ($myrow112['totalmark'] <=74)) 	{$bukva12= "C+"; 	$ball12 = "2.33";	$d12=$ball12*$c[12];}
if (($myrow112['totalmark'] >=75) and  ($myrow112['totalmark'] <=79)) 	{$bukva12= "B-";	$ball12 = "2.67";	$d12=$ball12*$c[12];}
if (($myrow112['totalmark'] >=80) and  ($myrow112['totalmark'] <=84)) 	{$bukva12= "B"; 	$ball12 = "3.0";		$d12=$ball12*$c[12];}
if (($myrow112['totalmark'] >=85) and  ($myrow112['totalmark'] <=89)) 	{$bukva12= "B+"; 	$ball12 = "3.33";	$d12=$ball12*$c[12];}
if (($myrow112['totalmark'] >=90) and  ($myrow112['totalmark'] <=94)) 	{$bukva12= "A-";	$ball12 = "3.67";	$d12=$ball12*$c[12];}
if (($myrow112['totalmark'] >=95) and  ($myrow112['totalmark'] <=100)) 	{$bukva12= "A"; 	$ball12 = "4.0";		$d12=$ball12*$c[12];}

$result113 = mysql_query ("select * from totalmarks2 where studygroupID=$a[13] and studentID=$myrow1[StudentID]"); 
$myrow113 = mysql_fetch_array($result113);

if (($myrow113['totalmark'] >=0) and    ($myrow113['totalmark'] <=49))   	{$bukva13= "F"; 	$ball13 = "0";		$d13=$ball13*$c[13];}
if (($myrow113['totalmark'] >=50) and  ($myrow113['totalmark'] <=54)) 	{$bukva13= "D"; 	$ball13 = "1.0";		$d13=$ball13*$c[13];}
if (($myrow113['totalmark'] >=55) and  ($myrow113['totalmark'] <=59)) 	{$bukva13= "D+"; 	$ball13 = "1.33";	$d13=$ball13*$c[13];}
if (($myrow113['totalmark'] >=60) and  ($myrow113['totalmark'] <=64)) 	{$bukva13= "C-";	$ball13 = "1.67";	$d13=$ball13*$c[13];}
if (($myrow113['totalmark'] >=65) and  ($myrow113['totalmark'] <=69)) 	{$bukva13= "C"; 	$ball13 = "2.0";		$d13=$ball13*$c[13];}
if (($myrow113['totalmark'] >=70) and  ($myrow113['totalmark'] <=74)) 	{$bukva13= "C+"; 	$ball13 = "2.33";	$d13=$ball13*$c[13];}
if (($myrow113['totalmark'] >=75) and  ($myrow113['totalmark'] <=79)) 	{$bukva13= "B-";	$ball13 = "2.67";	$d13=$ball13*$c[13];}
if (($myrow113['totalmark'] >=80) and  ($myrow113['totalmark'] <=84)) 	{$bukva13= "B"; 	$ball13 = "3.0";		$d13=$ball13*$c[13];}
if (($myrow113['totalmark'] >=85) and  ($myrow113['totalmark'] <=89)) 	{$bukva13= "B+"; 	$ball13 = "3.33";	$d13=$ball13*$c[13];}
if (($myrow113['totalmark'] >=90) and  ($myrow113['totalmark'] <=94)) 	{$bukva13= "A-";	$ball13 = "3.67";	$d13=$ball13*$c[13];}
if (($myrow113['totalmark'] >=95) and  ($myrow113['totalmark'] <=100)) 	{$bukva13= "A"; 	$ball13 = "4.0";		$d13=$ball13*$c[13];}

$dd=$d1+$d2+$d3+$d4+$d5+$d6+$d7+$d8+$d9+$d10+$d11+$d12+$d13;
$gpa=round($dd/$credit,2);


$sum1 =round(($myrow101['totalmark'] + $myrow102['totalmark'] + $myrow103['totalmark'] + $myrow104['totalmark'] + $myrow105['totalmark'] + $myrow106['totalmark'] + $myrow107['totalmark'] + $myrow108['totalmark'] + $myrow109['totalmark'] + $myrow110['totalmark'] + $myrow111['totalmark'] + $myrow112['totalmark'] + $myrow113['totalmark'])/13);
printf("<tr><td>%s %s %s</td><td align=center> %s</td><td>$bukva1</td><td>$ball1</td><td align=center> %s</td><td>$bukva2</td><td>$ball2</td><td align=center> %s</td><td>$bukva3</td><td>$ball3</td><td align=center> %s</td><td>$bukva4</td><td>$ball4</td><td align=center> %s</td><td>$bukva5</td><td>$ball5</td><td align=center> %s</td><td>$bukva6</td><td>$ball6</td><td align=center> %s</td><td>$bukva7</td><td>$ball7</td><td align=center> %s</td><td>$bukva8</td><td>$ball8</td><td align=center> %s</td><td>$bukva9</td><td>$ball9</td><td align=center> %s</td><td>$bukva10</td><td>$ball10</td><td  align=center> %s</td><td>$bukva11</td><td>$ball11</td><td >%s</td><td>$bukva12</td><td>$ball12</td><td >%s</td><td>$bukva13</td><td>$ball13</td><td >%s</td><td>$gpa</td></tr>", $myrow1['lastname'],$myrow1['firstname'],$myrow1['patronymic'],$myrow101['totalmark'],$myrow102['totalmark'],$myrow103['totalmark'],$myrow104['totalmark'],$myrow105['totalmark'],$myrow106['totalmark'],$myrow107['totalmark'],$myrow108['totalmark'],$myrow109['totalmark'],$myrow110['totalmark'],$myrow111['totalmark'],$myrow112['totalmark'],$myrow113['totalmark'],$sum1);
}
while ($myrow1 = mysql_fetch_array($result1));




echo "</table>";
		}
	while ($myrow = mysql_fetch_array($result));




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
