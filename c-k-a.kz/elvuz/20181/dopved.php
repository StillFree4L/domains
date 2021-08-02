<?
include("include/bd.php");
$user_id=$_REQUEST['user_id'];
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
                <td><h1>Дополнительные ведомости</h1></td>
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
echo "<h3><a href=dopved.php?user_id=$user_id&add>Добавление ведомости</a>  |  <a href=dopved.php?user_id=$user_id&view>Просмотр ведомости</a></h3>";

//Добавление допольнительной ведомости

if (isset($_REQUEST['add']))
{
	$result = mysql_query ("select * from groups2018 order by name");
	$myrow = mysql_fetch_array($result);
	echo "<p>";
	do
		{
			  echo "<a href=dopved.php?groups=$myrow[groupID]&user_id=$user_id&add>$myrow[name]</a> | ";
		}
	while ($myrow = mysql_fetch_array($result));
	echo "</p>";

	if (isset($_REQUEST['groups'])) 
	{
		$groups=$_REQUEST['groups']; 

		$result = mysql_query ("select * from disved where groupID=$groups and god=2018");
		$myrow = mysql_fetch_array($result);
		do
		{
  			echo "<a href=dopved.php?ved=$myrow[ved]&groups=$groups&t=$myrow[type]&user_id=$user_id&add>$myrow[dis]</a><br><br>";
		}
		while ($myrow = mysql_fetch_array($result));
	} 

	if (isset($_REQUEST['ved'])) 
	{
		$groups=$_REQUEST['groups']; 
		$ved=$_REQUEST['ved']; 
		$t=$_REQUEST['t']; 

		$result = mysql_query ("INSERT INTO  `nitro`.`dopved` (`id` ,`groupID` ,`ved` ,`opis` ,`type`,`god`) VALUES (NULL ,  '$groups',  '$ved',  '1',  '1','2018');");
		if ($result==true) {echo "Успешно добавлен";} else {echo "Не добавлен";}
	}
}

//Просмотр дополнительных демомостей
if (isset($_REQUEST['view']))
{
echo "<table border=1>";
$result = mysql_query ("select * from dopved where god=2018");
$myrow = mysql_fetch_array($result);
do
{
$result1 = mysql_query ("select * from groups2018 where groupID=$myrow[groupID]");
$myrow1 = mysql_fetch_array($result1);

$result2 = mysql_query ("select * from disved where ved=$myrow[ved]");
$myrow2 = mysql_fetch_array($result2);

  echo "<tr><td>$myrow1[name]</td><td><a href=dopved.php?groupID=$myrow[groupID]&dopvedid=$myrow[id]&user_id=$user_id&view>$myrow2[dis]</a></td><td>";

$result3 = mysql_query ("select * from dopvedstud where dopvedid=$myrow[id]");
$myrow3 = mysql_fetch_array($result3);

do
{
$result4 = mysql_query ("select * from students2018 where StudentID=$myrow3[studentID]");
$myrow4 = mysql_fetch_array($result4);

  echo "$myrow3[studentID]-$myrow4[lastname] $myrow4[firstname] $myrow4[patronymic]<br>";
}
while ($myrow3 = mysql_fetch_array($result3));

echo "</td><td><a href=dopved.php?dopvedid=$myrow[id]&user_id=3&input>Ввод оценок</a></td><td><a href=dopvedprint.php?dopvedid=$myrow[id]&user_id=$user_id&type=$myrow[type]>Печатать ведомость</a></td></tr>";
}
while ($myrow = mysql_fetch_array($result));
echo "</table>";

if (isset($_REQUEST['groupID']))
{
$groupID=$_REQUEST['groupID'];
$dopvedid=$_REQUEST['dopvedid'];


$result = mysql_query ("select * from students2018 where groupID=$groupID");
$myrow = mysql_fetch_array($result);
$i=0;
echo "<form name=form1 method=get><table border=1>";
do
{
$i++;
  echo "<tr><td><input type=checkbox value=$myrow[StudentID] name=StudentID$i></td><td>$myrow[lastname] $myrow[firstname] $myrow[patronymic]</td></tr>";
}
while ($myrow = mysql_fetch_array($result));

echo "<input name = kol value=$i type=hidden><input name = user_id value=$user_id type=hidden><input name = view value=0 type=hidden>";

echo "<tr><td><input type=text value=$dopvedid name=dopvedid></td><td><input type=submit name=submit value=OK></td></tr></table></form>";

}

if (isset($_REQUEST['dopvedid']))
{
$kol = $_REQUEST['kol'];
$dopvedid=$_REQUEST['dopvedid'];

			for ($i=1; $i<=$kol; $i++)
				{
					if (isset($_REQUEST['StudentID' . $i]))
					{
					$studentID=$_REQUEST['StudentID' . $i];
					echo "$studentID-<br>";
		$result = mysql_query ("INSERT INTO  `nitro`.`dopvedstud` (`id` ,`dopvedid` ,`studentID` ,`rd` ,`oralexam` ,`testexam`)VALUES (NULL ,  '$dopvedid',  '$studentID',  '',  '',  '');");
		if ($result==true) {echo "Успешно добавлен";} else {echo "Не добавлен";}


					}
				}
}
}

