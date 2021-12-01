<?php

$host = 'localhost';
$db = 'moviestar';
$user = 'root';
$pass = '';

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// habilitando os erros
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
