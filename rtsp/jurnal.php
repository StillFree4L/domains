<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <title>ARM WHITE LIST VIZIR-ST</title>
    <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    </head>
<body>
<?php require_once 'bd.php'; ?>

<style type="text/css">
 .left_menu {float:left; width:24%; margin-left:1%}
  .right {float:right; width:74%}

  .rounded {
counter-reset: li; 
list-style: none; 
font: 14px "Trebuchet MS", "Lucida Sans";
padding: 0;
text-shadow: 0 1px 0 rgba(255,255,255,.5);
}
.rounded a {
position: relative;
display: block;
padding: .4em .4em .4em 2em;
margin: .5em 0;
background: #DAD2CA;
color: #444;
text-decoration: none;
border-radius: .3em;
transition: .3s ease-out;
}
.rounded a:hover {background: #E9E4E0;}
.rounded a:hover:before {transform: rotate(360deg);}
.rounded a:before {
content: counter(li);
counter-increment: li;
position: absolute;
left: -1.3em;
top: 50%;
margin-top: -1.3em;
background: #8FD4C1;
height: 2.5em;
width: 3em;
line-height: 2em;
border: .3em solid white;
text-align: center;
font-weight: bold;
border-radius: 2em;
transition: all .3s ease-out;
}
</style>
<div class="left_menu">

<?php
mysql_connect($GLOBALS["localhost"],$GLOBALS["login"],$GLOBALS["pass"]);
mysql_select_db($GLOBALS["bd"]);
?>

<ul>

<button type="button" onclick="window.location.href = 'index.php'" class="btn btn-success">ARM OPERATOR</button>
<button type="button" onclick="window.location.href = 'jurnal.php?update=1'" class="btn btn-success">Обновить базу</button>
<br>ARM JURNAL
<hr>
<h3 style="padding-left: 2%;">Добавить или изменить</h3>
<form style="padding-left: 2%;" action="jurnal.php" method="POST">
    <p>ФИО: <input type="text" name="fio" /></p>
    <p>Должность : <input type="text" name="key" /></p>
    <p>Дата начала : <input type="datetime-local" name="open_admission" /></p>
    <p>Дата окончания : <input type="datetime-local" name="close_admission" /></p>
    <p>Номер ГРНЗ: 
<?php 
$sqls= mysql_query("select * from jurnal");
?>
<input type="text" name="number_id" list="number_id" />
	<datalist type="text" id="number_id" name="number_id">
	<?php while($sql=mysql_fetch_array($sqls)){ ?>
		<option value="<?=$sql['number_id']?>"><?=$sql['number_id']?></option>
	<?php }  ?>
	</datalist>

</p>

    <input type="hidden" name="allStreams" value="0" />
    <input type="checkbox" name="allStreams" value="1"> Все ST Контрольные Пункты<Br><Br>
    <input type="hidden" name="stream0" value="0" />
    <input type="checkbox" name="stream0" value="1"> ST0 КП3-К2 (выезд)<Br>
    <input type="hidden" name="stream1" value="0" />
    <input type="checkbox" name="stream1" value="1"> ST1 КП1-К2 (выезд)<Br>
    <input type="hidden" name="stream2" value="0" />
    <input type="checkbox" name="stream2" value="1"> ST2 КП4-К2 (выезд)<Br>
    <input type="hidden" name="stream3" value="0" />
    <input type="checkbox" name="stream3" value="1"> ST3 КП4-К1 (въезд)<Br>
    <input type="hidden" name="stream4" value="0" />
    <input type="checkbox" name="stream4" value="1"> ST4 КП1-К1 (въезд)<Br>
    <input type="hidden" name="stream5" value="0" />
    <input type="checkbox" name="stream5" value="1"> ST5 КП2-К2 (выезд)<Br>
    <input type="hidden" name="stream6" value="0" />
    <input type="checkbox" name="stream6" value="1"> ST6 КП2-К1 (въезд)<Br>
    <input type="hidden" name="stream8" value="0" />
    <input type="checkbox" name="stream8" value="1"> ST8 КП3-К1 (въезд)<Br><Br>
    <input type="submit" value="Отправить">
</form>

</ul>

</div>

<div class="right">

<p style="text-align: center;">Список номеров и разрешений</p>
<form action="jurnal.php" method="POST">
    <hr>
        <p>Поиск: <input style="width: 80%;" type="text" name="search"> <input type="submit" value="Поиск"></p>
        <hr>
    </form>

<?php


if (isset($_GET['page'])){
   $page = $_GET['page'];
}else {$page = 1;}
$kol = 10;  //количество записей для вывода
$art = ($page*$kol)-$kol; // определяем, с какой записи нам выводить
if (isset($_GET['page'])){
   $i = $art;
}else {$i = 0;}
	if(isset($_POST['search'])) $sqlAll = mysql_query("SELECT * FROM jurnal 
WHERE number_id LIKE '%".$_POST['search']."%' OR fio LIKE '%".$_POST['search']."%'");
	else  $sqlAll= mysql_query("select * from jurnal order by ts desc limit $art,$kol");
$total=row();
$str_pag = ceil($total/$kol);
?>

<table class="table table-hover">
    <tr>
        <td>№</td><td>Номер</td><td>ФИО</td><td>Дата начала</td><td>Дата окончания</td>
        <td>0</td><td>&#10102;</td><td>&#10103;</td><td>&#10104;</td><td>&#10105;</td>
        <td>&#10106;</td><td>&#10107;</td><td>&#10109;</td><td></td>
    </tr>

<?php while($sql=mysql_fetch_array($sqlAll)){ $i++; ?>
        <tr>
		<td style="font-size:11px"><?=$i?></td>
            <td style="font-size:11px"><?=$sql['number_id']?></td>
            <td style="font-size:11px"><?=$sql['fio']?></td>
	    <td style="font-size:11px"><?=$sql['open_admission']?></td>
	    <td style="font-size:11px"><?=$sql['close_admission']?></td>
            <td style="font-size:13px"><?=$sql['stream0']==1 ? '&#10004;' : '&#10008;'?></td>
            <td style="font-size:13px"><?=$sql['stream1']==1 ? '&#10004;' : '&#10008;'?></td>
            <td style="font-size:13px"><?=$sql['stream2']==1 ? '&#10004;' : '&#10008;'?></td>
            <td style="font-size:13px"><?=$sql['stream3']==1 ? '&#10004;' : '&#10008;'?></td>
            <td style="font-size:13px"><?=$sql['stream4']==1 ? '&#10004;' : '&#10008;'?></td>
            <td style="font-size:13px"><?=$sql['stream5']==1 ? '&#10004;' : '&#10008;'?></td>
            <td style="font-size:13px"><?=$sql['stream6']==1 ? '&#10004;' : '&#10008;'?></td>
            <td style="font-size:13px"><?=$sql['stream8']==1 ? '&#10004;' : '&#10008;'?></td>
            <td style="font-size:11px">
            <a class="btn btn-default" style="text-align:center;" href="jurnal.php?delete=<?=$sql['id']?>">&#10006;</a>
        </td>
        </tr>
<?php }  ?>
</table>
        <?php for ($i = 1; $i <= $str_pag; $i++): ?>
        <a class="btn btn-default" style="text-align:center;" href="jurnal.php?page=<?=$i?>"><?=$i?></a>
    <?php endfor;?>
</div>

<?php
if($_POST['number_id']){backup();addEdit();}
if($_GET['delete']){backup();delete();}
if($_GET['update']){backup();update();}
mysql_close();
?>

</body>
</html>