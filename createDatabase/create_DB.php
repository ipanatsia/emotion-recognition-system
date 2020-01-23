<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);

echo '<style type="text/css">
.message {
	position: relative;
	margin-top: 2%;
	margin-bottom: 2%;
	text-align: center;
	padding-left: 10%;
	padding-right: 10%;
}
#border {
	width: 30%;
	height: auto;
	position: absolute;
	left: 38%;
	border: 5px solid;
	border-radius: 30% 0%;
	top: 20%;
}
</style>' ;



echo '<div id="border">';
//recieve information from form

$USERNAME = $_POST["username"];  //database username
$PASSWORD = $_POST["password"];  //database password
$DATABASE = $_POST["databaseName"]; //database name
$URL = $_POST["URL"];    //database location

$_SESSION["username"]=$USERNAME;
$_SESSION["password"]=$PASSWORD;
$_SESSION["database"]=$DATABASE;
$_SESSION["url"]=$URL;

//create an empty DB
$con = mysql_connect("localhost","root", "");
if (mysql_query("CREATE DATABASE IF NOT EXISTS $DATABASE") === TRUE) {
    echo "<p class='message'> Database created successfully </p>";
} else {
    echo "<p class='message'> Error creating database: </p>";
}

//retrieve database	from the form
$arxeio = str_replace(" ", "", $_FILES["file"]["name"]);
$filename = dirname(__DIR__)."/ergasia/database/".$arxeio;
if (file_exists(dirname(__DIR__)."/ergasia/database/" . $arxeio))
  {
  echo "<p class='message'>".$_FILES["file"]["name"] . " already exists. </p>";
}
else
  {
  move_uploaded_file($_FILES["file"]["tmp_name"],
  dirname(__DIR__)."/ergasia/database/". $arxeio);
  echo "<p class='message'>Stored in: " . "ergasia/database/".$arxeio."</p>";
}

//unzip database
$zip = new ZipArchive;
$res = $zip->open($filename);
if ($res === TRUE) {
  $zip->extractTo(dirname(__DIR__)."/ergasia/database/");
  $filename = $zip->getNameIndex(0);
  $zip->close();
}
else { echo "<p class='message'>Problem with the unzip of database, please try again </p>"; }

//create user
mysql_query("CREATE USER '".$USERNAME."'@'".$URL."' IDENTIFIED BY '".$PASSWORD."'");
mysql_query("GRANT ALL PRIVILEGES ON *.* TO '".$USERNAME."'@'".$URL."' WITH GRANT OPTION");
mysql_close($con);


//connect with DB
$conn = mysql_connect($URL,$USERNAME,$PASSWORD,$DATABASE);
if (mysql_error()) {
    die("<p class='message'> Connection failed: " . mysql_error()."</p>");
} 
else {echo "<p class='message'> Connected to database </p>";}
$sql=mysql_select_db($DATABASE, $conn) or die('<p class="message"> Cannot connect to database. </p>');
mysql_query("SET NAMES `utf8`");
	  
//import database
// Name of the file
$filename = dirname(__DIR__)."/ergasia/database/".$filename;
// Temporary variable, used to store current query
$templine = '';
// Read in entire file
$lines = file($filename);
// Loop through each line
foreach ($lines as $line)
	{
	// Skip it if it's a comment
	if (substr($line, 0, 2) == '--' || $line == '')
		continue;

	// Add this line to the current segment
	$templine .= $line;
	// If it has a semicolon at the end, it's the end of the query
	if (substr(trim($line), -1, 1) == ';')
		{
			// Perform the query
			mysql_query($templine) or print('Error performing query ' . mysql_error() . '<br /><br />');
			// Reset temp variable to empty
			$templine = '';
	}
}
mysql_close($conn);
echo '<p class="message"><a href="main.php"> <img src="https://image.freepik.com/free-icon/arrow-full-shape-pointing-to-right-direction_318-32063.png" width="38" height="32" alt=""/></a></p>';

echo "<p class='message'>  Continue to emotional weight calculation </p></div>";

?>