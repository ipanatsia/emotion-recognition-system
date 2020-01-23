<?php

session_start();	
echo $_SESSION["username"]."<br>". $_SESSION["password"]."<br>".$_SESSION["database"]."<br>".$_SESSION["url"];

//GRANT USAGE ON *.* TO 'ipanatsi'@'localhost';
//DROP USER 'ipanatsi'@'localhost';

?>