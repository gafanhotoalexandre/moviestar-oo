<?php

require_once './globals.php';
require_once './connection.php';
require_once './dao/UserDaoMysql.php';

$userDao = new UserDaoMysql($conn, BASE_URL);
$userData = $userDao->verifyToken(true);
