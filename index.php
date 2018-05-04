<?php

  include 'config.php';
  include 'functions.php';

  $op = isset($_GET['op']) ? $_GET['op'] : 'wall';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <linsk rel="stylesheet" type="text/css" href="css/main.cssx" />
  <script type="text/javascript" src="js/main.js"></script>
  <title>clene.xyz</title>

  <style>
  body {
    background: #eee;
    line-height: 1.6vw;
  }

  a:link, a:visited, a:hover, a:active {
    color: #000;
    text-decoration: none;
  }

  #header {
    text-align: center;
    font-family: Verdana;

    padding: 3vw 3vw 1.5vw 3vw;
    margin: 1vw 1.5vw;
    background: #fff;
  }

  #content, #menu, #mensagem {
  }

  #content {
    margin-top: 1.5vw;
    width: 100%;
    height: 100%;

    font-family: Arial;
    font-size: 1.3vw;
  }

  #wall {
    white-space: normal;
    overflow-x: scroll;

    background-color: #ff00;
    position: relative;
    margin: 0 auto;
    width: 100%;
    text-align: center;
  }

  .item {
    margin: 1.2vw;
    font-size: 1.4vw;
  }

  .clene, .cleneup {
    background: #fff;
    width: 30%;
    height: 30.5vw;
    border: 0.1em solid #d0d0d0;
    padding: 0.5%;
    margin-right: 1vw;
    margin-bottom: 1vw;
    display: inline-block;
    vertical-align: middle;
    text-align: justify;
  }

  .imgClene {
    width: 100%;
    height: 26vw;
  }

  .nomeClene {
    margin-top: 0.4vw;
    margin-left: 0.5vw;
  }

  .uploadForm {
    /*display:;
    width: 25vw;
    color: #ff0000;*/
  }

  /*input {
    height: 0.5vw;
    width: 2vw;
  }*/

  </style>
  <script type="text/javascript">
    function rpc(action, data)
    {
      var req = new XMLHttpRequest();
      req.open('POST', action, true);
      req.send(data);

      req.onload = function(){
        if (req.status == 200 )
          eval(req.responseText);
      }

      return false;
    }

    function bind(item, formdata)
    {
      if( item.tagName.toLowerCase() == 'input' )
      if ( item.type.toLowerCase() != 'file' )
        formdata.append(item.name, item.value);
      else
        formdata.append(item.name, item.files[0]);
    }

    function updateWall()
    {
      var formdata = new FormData();
      formdata.append('limit', '30');
      formdata.append('offset', '0');

      rpc('rpc.php?action=wall', formdata);
    }
    <?php if($op === 'wall') echo "\n\tsetInterval(updateWall, 3000);"; ?>

  </script>
</head>

<body>
  <div id="header">
    <div style="font-size: 5vw; margin-bottom: 6vw;">clene.xyz</div>

    <div id="menu">
<?php
      $res = mysqli_query($conn, "SELECT * FROM menu WHERE ativo=1");
      while ( ($item = mysqli_fetch_assoc($res)) )
      {
        if ( (!getPrivilegio() && in_array($item['priv'], array(-1, 0))) ||
             ( getPrivilegio() >= $item['priv'] && $item['priv'] >= 0) )

          echo "\t<a class='item' href='$root$item[target]'>$item[nome]</a>\n";
      }
?>
    </div>
  </div>
  <div id="content">
<?php

  switch ( $op )
  {
    case 'ranking':
?>
    em construção
<?php
    break;

    case 'registrar':
    if ( getPrivilegio() > 0 )
    {
      header('Location: rpc.php?op=logout');
    }
    else
    {
?>
    <div id="mensagem"></div>
    <form action="rpc.php?action=registrar" method="POST">
      <table>
        <tr><td>Login</td><td><input type="text" name="login" placeholder="login" /></td></tr>
        <tr><td>Nome</td><td><input type="text" name="nome" placeholder="login" /></td></tr>
        <tr><td>E-mail</td><td><input type="text" name="email" placeholder="email" /></td></tr>
        <tr><td>Senha</td><td><input type="password" name="senha" placeholder="senha" /></td></tr>
        <tr><td><input type="submit" value="Log in"></td></tr>
      </table>
    </form>
<?php
    }
    break;

    case 'perfil':

    if ( verificaAcesso(1) )
    {
?>
    <table>
      <tr><td>Login:</td><td><input type="text" name="login" value="<?php echo $_SESSION['login']; ?>" disabled/></td></tr>
      <tr><td>Nome:</td><td><input type="text" name="nome" value="<?php echo $_SESSION['nome']; ?>" /></td></tr>
      <tr><td>E-mail:</td><td><input type="text" name="nome" value="<?php echo $_SESSION['email']; ?>" /></td></tr>
    </table>
<?php
    }
    break;

    case 'logout':
      echo "<script type=\"text/javascript\">rpc('rpc.php?action=logout', '');</script>\n";
    break;

    case 'login':

    if ( getPrivilegio() > 0 )
    {
      header('Location: rpc.php?op=logout');
    }
    else
    {
?>
    <div id="mensagem"></div>
    <form action="rpc.php?action=login" method="POST">
      <table>
        <tr><td>Login</td><td><input type="text" name="login" placeholder="login" /></td></tr>
        <tr><td>Senha</td><td><input type="password" name="senha" placeholder="senha" /></td></tr>
        <tr><td><input type="submit" value="Log in"></td></tr>
      </table>
    </form>
<?php
    }
    break;

    case 'wall':
    echo "\t<div id=\"wall\">\n";
    if ( getPrivilegio() > 0 )
    {
?>
    <div class="cleneup" style="position: relative; left: 0px; top: 0px;">
      <form action="rpc.php?action=upload" method="POST" enctype="multipart/form-data">
        <table class="uploadForm">
          <label for="fileClene">
            <img class="imgClene" src="imagem.php" />
          </label>
          <tr><td>Postar clene</td></tr>
          <tr><td><input type="file" name="clene" accept="image/*" id="fileClene" onchange="previewClene(event)" style="display: none;" /></td></tr>
          <tr><td>Título: <input type="text" name="nome" style="margin: auto 0.5vw auto 0.5vw;" /><input type="submit" value="Enviar" /></td></tr>
        </table>
      </form>
    </div>

<?php
    }

    echo "\t</div>\n";

    break;

    default:
    echo "operação inválida";
    break;
  }
?>
  </div>

  <script type="text/javascript">
    <?php if($op === 'wall') echo "window.onload = updateWall();\n\n"; ?>
    var recursiveBind = function(item, formdata) {
      if ( item.hasChildNodes() )
        Array.from(item.children).forEach(function(item, index){
          recursiveBind(item, formdata);
        });
      else bind(item, formdata);
    }

    Array.from(document.getElementsByTagName('form')).forEach(function(item, index){
      item.onsubmit = function(){
        var formdata = new FormData();
        Array.from(item.children).forEach(function(item, index){
          recursiveBind(item, formdata);
        });

        return rpc(item.action, formdata);
      }
    });
  </script>
</body>
</html>
