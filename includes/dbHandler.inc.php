<?php

$serverName = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'loginsystem';

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if(!$conn){
    die("connection failed because: ".mysqli_connect_error());
}