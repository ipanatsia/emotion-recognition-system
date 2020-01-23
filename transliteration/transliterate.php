<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Text</title>
</head>
<body>
<div>

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//ini_set( 'default_charset', 'UTF-8' );

//$send = $_POST['send'];
 if ($_SERVER['REQUEST_METHOD'] != 'POST'){
      $me = $_SERVER['PHP_SELF'];
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
<table>
<tr><td>??????????????:</td><td>
<textarea name="item" id="the-text-area" rows="5" cols="80"></textarea></td></tr>
</table> <br>
<input type="Submit" name="send" id="send" value="Evaluation" />
</form>


<?php
   } else {
   
	
    require_once 'utf8_to_ascii.php'; 


	$item = $_POST['item'];
        
	$item = trim($item);
    $item = str_replace('\\x', '&#', $item);
	$text = stripslashes(htmlspecialchars_decode($item));
    $out = utf8_to_ascii($text);
    echo $out;
	
}	
?>	

</div>
</body>
</html>