<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf8">


  <style>
   p {
    font-size: 9pt; /* Размер шрифта в пунктах */ 
   }
   td {
    font-size: 9pt; /* Размер шрифта в пунктах */ 
   }
.border-ram{
border-top:solid 1px #000000;
border-left:solid 1px #000000;

}
.border-ram1{

border-top:solid 1px #000000;
border-right:solid 1px #000000;
border-left:solid 1px #000000;
}
.border-ram2{
border-top:solid 1px #000000;
}

  </style>

</head>

<body text=2>
<?
include("include/bd.php");

$fak=$_REQUEST['fak']; 
$grup=$_REQUEST['grup']; 
$dis=$_REQUEST['dis']; 

$result = mysql_query ("select * from fak where id = $fak");
$myrow = mysql_fetch_array($result);

?>

<p align=right>Ф.4.04-14-1</p>
<p align=center><b>Министерство образования и науки Республики Казахстан<br>
ЦЕНТРАЛЬНО-КАЗАХСТАНСКАЯ АКАДЕМИЯ</p>
 
<p align=center><b>РУБЕЖНО-РЕЙТИНГОВАЯ ВЕДОМОСТЬ № ___<br>
___________ уч. год</p>


<table border=0 align=center>
<tr><td>Факультет:  </td>		<td><? echo "$myrow[fak]"; ?></td>		<td>Специальность:</td> 			<td> Юриспруденция</td></tr>           
<tr><td>Форма обучения: </td><td> Заочная</td>					<td> Группа:  </td> 				<td>ЮВ-17-11 (р)           </td></tr>
<tr><td>курс: </td>			<td> 1</td>							<td>семестр:  </td> 				<td>2 </td></tr>
<tr><td>Дисциплина: </td>		<td> Административное право РК</td>	<td>Количество кредитов:  </td> 	<td>3           </td></tr>
<tr><td>Ф.И.О. тьютора: </td>	<td>Карпекин А. В.  </td>				<td>Дата: </td> 					<td>20.03.2018</td></tr>
</table>
<br><br>




	



<table border=0 align=center cellspacing="0" cellpadding="1"><tr>
<td align=center class=border-ram><b>№</td>
<td align=center class=border-ram><b>Ф.И.О обучающегося</td>
<td align=center class=border-ram><b>Текущий контроль</td>
<td align=center class=border-ram><b>Рубежный контроль</td>
<td align=center class=border-ram><b>Рейтинг допуск</td>
<td align=center class=border-ram1><b>РД, с учетом аппеляции</td></tr>





<?
$i=1;
$a1=0; //a
$a2=0;//a-
$b1=0;//b+
$b2=0;//b
$b3=0;//b-
$c1=0;//c+
$c2=0;//c
$c3=0;//c-
$d1=0;//d
$d2=0;//d-
$f=0;//f



//id
//user_id
//last_name
//first_name/
//middle_name
//grup
//prepay
//sex
//nationality_id
//reception_date
//adress
//foreign_lang
//need_host
//birthdate
//hasid
//hasdiploma
//hasmedid
//hasphotos
//haslist
//teach	
//is_teacher
//iin
//identify_number
//hasdiploma_copy
//ts







//$result = mysql_query ("SELECT * FROM `users_info` WHERE `grup` = $grup ORDER BY `grup` ASC");
//$myrow = mysql_fetch_array($result);

$result = mysql_query ("select * from marks order by dis ");
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


