
<?php

//Declare Database hostName and password
$hostName = "localhost";
$username = "id17712103_root";
$password = "1}3K}wnnNkgQS|&@";//Blank for XAMPP Application
$databaseName = "id17712103_socialsite";

//Syntax to connect to the database in XAMPP Application
$conn = mysqli_connect($hostName, $username, $password, $databaseName) or die("Unable to connect to the Database or found wrong credentials");
//By calling $conn as parameter in sql commands, we can call the database.

?>