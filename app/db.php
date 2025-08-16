<?php

$dsn = 'mysql:host=localhost;dbname=incident_lite;charset=utf8mb4';
$user = 'incidentapp'; $pass = 'strong_password';
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, $user, $pass, $options);