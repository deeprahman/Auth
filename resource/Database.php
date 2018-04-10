<?php

$username = "goro";
$dns = "mysql:host=localhost;dbname=register";
$password = "123456";

try {
    $db=new PDO($dns,$username,$password);
    
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to the register database";
} catch (PDOException $ex) {
    echo "Connection failed ". $ex->getMessage();
}
