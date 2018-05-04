<?php

  include 'config.php';
  include 'functions.php';

  if ( !isset($_GET['action']) )
    exit;

  $action['login']  = 0;
  $action['wall']   = 0;
  $action['logout'] = 1;
  $action['upload'] = 1;

  $op = $_GET['action'];

  if ( $action[$op] <= getPrivilegio() )
    echo call_user_func("action_$op");
  else
    echo 'acesso negado.';
