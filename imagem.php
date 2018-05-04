<?php

  include 'config.php';

  if ( isset($_GET['id']) )
  {
    $id = intval($_GET['id']);
    $res = mysqli_query($conn, "SELECT imagem, tipo FROM clene WHERE ID=$id");

    if ( mysqli_num_rows($res) )
    {
      $imagem = mysqli_fetch_assoc($res);
      header("Content-Type: $imagem[tipo]");
      echo $imagem['imagem'];
      exit;
    }
  }

  header("Content-Type: image/jpeg");
  echo file_get_contents("foto.jpg");
