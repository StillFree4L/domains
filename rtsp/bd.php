<?php

$file = 'C:\Users\User pc\AppData\Roaming\ICCTV\ICCTVControl.cfg';
$localhost = 'test1.ru';
$login = 'root';
$pass = '';
$bd = 'arm';

function backup()
{
	$rand=rand(0,5);
	copy($GLOBALS["file"],$GLOBALS["file"].'.bak'.$rand);
}

function delete()
{
$sqls=mysql_query("SELECT number_id FROM jurnal WHERE id = '".$_GET['delete']."'");
$row=mysql_fetch_row($sqls);
  if($row)
  {
$sqls=mysql_query("DELETE FROM jurnal WHERE id = '".$_GET['delete']."'");

    $file = file($GLOBALS["file"]);
    for ($i=0; $i < count($file); $i++) { 
      if (preg_match('/< *plate[^>]*value *= *["\']?([^"\']*)/i', $file[$i], $value))
        {if ($value[1]==$row[0]) {
          $file[$i] = '';
        }}
    }
    file_put_contents($GLOBALS["file"], $file);
  }
}

function add($bd)
{
  $_POST['allStreams']==1 ? $allStreams='true' : $allStreams='false';
  if($_POST['allStreams'] != 1){
    mysql_query("INSERT INTO jurnal (fio,open_admission,close_admission,number_id, allStreams, stream0, stream1, stream2, stream3, stream4, stream5, stream6, stream8, ts)
    VALUES ('".$_POST['fio']."','".$_POST['open_admission']."','".$_POST['close_admission']."','".$_POST['number_id']."','".$_POST['allStreams']."','".$_POST['stream0']."',
    '".$_POST['stream1']."','".$_POST['stream2']."','".$_POST['stream3']."',
    '".$_POST['stream4']."','".$_POST['stream5']."',
    '".$_POST['stream6']."',
    '".$_POST['stream8']."','".time()."')");

$file = file($GLOBALS["file"]);
$file[6] .= '<plate value="'.$_POST['number_id'].'" allStreams="'.$allStreams.'" >';

if($_POST['stream0']!=0)$file[6] .= '<stream>Stream0</stream>';
if($_POST['stream1']!=0)$file[6] .= '<stream>Stream1</stream>';
if($_POST['stream2']!=0)$file[6] .= '<stream>Stream2</stream>';
if($_POST['stream3']!=0)$file[6] .= '<stream>Stream3</stream>';
if($_POST['stream4']!=0)$file[6] .= '<stream>Stream4</stream>';
if($_POST['stream5']!=0)$file[6] .= '<stream>Stream5</stream>';
if($_POST['stream6']!=0)$file[6] .= '<stream>Stream6</stream>';
if($_POST['stream8']!=0)$file[6] .= '<stream>Stream8</stream>';
$file[6] .= '</plate>'.PHP_EOL;
file_put_contents($GLOBALS["file"], $file);
} else 
{
mysql_query("INSERT INTO jurnal (fio,open_admission,close_admission,number_id, allStreams, stream0, stream1, stream2, stream3,
stream4, stream5, stream6, stream8, ts)
    VALUES ('".$_POST['fio']."','".$_POST['open_admission']."','".$_POST['close_admission']."','".$_POST['number_id']."',
'".$_POST['allStreams']."','1','1',
    '1','1','1','1','1','1','".time()."')");

$file = file($GLOBALS["file"]);
$file[6] .= '<plate value="'.$_POST['number_id'].'" allStreams="'.$allStreams.'" />'.PHP_EOL;
file_put_contents($GLOBALS["file"], $file);
}
}

function addEdit()
{
$sql=mysql_query("SELECT number_id FROM jurnal WHERE number_id = '".$_POST['number_id']."'");
$row=mysql_fetch_row($sql);
  if(!$row)add($bd);
  else {edit($bd);add($bd);}
}

function edit($bd)
{
    $sql = "DELETE FROM jurnal WHERE number_id = '".$_POST['number_id']."'";

    $file = file($GLOBALS["file"]);
    for ($i=0; $i < count($file); $i++) { 
      if (preg_match('/< *plate[^>]*value *= *["\']?([^"\']*)/i', $file[$i], $value))
        {if ($value[1]==$_POST['number_id']) {
          $file[$i] = '';
        }}
    }
    file_put_contents($GLOBALS["file"], $file);
	$res=mysql_query($sql);
}

function row()
{
$res=mysql_query("SELECT count(*) FROM jurnal");
$row=mysql_fetch_row($res);
  $count = $row[0];
  return $count;
}

function update()
{
  $cfg=parseCfg($GLOBALS["file"]);
  bd($cfg);
}

function bd($cfgs)
{
$vars=array();
	foreach ($cfgs as $cfg){
	if($cfg['number']!=null){
		array_push($vars,$cfg['number']);
		$sql=mysql_query("SELECT number_id FROM jurnal WHERE number_id = '".$cfg['number']."'");
		$row=mysql_fetch_row($sql);
			if(!$row){
$sql=mysql_query("INSERT INTO jurnal (number_id, allStreams, stream0, stream1, stream2, stream3, stream4, stream5, stream6, stream8, ts)
    VALUES ('".$cfg['number']."','".intval($cfg['allStreams'])."','".intval($cfg['stream0'])."','".intval($cfg['stream1'])."','".intval($cfg['stream2'])."',
'".intval($cfg['stream3'])."','".intval($cfg['stream4'])."','".intval($cfg['stream5'])."','".intval($cfg['stream6'])."',
'".intval($cfg['stream8'])."','".time()."')");
				}else
			{ 
$sql=mysql_query("update jurnal set number_id='".$cfg['number']."', allStreams='".intval($cfg['allStreams'])."',
stream0='".intval($cfg['stream0'])."', stream1='".intval($cfg['stream1'])."', stream2='".intval($cfg['stream2'])."',
stream3='".intval($cfg['stream3'])."', stream4='".intval($cfg['stream4'])."', stream5='".intval($cfg['stream5'])."',
stream6='".intval($cfg['stream6'])."', stream8='".intval($cfg['stream8'])."', ts='".time()."' 
WHERE number_id = '".$cfg['number']."'");
			}
		}
	}
	$var=implode(" AND number_id = ",$vars);
	$sql=mysql_query("DELETE FROM jurnal WHERE number_id = '".$var."'");
}

function parseCfg($file) {
  $cfg = array();
  $val='';
  $stream=array();
  $stream=array_fill(0, 8, '0');
  $i=0;
  foreach (file($file) as $line) {
    if (preg_match('/< *plate[^>]*value *= *["\']?([^"\']*)/i', $line, $value)) {
      if (preg_match('/< *plate[^>]*allStreams *= *["\']?([^"\']*)/i', $line, $allStreams)) {
        if($allStreams[1] == 'false'){
          $val=$value[1];
          continue; 
      }
      else{
        if($value[1] != ""){
          $data = array(
          'number' => $value[1],
          'allStreams' => '1',
          'stream0' => '1',
          'stream1' => '1',
          'stream2' => '1',
          'stream3' => '1',
          'stream4' => '1',
          'stream5' => '1',
          'stream6' => '1',
          'stream8' => '1',
        );
        } 
      }
    }
  }
      if (preg_match('#<[\s]*stream[\s]*>Stream([^<]*)<[\s]*/stream[\s]*>#i', $line, $streams))
      {
        if($streams[1]=='0') $i=0;
        if($streams[1]=='1') $i=1;
        if($streams[1]=='2') $i=2;
        if($streams[1]=='3') $i=3;
        if($streams[1]=='4') $i=4;
        if($streams[1]=='5') $i=5;
        if($streams[1]=='6') $i=6;
        if($streams[1]=='8') $i=8;
        $stream[$i] = '1';
      }
      if (preg_match('#([^<]*)<[\s]*/plate[\s]*>#i', $line, $streams))
      {
          $data = array(
          'number' => $val,
          'allStreams' => '0',
          'stream0' => $stream[0],
          'stream1' => $stream[1],
          'stream2' => $stream[2],
          'stream3' => $stream[3],
          'stream4' => $stream[4],
          'stream5' => $stream[5],
          'stream6' => $stream[6],
          'stream8' => $stream[8],
        ); 
        
      }
        $cfg[] = $data;
  }
  return $cfg;
}

/*
CREATE DATABASE arm DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
DROP DATABASE arm;

CREATE TABLE jurnal(
id INT NOT NULL AUTO_INCREMENT,
number_id varchar(40) NOT NULL,
allStreams int(11) NOT NULL,
stream0 int(11) NOT NULL default '0',
stream1 int(11) NOT NULL default '0',
stream2 int(11) NOT NULL default '0',
stream3 int(11) NOT NULL default '0',
stream4 int(11) NOT NULL default '0',
stream5 int(11) NOT NULL default '0',
stream6 int(11) NOT NULL default '0',
stream7 int(11) NOT NULL default '0',
stream8 int(11) NOT NULL default '0',
ts int(11) NOT NULL default '0',
PRIMARY KEY ( id ));

ALTER TABLE `jurnal`
ADD UNIQUE INDEX `ix_number` (`number_id`);
*/
 ?>