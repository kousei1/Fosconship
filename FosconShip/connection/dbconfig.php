<?php

$host = "localhost";
$dbUser = "root";
$dbpass = "";
$dbname = "fasconshipdb";

$connection = new mysqli($host, $dbUser, $dbpass, $dbname);
if ($connection->connect_error)
{
    die('connection failed :'.$connection->connect_error );
}


?>