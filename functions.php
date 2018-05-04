<?php

  function getPrivilegio(){ return isset($_SESSION['priv']) ? $_SESSION['priv'] : 0; }
  function verificaAcesso($level)
  {
    if ( getPrivilegio() < $level )
    {
      global $root;

      if ( getPrivilegio() )
        echo "acesso negado";
      else
        echo "<script type=\"text/javascript\">location.href='$root/?op=login';</script>";

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
?>
var wall = document.getElementById('wall');
var numClenes = document.getElementsByClassName('clene').length;

var result = <?php echo json_encode($result); ?>;
var count = 0;

result.forEach(function(item, index){

  if ( index >= numClenes )
  {
    var clene = document.createElement('div');
    clene.className = 'clene';

    var imgClene = document.createElement('img');
    imgClene.className = 'imgClene';
    imgClene.src = 'imagem.php?id=' + result[count].ID;

    var nomeClene = document.createElement('div');
    nomeClene.className = 'nomeClene';
    nomeClene.innerHTML = item.nome;

    var uploader = document.createElement('div');
    uploader.className = 'nomeClene';
    uploader.innerHTML = 'Uploader: ' + result[count++].uploader;

    clene.appendChild(imgClene);
    clene.appendChild(nomeClene);
    clene.appendChild(uploader);

    if ( !wall.firstElementChild )
      wall.appendChild(clene);
    else
    {
      if ( wall.firstElementChild.className == 'cleneup'  )
        wall.insertBefore(clene, wall.firstElementChild.nextSibling);
      else
        wall.insertBefore(clene, wall.firstChild);
    }
  }
});

<?php
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
          return "document.getElementById('mensagem').innerHTML = 'senha incorreta'";
      }
      else
        return "document.getElementById('mensagem').innerHTML = 'usuário/email inexistente'";
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
    return "alert(". mysqli_error($conn) .")";
  }
