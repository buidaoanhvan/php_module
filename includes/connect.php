<?php
if(!defined('_BLOCK_DEFAULT')) header("Location: ./?module=errors&action=404");

try {
    $conn = new PDO("mysql:host="._servername.";dbname="._database.";charset=utf8", _username, _password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (Exception $e){
    require_once 'modules/errors/404.php';
    die();
}

