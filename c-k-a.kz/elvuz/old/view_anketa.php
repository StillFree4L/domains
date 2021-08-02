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
            <td width="1" height="100%" valign="top">             <? include("include/menu.php");  ?></td>
            <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td><h1>Добро пожаловать!</h1></td>
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
echo "<h3>3 - курс</h3><table border=1><tr><td>Ф.И.О. преподавателя</td><td>lang</td><td>Личные качества преподавателя</td><td>Профессиональные качества преподавателя</td><td>Характеристика работы преподавателя</td><td>Оқытушының жеке сапасы</td><td>Оқытушының кәсіби сапасы</td><td>Оқытушы жұмысының сипаттамасы</td></tr>";
$result = mysql_query ("select * from anketa_teacher where kurs=3 order by lang,fio");
$myrow = mysql_fetch_array($result);
do
{
	$result1 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=1");
	$myrow1 = mysql_fetch_array($result1);
	$sum1=0;
	$i1=0;
	do
	{
		  $i1++;
		  $sum1 = $sum1+$myrow1['ball'];
	}
	while ($myrow1 = mysql_fetch_array($result1));
	$s1=$sum1/$i1;

	$result2 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=2");
	$myrow2 = mysql_fetch_array($result2);
	$sum2=0;
	$i2=0;
	do
	{
		  $i2++;
		  $sum2 = $sum2+$myrow2['ball'];
	}
	while ($myrow2 = mysql_fetch_array($result2));
	$s2=$sum2/$i2;

	$result3 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=3");
	$myrow3 = mysql_fetch_array($result3);
	$sum3=0;
	$i3=0;
	do
	{
		  $i3++;
		  $sum3 = $sum3+$myrow3['ball'];
	}
	while ($myrow3 = mysql_fetch_array($result3));
	$s3=$sum3/$i3;


	$result4 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=4");
	$myrow4 = mysql_fetch_array($result4);
	$sum4=0;
	$i4=0;
	do
	{
		  $i4++;
		  $sum4 = $sum4+$myrow4['ball'];
	}
	while ($myrow4 = mysql_fetch_array($result4));
	$s4=$sum4/$i4;


	$result5 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=5");
	$myrow5 = mysql_fetch_array($result5);
	$sum5=0;
	$i5=0;
	do
	{
		  $i5++;
		  $sum5 = $sum5+$myrow5['ball'];
	}
	while ($myrow5 = mysql_fetch_array($result5));
	$s5=$sum5/$i5;

	$result6 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=6");
	$myrow6 = mysql_fetch_array($result6);
	$sum6=0;
	$i6=0;
	do
	{
		  $i6++;
		  $sum6 = $sum6+$myrow6['ball'];
	}
	while ($myrow6 = mysql_fetch_array($result6));
	$s6=$sum6/$i6;
$d1=round($s1,2);
$d2=round($s2,2);
$d3=round($s3,2);
$d4=round($s4,2);
$d5=round($s5,2);
$d6=round($s6,2);
echo "<tr><td>$myrow[fio]</td><td>$myrow[lang]</td><td>$d1</td><td>$d2</td><td>$d3</td><td>$d4</td><td>$d5</td><td>$d6</td></tr>";
}
while ($myrow = mysql_fetch_array($result));
echo "</table>";
//echo "<h3>2 - курс</h3>";
//echo "<table border=1><tr><td>Ф.И.О. преподавателя</td><td>lang</td><td>Личные качества преподавателя</td><td>Профессиональные качества преподавателя</td><td>Характеристика работы преподавателя</td><td>Оқытушының жеке сапасы</td><td>Оқытушының кәсіби сапасы</td><td>Оқытушы жұмысының сипаттамасы</td></tr>";
//$result = mysql_query ("select * from anketa_teacher where kurs=2 order by lang,fio");
//$myrow = mysql_fetch_array($result);
//do
//{
//	$result7 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=7");
//	$myrow7 = mysql_fetch_array($result7);
//	$sum7=0;
//	$i7=0;
//	do
//	{
//		  $i7++;
//		  $sum7 = $sum7+$myrow7['ball'];
//	}
//	while ($myrow7 = mysql_fetch_array($result7));
//	$s7=$sum7/$i7;
//
//	$result8 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=8");
//	$myrow8 = mysql_fetch_array($result8);
//	$sum8=0;
//	$i8=0;
//	do
//	{
//		  $i8++;
//		  $sum8 = $sum8+$myrow8['ball'];
//	}
//	while ($myrow8 = mysql_fetch_array($result8));
//	$s8=$sum8/$i8;
//
//	$result9 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=9");
//	$myrow9 = mysql_fetch_array($result9);
//	$sum9=0;
//	$i9=0;
//	do
//	{
//		  $i9++;
//		  $sum9 = $sum9+$myrow9['ball'];
//	}
//	while ($myrow9 = mysql_fetch_array($result9));
//	$s9=$sum9/$i9;
//

