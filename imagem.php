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

      if ( ($imgSrc = imagecreatefromstring($imagem['imagem'])) )
      {
        list($width, $height) = getimagesizefromstring($imagem['imagem']);

        if ( isset($_GET['w']) && isset($_GET['h']) )
        {
          $newWidth = intval($_GET['w']);
          $newHeight = intval($_GET['h']);
        }
        else if ( isset($_GET['thumb']) )
        {
          $newWidth = 380;
          $newHeight = 340;
        }

        $imgDst = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($imgDst, $imgSrc, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagepng($imgDst);
      }
      exit;
    }
  }

  header("Content-Type: image/jpeg");
  echo file_get_contents("foto.jpg");
