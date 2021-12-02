<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <meta http-equiv="Refresh" content="3" />
    <title>ARM WHITE LIST VIZIR-ST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    </head>
<body>

<?php
$path ='c:\\Distr\ICCTV_v1.0.7.5lite\tmp\Stream0\recognition.txt';
$img='c:\\Distr\ICCTV_v1.0.7.5lite\tmp\Stream0';
$myArray = array();

foreach (file($path) as $line) {
$myArray[] = explode(' ', $line);
}

?>

<?php
$file = __DIR__ . '/test.jpg';
$path = pathinfo($file);
$ext = mb_strtolower($path['extension']);
 
if (in_array($ext, array('jpeg', 'jpg', 'gif', 'png', 'webp', 'svg'))) {       
    if ($ext == 'svg') {
        $img = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($file));
    } else {
        $size = getimagesize($file);
        $img = 'data:' . $size['mime'] . ';base64,' . base64_encode(file_get_contents($file));
    }
}
?>
 
<img src="<?php echo $img; ?>">




<table>
	<tr>


		<td style="text-align: center; padding-left: 10%;">
			<img src="<?=$img.'\ '.trim($myArray[count($myArray)-2][7])?>" 
 width="189" height="255" alt="lorem"><br>
  <?=$myArray[count($myArray)-2][2]." - ".$myArray[count($myArray)-2][4]?>
		</td>

	</tr>
</table>

<input type="file" name="imageFiles[]" multiple="true" value="">

</body>
</html>