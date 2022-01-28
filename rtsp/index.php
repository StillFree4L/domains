<?php

$fp = @fopen("1synmaster.txt", "r");
$array = array();
if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
        $array[] = $buffer;
    }
    if (!feof($fp)) {
        echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
    }
    fclose($fp);
}
$pp=0;
$p=array();
//$searchs = explode(", ", 'Подарки, хобби, книги');
//foreach ($searchs as $search) {
foreach($array as $arr) {

    $pos = strpos($arr, 'подарки');
    if ($pos !== false) {
        $p[$pp] = $arr;
        $pp++;
    }
//}
  //  var_dump($p[$pp-1]);
}

$x='';
foreach($p as $ar) {
    $x.=str_replace("|", ", ", trim($ar)).', ';
}
var_dump($x);
/*
$row = "Дискошар Аккумуляторный с блютузом";
$znak = array(" ",".",",",":",";"," - ","!","?");
$fileSin = "1synmaster.txt";
$masSin = file($fileSin);
$countSin = count($masSin);
$len2 = strlen($row);
//var_dump(explode("|",$masSin[0]));

for ($t=0; $t< $countSin; $t++) {
    $sin = explode("|",$masSin[$t]);
    //var_dump($sin);

    $pos = strpos($row, $sin[0]);
    $len = strlen($sin[0]);
    if ($pos > 1) {
        if ( ($pos + $len) < $len2 )
            if ( (in_array($row[$pos + $len], $znak)) AND (in_array($row[$pos - 1], $znak)) ) {
                $r = rand(2, count($sin));
                $OldStr = $row[$pos-1].$sin[0].$row[$pos+$len];
                $NewStr = $row[$pos-1].$sin[$r-1].$row[$pos+$len];
                $row = str_replace($OldStr, $NewStr, $row);
            }
    }


}
var_dump($row);
*/
/*
function seokeywords($contentss,$symbol=5,$words=35){
    $contents = @preg_replace(array("'<[/!]*?[^<>]*?>'si","'([rn])[s]+'si","'&[a-z0-9]{1,6};'si","'( +)'si"),
        array("","1 "," "," "),strip_tags($contentss));
    $rearray = array("~","!","@","#","$","%","^","&","*","(",")","_","+",
        "`",'"',"№",";",":","?","-","=","|","\"","","/",
 "[","]","{","}","'",",",".","<",">","rn","n","t","«","»");


 $adjectivearray = array("ые","ое","ие","ий","ая","ый","ой","ми","ых","ее","ую","их","ым",
 "как","для","что","или","это","этих",
 "всех","вас","они","оно","еще","когда",
 "где","эта","лишь","уже","вам","нет",
 "если","надо","все","так","его","чем",
 "при","даже","мне","есть","только","очень",
 "сейчас","точно","обычно"
 );




 $contents = @str_replace($rearray," ",$contents);
 $keywordcache = @explode(" ",$contents);
 $rearray = array();


 foreach($keywordcache as $word){
 if(strlen($word)>=$symbol && !is_numeric($word)){
 $adjective = substr($word,-2);
 if(!in_array($adjective,$adjectivearray) && !in_array($word,$adjectivearray)){
 $rearray[$word] = (array_key_exists($word,$rearray)) ? ($rearray[$word] + 1) : 1;
 }
 }
 }


 @arsort($rearray);
 $keywordcache = @array_slice($rearray,0,$words);
 $keywords = "";

$i=0;
$k='';
 foreach($keywordcache as $word=>$count){
     if($i==0){$k=$word;}$i++;
 $keywords.= ",".$k.' для '.$word;
 }
    $keywords.=",".$contentss;

 return substr($keywords,1);
}
var_dump(seokeywords('Автомагнитола,Авто - мото,Автомобильная электроника,Автозвук,Автомагнитол
'));*/
?>