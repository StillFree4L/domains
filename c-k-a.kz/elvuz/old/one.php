<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf8">
  <style>
   p {     font-size: 8pt; /* Размер шрифта в пунктах */    }
   td {    font-size: 9pt; /* Размер шрифта в пунктах */    }
.border-ram{
border-top:solid 1px #000000;
border-left:solid 1px #000000;
}
.border-ram1{
border-top:solid 1px #000000;
border-right:solid 1px #000000;
border-left:solid 1px #000000;
}
.border-ram2{border-top:solid 1px #000000;}
  </style>
</head>
<body >
<?
	include("include/bd.php");
	$result_gr = mysql_query ("select * from groups where stateID=0");
	$myrow_gr = mysql_fetch_array($result_gr);
		do 
			{

				$groups=$myrow_gr['groupID']; 
				$ved=$_REQUEST['ved']; 
				$t=$_REQUEST['t']; 
echo "$myrow_gr[name]<br>";

//				echo "<p>Пән/дисциплина:</td><td>";

				$result1 = mysql_query ("select * from disved where groupID=$myrow_gr[groupID] and sem=2 ");
				$myrow1 = mysql_fetch_array($result1);
				do
					{
						  echo "<p>$myrow1[dis]</p><table border=1><tr><td>ФИО слушателя</td><td>Тест</td><td>Устный</td></tr>";

						$result = mysql_query ("select * from students2 where groupID=$myrow_gr[groupID]");
						$myrow = mysql_fetch_array($result);
						do
						{
							
							$result70 = mysql_query ("select * from oral_exam where studentID=$myrow[StudentID] and testID='$myrow1[testID]' ");
							$myrow70 = mysql_fetch_array($result70);
	
							$result50= mysql_query ("select * from test_work where studentID=$myrow[StudentID] and testID='$myrow1[testID]' ");
							$myrow50 = mysql_fetch_array($result50);
$test = $myrow50['ball'];
$oral = $myrow70['ball'];
							if  (($myrow1['type']==3) and ($test<>''))
							{
if ($test==-1){$test='Не явка';}
if ($oral==-1){$oral='Не явка';}

							if ($test<50) { 

								echo "<tr class=border-ram>";
									echo "<td class=border-ram>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td>";
									echo "<td align=center class=border-ram>$test</td><td align=center class=border-ram>$oral</td></tr>";
										}
							if (($test>=50) and ($oral<50)) { 

								echo "<tr class=border-ram>";
									echo "<td class=border-ram>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td>";
									echo "<td align=center class=border-ram>$test</td><td align=center class=border-ram>$oral</td></tr>";
										}


					//		if ($oral<50) { 
					//			echo "<tr class=border-ram>";
					//				echo "<td align=right class=border-ram>$i.</td><td class=border-ram>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td>";
					//				echo "<td align=center class=border-ram>Устный - $oral</td></tr>";
					//					}

}
		//					if ($myrow1['type']==2) {}
		//					if ($myrow1['type']==3) {}
								

						}
						while ($myrow = mysql_fetch_array($result));
echo "</table>";
					}
				while ($myrow1 = mysql_fetch_array($result1));
				

//$result = mysql_query ("select * from students2 where groupID=$groups");
//$myrow = mysql_fetch_array($result);
//do
//{ 
//satu1
//$result1 = mysql_query ("select * from journal2 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=1");
//$myrow1 = mysql_fetch_array($result1);
//r1
//$result2 = mysql_query ("select * from journal2 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=1");
//$myrow2 = mysql_fetch_array($result2);
//satu2
//$result3 = mysql_query ("select * from journal3 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=6 and number=2");
//$myrow3 = mysql_fetch_array($result3);
//r2
//$result4 = mysql_query ("select * from journal3 where StudentID=$myrow[StudentID] and StudyGroupID=$ved and markTypeID=2 and number=2");
//$myrow4 = mysql_fetch_array($result4);

//$result6 = mysql_query ("select * from disved where ved=$ved");
//$myrow6 = mysql_fetch_array($result6);



//if ($t==1) {$exam = $myrow50['ball'];}
//if ($t==2) {$exam = $myrow70['ball'];}
//if ($t==3) {$exam = round(($myrow50['ball'] + $myrow70['ball'])/2);}


//if ($exam<50) { 

  // $mark = round(($myrow1['Mark']+$myrow2['Mark']+$myrow3['Mark']+$myrow4['Mark'])/4);


	//		if ($mark<0) 							{$mark="Неявка по ув.п.";	$bukva= ""; 		$ball = "";}
	//		if (($mark =='') or       ($mark ==0))		{$mark="";				$bukva= ""; 		$ball = "";}
	//		if (($mark >=1) and    ($mark <=49))   	{	$bukva= "F"; 		$ball = "0";}
	//		if (($mark >=50) and  ($mark <=54)) 		{	$bukva= "D"; 		$ball = "1.0";}
	//		if (($mark >=55) and  ($mark <=59)) 		{	$bukva= "D+"; 	$ball = "1.33";}
	//		if (($mark >=60) and  ($mark <=64)) 		{	$bukva= "C-";	$ball = "1.67";}
	//		if (($mark >=65) and  ($mark <=69)) 		{	$bukva= "C"; 		$ball = "2.0";}
	//		if (($mark >=70) and  ($mark <=74)) 		{	$bukva= "C+"; 	$ball = "2.33";}
	//		if (($mark >=75) and  ($mark <=79)) 		{	$bukva= "B-";	$ball = "2.67";}
	//		if (($mark >=80) and  ($mark <=84)) 		{	$bukva= "B"; 		$ball = "3.0";}
	//		if (($mark >=85) and  ($mark <=89)) 		{	$bukva= "B+"; 	$ball = "3.33";}
	//		if (($mark >=90) and  ($mark <=94)) 		{	$bukva= "A-";	$ball = "3.67";}
	//		if (($mark >=95) and  ($mark <=100)) 	{	$bukva= "A"; 		$ball = "4.0";}


//echo "<tr class=border-ram>";
//echo "<td align=right class=border-ram>$i.</td><td class=border-ram>$myrow[lastname] $myrow[firstname] $myrow[patronymic] </td>";
//echo "<td align=center class=border-ram>$myrow[zachetka]</td>";
//echo "<td align=center class=border-ram>$mark</td>";
//echo "<td align=center class=border-ram>$bukva</td>";
//echo "<td align=center class=border-ram>$ball</td>";



//	if ($t==1) 
//		{
//			$mark1=$myrow50['ball']; 
//			if ($mark1<0) 							{$mark1="Неявка по ув.п.";	$bukva1= ""; 		$ball1 = "";}
//			if (($mark1 =='') )		{$mark1="";		$bukva1= ""; 		$ball1 = "";}
//			if (($mark1 ==0) )		{$mark1="";		$bukva1= ""; 		$ball1 = "";}
//			if (($mark1>0) and    ($mark1 <=49))   		{$bukva1= "F"; 	$ball1 = "0";}
//			if (($mark1 >=50) and  ($mark1 <=54)) 		{$bukva1= "D"; 	$ball1 = "1.0";}
//			if (($mark1 >=55) and  ($mark1<=59)) 		{$bukva1= "D+"; 	$ball1 = "1.33";}
//			if (($mark1 >=60) and  ($mark1 <=64)) 		{$bukva1= "C-";	$ball1 = "1.67";}
//			if (($mark1 >=65) and  ($mark1 <=69)) 		{$bukva1= "C"; 	$ball1 = "2.0";}
//			if (($mark1 >=70) and  ($mark1 <=74)) 		{$bukva1= "C+"; 	$ball1 = "2.33";}
//			if (($mark1 >=75) and  ($mark1 <=79)) 		{$bukva1= "B-";	$ball1 = "2.67";}
//			if (($mark1 >=80) and  ($mark1 <=84)) 		{$bukva1= "B"; 	$ball1 = "3.0";}
///			if (($mark1 >=85) and  ($mark1 <=89)) 		{$bukva1= "B+"; 	$ball1 = "3.33";}
//			if (($mark1 >=90) and  ($mark1 <=94)) 		{$bukva1= "A-";	$ball1 = "3.67";}
//			if (($mark1 >=95) and  ($mark1 <=100)) 		{$bukva1= "A"; 	$ball1 = "4.0";}
//			echo "<td align=center class=border-ram></td>";
//			echo "<td align=center class=border-ram></td>";
//			echo "<td align=center class=border-ram></td>";
//			echo "<td align=center class=border-ram></td>";
//			echo "<td align=center class=border-ram></td>";
//			echo "<td align=center class=border-ram></td>";
//		}

//	if ($t==2) 
//		{
//			$mark1=$myrow70['ball']; 
//			if ($mark1<0) 							{$mark1="Неявка по ув.п.";	$bukva1= ""; 		$ball1 = "";}
//			if (($mark1 =='') )		{$mark1="";		$bukva1= ""; 		$ball1 = "";}
//			if (($mark1 ==0) )		{$mark1="";		$bukva1= ""; 		$ball1 = "";}
//			if (($mark1>0) and    ($mark1 <=49))   		{$bukva1= "F"; 	$ball1 = "0";}
//			if (($mark1 >=50) and  ($mark1 <=54)) 		{$bukva1= "D"; 	$ball1 = "1.0";}
//			if (($mark1 >=55) and  ($mark1<=59)) 		{$bukva1= "D+"; 	$ball1 = "1.33";}
///			if (($mark1 >=60) and  ($mark1 <=64)) 		{$bukva1= "C-";	$ball1 = "1.67";}
//			if (($mark1 >=65) and  ($mark1 <=69)) 		{$bukva1= "C"; 	$ball1 = "2.0";}
//			if (($mark1 >=70) and  ($mark1 <=74)) 		{$bukva1= "C+"; 	$ball1 = "2.33";}
//			if (($mark1 >=75) and  ($mark1 <=79)) 		{$bukva1= "B-";	$ball1 = "2.67";}
//			if (($mark1 >=80) and  ($mark1 <=84)) 		{$bukva1= "B"; 	$ball1 = "3.0";}
//			if (($mark1 >=85) and  ($mark1 <=89)) 		{$bukva1= "B+"; 	$ball1 = "3.33";}
//			if (($mark1 >=90) and  ($mark1 <=94)) 		{$bukva1= "A-";	$ball1 = "3.67";}
//			if (($mark1 >=95) and  ($mark1 <=100)) 		{$bukva1= "A"; 	$ball1 = "4.0";}
///			echo "<td align=center class=border-ram></td>";
///			echo "<td align=center class=border-ram></td>";
///			echo "<td align=center class=border-ram></td>";
///			echo "<td align=center class=border-ram></td>";
//			echo "<td align=center class=border-ram></td>";
//			echo "<td align=center class=border-ram></td>";
//		}


//	if ($t==3) 
//	{
///			$mark1=$myrow50['ball'];
//			$mark2=$myrow70['ball']; 
//			if ($mark1<0) 							{$mark1="Неявка по ув.п.";	$bukva1= ""; 		$ball1 = "";}
//			if (($mark1 =='') )		{$mark1="";		$bukva1= ""; 		$ball1 = "";}
//			if (($mark1 ==0) )		{$mark1="";		$bukva1= ""; 		$ball1 = "";}
//			if (($mark1>0) and    ($mark1 <=49))   		{$bukva1= "F"; 	$ball1 = "0";}
//			if (($mark1 >=50) and  ($mark1 <=54)) 		{$bukva1= "D"; 	$ball1 = "1.0";}
//			if (($mark1 >=55) and  ($mark1<=59)) 		{$bukva1= "D+"; 	$ball1 = "1.33";}
//			if (($mark1 >=60) and  ($mark1 <=64)) 		{$bukva1= "C-";	$ball1 = "1.67";}
//			if (($mark1 >=65) and  ($mark1 <=69)) 		{$bukva1= "C"; 	$ball1 = "2.0";}
//			if (($mark1 >=70) and  ($mark1 <=74)) 		{$bukva1= "C+"; 	$ball1 = "2.33";}
//			if (($mark1 >=75) and  ($mark1 <=79)) 		{$bukva1= "B-";	$ball1 = "2.67";}
//			if (($mark1 >=80) and  ($mark1 <=84)) 		{$bukva1= "B"; 	$ball1 = "3.0";}
//			if (($mark1 >=85) and  ($mark1 <=89)) 		{$bukva1= "B+"; 	$ball1 = "3.33";}
//			if (($mark1 >=90) and  ($mark1 <=94)) 		{$bukva1= "A-";	$ball1 = "3.67";}
//			if (($mark1 >=95) and  ($mark1 <=100)) 		{$bukva1= "A"; 	$ball1 = "4.0";}
//			echo "<td align=center class=border-ram></td>";
//			echo "<td align=center class=border-ram></td>";
//			echo "<td align=center class=border-ram></td>";
//
//			if ($mark2<0) 							{$mark2="Неявка по ув.п.";	$bukva2= ""; 		$ball2 = "";}
//			if (($mark2 =='') )		{$mark2="";	    	         $bukva2= ""; 		$ball2= "";}
//			if (($mark2 ==0) )		{$mark2="";		         $bukva2= ""; 		$ball2 = "";}
//			if (($mark2>0) and        ($mark2 <=49))   		{$bukva2= "F"; 	$ball2 = "0";}
//			if (($mark2 >=50) and  ($mark2 <=54)) 		{$bukva2= "D"; 	$ball2 = "1.0";}
//			if (($mark2 >=55) and  ($mark2<=59)) 		{$bukva2= "D+"; 	$ball2 = "1.33";}
//			if (($mark2 >=60) and  ($mark2 <=64)) 		{$bukva2= "C-";	$ball2 = "1.67";}
//			if (($mark2 >=65) and  ($mark2 <=69)) 		{$bukva2= "C"; 	$ball2 = "2.0";}
//			if (($mark2 >=70) and  ($mark2 <=74)) 		{$bukva2= "C+"; 	$ball2 = "2.33";}
//			if (($mark2 >=75) and  ($mark2 <=79)) 		{$bukva2= "B-";	$ball2 = "2.67";}
//			if (($mark2 >=80) and  ($mark2 <=84)) 		{$bukva2= "B"; 	$ball2 = "3.0";}
//			if (($mark2 >=85) and  ($mark2 <=89)) 		{$bukva2= "B+"; 	$ball2 = "3.33";}
//			if (($mark2 >=90) and  ($mark2 <=94)) 		{$bukva2= "A-";	$ball2 = "3.67";}
//			if (($mark2 >=95) and  ($mark2 <=100)) 		{$bukva2= "A"; 	$ball2 = "4.0";}
//			echo "<td align=center class=border-ram></td>";
//			echo "<td align=center class=border-ram></td>";
//			echo "<td align=center class=border-ram></td>";
//
//	}
//	echo "<td class=border-ram1></td>";
//	echo "</tr>";
//	$i++;
//}
//}
//while ($myrow = mysql_fetch_array($result));
//$a = $a1+$a2;
//$b=$b1+$b2+$b3;
//$c=$c1+$c2+$c3+$d1+$d2;

//echo "</table>";


}
while ($myrow_gr = mysql_fetch_array($result_gr));
?>


</body>

</html>
