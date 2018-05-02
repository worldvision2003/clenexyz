<?php

  function getPrivilegio(){ return isset($_SESSION['priv']) ? $_SESSION['priv'] : 0; }
  function verificaAcesso($level)
  {
    if ( getPrivilegio() > $level )
    {
      echo "acesso negado";
      return false;
    }
    else
    {
      return true;
    }
  }

  function action_login()
  {
    global $conn, $secret, $root;

    if ( isset($_POST['login']) && isset($_POST['senha']) )
    {
      $res = mysqli_query($conn, "SELECT ID FROM usuario WHERE login='$_POST[login]' OR email='$_POST[login]'");
      if ( ($userid = mysqli_fetch_array($res)[0]) )
      {
        $res = mysqli_query($conn, "SELECT * FROM usuario WHERE ID=$userid AND senha=MD5(CONCAT('$secret','$_POST[senha]'))");
        if ( ($dados = mysqli_fetch_assoc($res)) )
        {
          $_SESSION = $dados;
          echo "location.href='$root/'";
        }
        else
        {
          echo "document.getElementById('mensagem').innerHTML = 'senha incorreta'";
        }
      }
      else {
        echo "document.getElementById('mensagem').innerHTML = 'usuário/email inexistente'";
      }
    }
  }

  function action_logout()
  {
    global $root;
    session_destroy();
    echo "location.href='$root/?op=login';";
  }
