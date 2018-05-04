<?php

  ini_set('display_errors', 'on');
  error_reporting(E_ALL);

  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', 'banana');
  define('DB_NAME', 'clene');

  $secret = 'banana';
  $root = "/clene";

  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS) or die('falha de conexao @' . DB_HOST);
  mysqli_select_db($conn, DB_NAME) or die('nao pude conectar ao db ' . DB_NAME);

  session_start();
