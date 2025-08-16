<?php
$local = __DIR__ . '/../config/secret.local.php';
require file_exists($local) ? $local : __DIR__ . '/../config/secret.example.php';

$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO(DB_DSN, DB_USER, DB_PASS, $options);