if (isset($_REQUEST['input']))
{
$dopvedid=$_REQUEST['dopvedid'];
$result = mysql_query("select * from dopved where id=$dopvedid");
$myrow = mysql_fetch_array($result);

$result3 = mysql_query("select * from disved where ved=$myrow[ved]");
$myrow3 = mysql_fetch_array($result3);

$result4 = mysql_query("select * from groups2018 where groupID=$myrow[groupID]");
$myrow4 = mysql_fetch_array($result4);


if ($myrow['type']==1)
{
$i=0;
echo "<form name=form1 method=get><h3>$myrow4[name] - $myrow3[dis]</h3><table border=1><tr><td>ФИО студента</td><td>Рейтинг допуска</td><td>Тестирования</td></tr>";
$result1 = mysql_query("select * from dopvedstud where dopvedid=$dopvedid");
$myrow1 = mysql_fetch_array($result1);
do
{
$i++;
$result2 = mysql_query ("select * from students2018 where StudentID=$myrow1[studentID]");
$myrow2 = mysql_fetch_array($result2);
echo "<tr><td><input type=hidden name=StudentID$i value=$myrow1[studentID]><input type=hidden name=id$i value=$myrow1[id]> $i. $myrow2[lastname] $myrow2[firstname] $myrow2[patronymic]</td><td><input type=text name=rd$i value=$myrow1[rd]></td><td><input type=text name=testexam$i value=$myrow1[testexam]></td></tr>";  
}
while ($myrow1 = mysql_fetch_array($result1));


echo "<tr><td colspan=1><input name = t value=1 type=hidden><input name = kol value=$i type=hidden><input name = user_id value=$user_id type=hidden><input type=submit name=submir value=OK></td></tr></table></form>";
}


if ($myrow['type']==2)
{
$i=0;
echo "<form name=form1 method=get><h3>$myrow4[name] - $myrow3[dis]</h3><table border=1><tr><td>ФИО студента</td><td>Рейтинг допуска</td><td>Устнный экзамен</td></tr>";
$result1 = mysql_query("select * from dopvedstud where dopvedid=$dopvedid");
$myrow1 = mysql_fetch_array($result1);
do
{
$i++;
$result2 = mysql_query ("select * from students2018 where StudentID=$myrow1[studentID]");
$myrow2 = mysql_fetch_array($result2);
echo "<tr><td><input type=hidden name=StudentID$i value=$myrow1[studentID]><input type=hidden name=id$i value=$myrow1[id]> $i. $myrow2[lastname] $myrow2[firstname] $myrow2[patronymic]</td><td><input type=text name=rd$i value=$myrow1[rd]></td><td><input type=text name=oralexam$i value=$myrow1[oralexam]></td></tr>";  
}
while ($myrow1 = mysql_fetch_array($result1));


echo "<tr><td colspan=2><input name = t value=2 type=hidden><input name = kol value=$i type=hidden><input name = user_id value=$user_id type=hidden><input type=submit name=submir value=OK></td></tr></table></form>";
}


if ($myrow['type']==3)
{
$i=0;
echo "<form name=form1 method=get><h3>$myrow4[name] - $myrow3[dis]</h3><table border=1><tr><td>ФИО студента</td><td>Рейтинг допуска</td><td>Устнный экзамен</td><td>Тестирования</td></tr>";
$result1 = mysql_query("select * from dopvedstud where dopvedid=$dopvedid");
$myrow1 = mysql_fetch_array($result1);
do
{
$i++;
$result2 = mysql_query ("select * from students2018 where StudentID=$myrow1[studentID]");
$myrow2 = mysql_fetch_array($result2);
echo "<tr><td><input type=hidden name=StudentID$i value=$myrow1[studentID]><input type=hidden name=id$i value=$myrow1[id]> $i. $myrow2[lastname] $myrow2[firstname] $myrow2[patronymic]</td><td><input type=text name=rd$i value=$myrow1[rd]></td><td><input type=text name=oralexam$i value=$myrow1[oralexam]></td><td><input type=text name=testexam$i value=$myrow1[testexam]></td></tr>";  
}
while ($myrow1 = mysql_fetch_array($result1));


echo "<tr><td colspan=2><input name = t value=3 type=hidden><input name = kol value=$i type=hidden><input name = user_id value=$user_id type=hidden><input type=submit name=submir value=OK></td></tr></table></form>";
}





}

if (isset($_REQUEST['kol']))
{
$t= $_REQUEST['t'];
$kol= $_REQUEST['kol'];
for ($i=1; $i<=$kol; $i++)
	{
		$studentID=$_REQUEST['StudentID' . $i];
		$rd=$_REQUEST['rd' . $i];
		$id= $_REQUEST['id'. $i];
if ($t==1) 
{
	$testexam= $_REQUEST['testexam'. $i]; 
	echo $testexam;
	$result = mysql_query ("UPDATE  `nitro`.`dopvedstud` SET  `rd` =  '$rd',`testexam` =  '$testexam' WHERE  `dopvedstud`.`id` =$id;");
}

if ($t==2) 
{
	$oralexam= $_REQUEST['oralexam'. $i]; 
	$result = mysql_query ("UPDATE  `nitro`.`dopvedstud` SET  `rd` =  '$rd',`oralexam` =  '$oralexam' WHERE  `dopvedstud`.`id` =$id;");
}		

if ($t==3) 
{
	$oralexam= $_REQUEST['oralexam'. $i]; 
	$testexam= $_REQUEST['testexam'. $i]; 
	$result = mysql_query ("UPDATE  `nitro`.`dopvedstud` SET  `rd` =  '$rd',`oralexam` =  '$oralexam',`testexam` =  '$testexam' WHERE  `dopvedstud`.`id` =$id ;");

}		

}
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