//if ($myrow2['Mark']<0) {$mark="Неявка по ув.п.";				$bukva= ""; 		$ball = "";}
//if (($myrow2['Mark'] =='') or  ($myrow2['Mark'] ==0))		{$mark="";				$bukva= ""; 		$ball = "";}
//if (($myrow2['Mark'] >=1) and  ($myrow2['Mark'] <=49))   	{$mark=$myrow2['Mark'];	$bukva= "F"; 		$ball = "0";}
//if (($myrow2['Mark'] >=50) and  ($myrow2['Mark'] <=54)) 	{$mark=$myrow2['Mark'];	$bukva= "D"; 		$ball = "1.0";}
//if (($myrow2['Mark'] >=55) and  ($myrow2['Mark'] <=59)) 	{$mark=$myrow2['Mark'];	$bukva= "D+"; 	$ball = "1.33";}
//if (($myrow2['Mark'] >=60) and  ($myrow2['Mark'] <=64)) 	{$mark=$myrow2['Mark'];	$bukva= "C-";	$ball = "1.67";}
//if (($myrow2['Mark'] >=65) and  ($myrow2['Mark'] <=69)) 	{$mark=$myrow2['Mark'];	$bukva= "C"; 		$ball = "2.0";}
//if (($myrow2['Mark'] >=70) and  ($myrow2['Mark'] <=74)) 	{$mark=$myrow2['Mark'];	$bukva= "C+"; 	$ball = "2.33";}
//if (($myrow2['Mark'] >=75) and  ($myrow2['Mark'] <=79)) 	{$mark=$myrow2['Mark'];	$bukva= "B-";	$ball = "2.67";}
//if (($myrow2['Mark'] >=80) and  ($myrow2['Mark'] <=84)) 	{$mark=$myrow2['Mark'];	$bukva= "B"; 		$ball = "3.0";}
//if (($myrow2['Mark'] >=85) and  ($myrow2['Mark'] <=89)) 	{$mark=$myrow2['Mark'];	$bukva= "B+"; 	$ball = "3.33";}
//if (($myrow2['Mark'] >=90) and  ($myrow2['Mark'] <=94)) 	{$mark=$myrow2['Mark'];	$bukva= "A-";	$ball = "3.67";}
//if (($myrow2['Mark'] >=95) and  ($myrow2['Mark'] <=100)) 	{$mark=$myrow2['Mark'];	$bukva= "A"; 		$ball = "4.0";}

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
?>
<tr><td colspan=7 class=border-ram2>&nbsp;</td></tr>
</table>
<table>
<tr><td>Тьютор: _________</td><td>Декан: _________ </td><td> Офис регистратор: _________</td></tr>
</table>
<table>
<tr><td>Итого:</td><td>отлично</td><td>1</td><td>хорошо</td><td>15</td><td>удовлетворительно</td><td>17</td><td>неудовлетворительно</td><td>0</td><td>не явка</td><td>1</td></tr></table>

<p><b>Примечание:</b><br>
1) Преподаватель ответственен за подсчет итоговой оценки</p>


<table align=center border=1>
<tr><td align=center>Рейтинг</td><td align=center>0-49</td><td align=center>50-54</td><td align=center>55-59</td><td align=center>60-64</td><td align=center>65-69</td><td align=center>70-74</td><td align=center>75-79</td><td align=center>80-84</td><td align=center>85-89</td><td align=center>90-94</td><td align=center>95-100</td></tr>
<tr><td align=center>Балл</td><td align=center>0</td><td align=center>1</td><td align=center>1.33</td><td align=center>1.67</td><td align=center>2</td><td align=center>2.33</td><td align=center>2.67</td><td align=center>3</td><td align=center>3.33</td><td align=center>3.67</td><td align=center>4</td></tr>
<tr><td align=center>Буквенный эквивалент</td><td align=center>F</td><td align=center>D</td><td align=center>D+</td><td align=center>C-</td><td align=center>C</td><td align=center>C+</td><td align=center>B-</td><td align=center>B</td><td align=center>B+</td><td align=center>A-</td><td align=center>A</td></tr>
<tr><td align=center>Оценка</td><td align=center>Неуд.</td><td colspan=5 align=center>Удовлетворительно</td><td colspan=3 align=center>Хорошо</td><td colspan=2 align=center>Отлично</td></tr>
</table>


</body>

</html>
