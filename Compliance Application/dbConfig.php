<?php

// Database configuration 
$dbHost     = "localhost"; 
$dbUsername = "root"; 
$dbPassword = ""; 
$dbName     = "crm"; 
/*
$dbHost = "localhost";
$dbUsername = "recoitcc_cwa";
$dbPassword = "Compliance@1234567890";
$dbName = "recoitcc_crm";*/
 
// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
 
// Check connection 
if ($db->connect_error) { 
    die("Connection failed: " . $db->connect_error); 
}
?>