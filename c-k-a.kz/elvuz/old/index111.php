<?
include("include/bd.php");
//include ("include/auth.php"); 
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

<?
//if (isset($_REQUEST['auth'])) {
//$auth=$_REQUEST['auth'];
//$auth_pass=$_REQUEST['auth_pass'];

 //$query = "SELECT * FROM users WHERE login='$auth' AND password='$auth_pass'";
//  $res = mysql_query($query) or trigger_error(mysql_error().$query);
//  if ($row = mysql_fetch_assoc($res)) {
//    $user_id = $row['id'];
//    echo "<h1 align=center>Вы ввели корректный логин и пароль можете войти в систему</h1> ";
//echo "<form method=POST action=index1.php>";
//echo "<input type=text name=user_id value=$user_id><br>";
//echo "<input type=submit value=Войти><br>";
//echo "</form>";



 //   }
//} else {
?>

<form method="POST" >
<label>Логин:</label><input type="text" name="auth"><br>
<label>Пароль:</label><input type="password" name="auth_pass"><br>
<input type="submit" value=Войти><br>
</form>

<?
//}
?>
</body>
</html>
