<?php
/*echo getcwd();
chdir("../");
echo getcwd();*/
//exec("php yii server/start");

/*$yourCommand='php yii server/start';
$command = $yourCommand . ' > /dev/null 2>&1 & echo $!'; 
exec($command, $output);
$pid = (int)$output[0];
$p=shell_exec("sudo lsof -nP -i | grep LISTEN");
var_dump($p);*/

?>
<form method="post" enctype="multipart/form-data">

            <label for="file">Filename:</label>
            <input type="file" name="file" id="file" />
            <br />
            <input type="submit" name="submit" value="Submit" />
        </form>

<?php var_dump($_FILES)?>
<?php 
define('FILES_ROOT', '/var/www/vhosts/v-4762.webspace/www/files.c-k-a.kz/');
$path = str_replace("https://files.c-k-a.kz//",FILES_ROOT,"https://files.c-k-a.kz//e9ce2//3ee2fd//6189074f80656.docx");
$path = str_replace("//","/",$path);

echo $path;
if(isset($_FILES['file']))
{
    $f = json_encode($_FILES['file']);
    echo $f."\n\n\n";
    move_uploaded_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"]);
    $bin_string = file_get_contents($_FILES["file"]["name"]);
    $bas=base64_encode($bin_string);
    $b='data:'.$_FILES["file"]["type"].';base64,'.$bas;

    echo $b;
    echo "<br/><a download='"."asfgas.pdf"."' href='".$b."'>Download</a>";
    $extension = explode('/', mime_content_type($b))[1];
    echo "<br/>".$extension."<br/>";
}
?>

Username:<br />
<input id="username" type="text"><button id="btnSetUsername">Set username</button>

<div id="chat" style="width:400px; height: 250px; overflow: scroll;"></div>

Message:<br />
<input id="message" type="text"><button id="btnSend">Send</button>
<div id="response" style="color:#D00"></div>
JS code for chat with jQuery:
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script>
    $(function() {
        var chat = new WebSocket('ws://localhost:1024');

        chat.onmessage = function(e) {
            $('#response').text('');

            var response = JSON.parse(e.data);
            if (response.type && response.type == 'chat') {
                $('#chat').append('<div><b>' + response.from + '</b>: ' + response.message + '</div>');
                $('#chat').scrollTop = $('#chat').height;
            } else if (response.message) {
                $('#response').text(response.message);
            }
        };
        chat.onopen = function(e) {
            $('#response').text("Connection established! Please, set your username.");
        };
        $('#btnSend').click(function() {
            if ($('#message').val()) {
                chat.send( JSON.stringify({'action' : 'chat', 'message' : $('#message').val()}) );
            } else {
                alert('Enter the message')
            }
        })

        $('#btnSetUsername').click(function() {
            if ($('#username').val()) {
                chat.send( JSON.stringify({'action' : 'setName', 'name' : $('#username').val()}) );
            } else {
                alert('Enter username')
            }
        })

        

    })
</script>