//	$result10 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=10");
//	$myrow10 = mysql_fetch_array($result10);
//	$sum10=0;
//	$i10=0;
//	do
//	{
//		  $i10++;
//		  $sum10 = $sum10+$myrow10['ball'];
//	}
//	while ($myrow10 = mysql_fetch_array($result10));
//	$s10=$sum10/$i10;


//	$result11 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=11");
//	$myrow11 = mysql_fetch_array($result11);
//	$sum11=0;
//	$i11=0;
//	do
//	{
//		  $i11++;
//		  $sum11 = $sum11+$myrow11['ball'];
//	}
//	while ($myrow11 = mysql_fetch_array($result11));
//	$s11=$sum11/$i11;

//	$result12 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=12");
//	$myrow12 = mysql_fetch_array($result12);
//	$sum12=0;
//	$i12=0;
//	do
//	{
//		  $i12++;
//		  $sum12 = $sum12+$myrow12['ball'];
//	}
//	while ($myrow12 = mysql_fetch_array($result12));
//	$s12=$sum12/$i12;

//echo "<tr><td>$myrow[fio]</td><td>$myrow[lang]</td><td>$s7</td><td>$s8</td><td>$s9</td><td>$s10</td><td>$s11</td><td>$s12</td></tr>";
//}
//while ($myrow = mysql_fetch_array($result));
//echo "</table>";



//echo "<h3>3 - курс</h3><table border=1><tr><td>Ф.И.О. преподавателя</td><td>lang</td><td>Личные качества преподавателя</td><td>Профессиональные качества преподавателя</td><td>Характеристика работы преподавателя</td><td>Оқытушының жеке сапасы</td><td>Оқытушының кәсіби сапасы</td><td>Оқытушы жұмысының сипаттамасы</td></tr>";
//$result = mysql_query ("select * from anketa_teacher where kurs=3 order by lang,fio");
//$myrow = mysql_fetch_array($result);
//do
//{
//	$result13 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=13");
//	$myrow13 = mysql_fetch_array($result13);
//	$sum13=0;
//	$i13=0;
//	do
//	{
//		  $i13++;
//		  $sum13 = $sum13+$myrow13['ball'];
//	}
//	while ($myrow13 = mysql_fetch_array($result13));
//	$s13=$sum13/$i13;

//	$result14 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=14");
//	$myrow14 = mysql_fetch_array($result14);
//	$sum14=0;
//	$i14=0;
//	do
//	{
//		  $i14++;
//		  $sum14 = $sum14+$myrow14['ball'];
//	}
//	while ($myrow14 = mysql_fetch_array($result14));
//	$s14=$sum14/$i14;

//	$result15 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=15");
//	$myrow15 = mysql_fetch_array($result15);
//	$sum15=0;
//	$i15=0;
///	do
//	{
//		  $i15++;
//		  $sum15 = $sum15+$myrow15['ball'];
//	}
//	while ($myrow15 = mysql_fetch_array($result15));
//	$s15=$sum15/$i15;
//

//	$result16 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=16");
//	$myrow16 = mysql_fetch_array($result16);
//	$sum16=0;
//	$i16=0;
//	do
//	{
//		  $i16++;
//		  $sum16 = $sum16+$myrow16['ball'];
//	}
//	while ($myrow16 = mysql_fetch_array($result16));
//	$s16=$sum16/$i16;


//	$result17 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=17");
//	$myrow17 = mysql_fetch_array($result17);
//	$sum17=0;
//	$i17=0;
//	do
//	{
//		  $i17++;
//		  $sum17 = $sum17+$myrow17['ball'];
//	}
//	while ($myrow17 = mysql_fetch_array($result17));
//	$s17=$sum17/$i17;

//	$result18 = mysql_query ("select * from anketa_work where teacherID=$myrow[id] and voprosID=18");
//	$myrow18 = mysql_fetch_array($result18);
//	$sum18=0;
//	$i18=0;
//	do
//	{
//		  $i18++;
//		  $sum18 = $sum18+$myrow18['ball'];
//	}
//	while ($myrow18 = mysql_fetch_array($result18));
//	$s18=$sum18/$i18;
//
//echo "<tr><td>$myrow[fio]</td><td>$myrow[lang]</td><td>$s13</td><td>$s14</td><td>$s15</td><td>$s16</td><td>$s17</td><td>$s18</td></tr>";
//}
//while ($myrow = mysql_fetch_array($result));
//echo "</table>";



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
