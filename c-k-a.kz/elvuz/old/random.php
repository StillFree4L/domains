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
<table width="765" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
		<div style="position:absolute; top:60px; margin-left:7px; width:200px">
<? include("include/top.php")?>

		</div>
		<img src="images/t1.jpg" width="207" height="237"></td>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><img src="images/t2.gif" width="355" height="37"></td>
          </tr>
          <tr>
            <td><img src="images/t2-5.jpg" width="355" height="200"></td>
          </tr>
        </table></td>
        <td><img src="images/t3.jpg" width="203" height="237"></td>
      </tr>
    </table></td>
  </tr>
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
                <td><h1>Ведомости все оценки</h1></td>
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
//for ($i=1; $i<=30;$i++)
//{
//$a[$i]=rand(1,50);
//echo "$i - $a[$i]<br>";
//}

//for ($i=1; $i<=30;$i++)
//{
//for ($j=2; $j<28;$j++)
//{
//if ($a[$i]==$a[$j]) {$a[$i]=$a[$i]+1;}
//}
//echo "$i -$a[$i]<br>";
//}

//$mas[1]=1;
//for($i=1;$i<10;$i++)
//{
//$mas[$i+1]=rand(1,15);
//echo "$i - $mas[$i]<br>";
//$t=$mas[$i];
//$flag=0;
//        while($flag==1)
  //      {
        //for($j=2;$j<=10;$j++)
      //      {
    //                    if($t>$mas[$j]) $mas[$j]=$mas[$j]+1;
  //          }
      //      if($j==9) {$flag=1;}
            
    //    }
 
//}
for ($i=1; $i<=30;$i++)
{
$a[$i]=rand(1,50);
echo "$i - $a[$i]<br>";
}

for ($i=1; $i<30;$i++)
	{
		for ($j=1; $j<30;$j++)
		{
			if ($a[$j]>$a[$j+1])
				{
					$x = $a[$j];
					$a[$j]=$a[$j+1];
					$a[$j+1]=$x;
				}
		}
	}

for ($i=1; $i<=30;$i++)
{
//$a[$i]=rand(1,50);
echo "$i - $a[$i]<br>";
}
for ($i=1; $i<30;$i++)
	{
		for ($j=1; $j<30;$j++)
		{
			if ($a[$j]=$a[$j+1])
				{
					//$x = $a[$j];
					$a[$j]=$a[$j]+1;
					//$a[$j+1]=$x;
				}
		}
	}

for ($i=1; $i<=30;$i++)
{
//$a[$i]=rand(1,50);
echo "$i - $a[$i]<br>";
}

//{сортировка пузырьком простая}
//for i:=1 to n-1 do
//for j:=1 to n-1 do
//if a[j]>a[j+1] then
// begin
//  x:=a[j];
//.  a[j]:=a[j+1];
//  a[j+1]:=x;
// end;
//writeln('Отсортированный массив:');
//for i:=1 to n do
//write(a[i],' ');
//readln
//end.

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
