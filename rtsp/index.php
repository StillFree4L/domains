<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8 />
	<title>ARM OPERATOR VIZIR-ST</title>
  	<link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
	</head>
<body>
<?php
include_once("config.php");
/*for($i = 0;$i<count($rstpName);$i++)
{
exec("ffmpeg -v info -i rtsp://".$rstpName[$i]." -c:v copy -c:a copy -bufsize 1835k -pix_fmt yuv420p -flags -global_header -hls_time 10 -hls_list_size 6 -hls_wrap 10 -start_number 1 /var/www/html/rtsp/tmp/".$streamName[$i].".m3u8 &");
}*/
 ?>
  <br><h2 align="center">ARM OPERATOR VIZIR-ST</h2><br>
  
  <table class="table">
  	<tbody>
  		<?php for($i = 0;$i<count($streamName);$i++){ ?>
  		<?php if($i==0 or $i==4) echo "<tr>"; ?>
  			<td style="padding:0px;">
  				<video-js <?= 'id="my_video_'.$i.'"' ?> class="vjs-default-skin" controls preload="auto" width="340" height="180">
  				<source <?= 'src="tmp/'.$streamName[$i].'.m3u8"' ?> type="application/x-mpegURL">
  				</video-js>
  			</td>
  		<?php if($i==3 or $i==7) echo "</tr>"; ?>
  		<?php } ?>
  		<tr>
  		<?php for($i = 0;$i<count($buttonName);$i++){ ?>
  			<td align="center"><button type="button" class="btn btn-success"><?= $buttonName[$i] ?></button></td>
  		<?php } ?>
  		</tr>
  	</tbody>
  </table>
  
  <script src="https://unpkg.com/video.js/dist/video.js"></script>
  <script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>
  
  <script>
  for (var i = 0; i <8; i++) {
    var player = videojs('my_video_'+i);
    }
  </script>
  
</body>
</html>
