<?php

  function mensagem($msg){ return "document.getElementById('mensagem').innerHTML = '". addslashes($msg) ."'"; }
  function getPrivilegio(){ return isset($_SESSION['priv']) ? $_SESSION['priv'] : 0; }
  function verificaAcesso($level)
  {
    global $root;
    if ( getPrivilegio() < $level )
    {
      if ( getPrivilegio() )
        echo "\tacesso negado\n";
      else
        echo "\t<script type=\"text/javascript\">location.href='$root/?op=login';</script>\n";

      return false;
    }
    else return true;
  }

  function action_wall()
  {
    global $conn;

    $res = mysqli_query($conn, "SELECT c.ID, c.nome, u.nome AS uploader FROM clene AS c INNER JOIN usuario AS u ON userid=u.ID WHERE ativo=1 ORDER BY ID DESC");
    if ( mysqli_num_rows($res) )
    {
      $result = array();
      while ( ($clene = mysqli_fetch_assoc($res)) )
        $result[] = $clene;

      echo json_encode($result);
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
          return "location.href='$root/'";
        }
        else
          return mensagem('senha incorreta');
      }
      else
        return mensagem('usuário/email inexistente');
    }
  }

  function action_logout()
  {
    global $root;
    session_destroy();
    return "location.href='$root/?op=login';";
  }

  function action_upload()
  {
    global $conn;

    $imagem = fopen($_FILES['clene']['tmp_name'], 'rb');
    $conteudo = addslashes(fread($imagem, $_FILES['clene']['size']));
    $tipo = $_FILES['clene']['type'];

    $res = mysqli_query($conn, "SELECT ID FROM clene WHERE MD5(imagem)='". md5(stripslashes($conteudo)) ."'");
    if ( mysqli_num_rows($res) )
      return "alert('error: clene has already been notified last year')";

    $nome = strlen($_POST['nome']) > 3 ? $_POST['nome'] : 'sem nome';

    mysqli_query($conn, "INSERT INTO clene (userid, data, nome, imagem, tipo, ativo) VALUES ($_SESSION[ID], NOW(), '$nome', '$conteudo', '$tipo', 1)");
    return "document.getElementsByClassName('imgClene')[0].src='imagem.php'";
  }

  function action_registrar()
  {
    global $conn, $root, $secret;

    if (  (empty($_POST['login']) || empty($_POST['nome']) ||
           empty($_POST['email']) || empty($_POST['senha'])) )
       return "alert('todos os campos devem ser preenchidos')";

    $res = mysqli_query($conn, "SELECT ID FROM usuario WHERE LOWER(login)=LOWER('$_POST[login]')");
    if ( mysqli_num_rows($res) )
      return mensagem('login já existe');

    $res = mysqli_query($conn, "SELECT ID FROM usuario WHERE LOWER(email)=LOWER('$_POST[email]')");
    if ( mysqli_num_rows($res) )
      return mensagem('email já existe');

    $res = mysqli_query($conn, "INSERT INTO usuario (login, nome, email, senha, token, criado, logado, priv) VALUES
    ('$_POST[login]', '$_POST[nome]', '$_POST[email]', MD5(CONCAT('$secret', '$_POST[senha]')), '', NOW(), NOW(), 1)");

    if ( mysqli_affected_rows($conn) )
      return "location.href='$root/?op=login';";
  }
