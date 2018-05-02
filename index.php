<?php include 'config.php'; ?>
<?php include 'functions.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>clene.xyz</title>
  <link rel="stylesheet" type="text/css" href="css/main.css" />

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
    }
  </script>
</head>

<body>
  <div id="header">
    <div style="font-size: 40px; margin: 35px;">clene.xyz</div>

    <div id="menu">
<?php
      $res = mysqli_query($conn, "SELECT * FROM menu WHERE ativo=1");
      while ( ($item = mysqli_fetch_assoc($res)) )
      {
        if ( (!getPrivilegio() && in_array($item['priv'], array(-1, 0))) ||
             ( getPrivilegio() >= $item['priv'] && $item['priv'] >= 0) )
          echo "\t<a class='item' href='/clene$item[target]'>$item[nome]</a>";
      }
?>
    </div>
  </div>
  <div id="content">
<?php

  $op = isset($_GET['op']) ? $_GET['op'] : 'wall';

  switch ( $op )
  {
    case 'perfil':

    if ( getPrivilegio() > 0 )
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
      echo "<script type=\"text/javascript\">rpc('rpc.php?action=logout', '');</script>";
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
      <input type="text" name="login" placeholder="login" />
      <input type="password" name="senha" placeholder="senha" />
      <input type="submit" value="Log in">
    </form>
<?php
    }
    break;

    case 'wall':
    if ( getPrivilegio() > 0 )
    {
?>
    <form action="rpc.php?action=upload" method="POST" enctype="multipart/form-data">
      <input type="file" name="clene" accept="image/*" />
      <input type="submit" value="Enviar" />
    </form>

<?php
    }
    break;
  }
?>
  </div>

  <script type="text/javascript">
    Array.from(document.getElementsByTagName('form')).forEach(function(item, index){
      item.onsubmit = function(){
        var formdata = new FormData();

        Array.from(item.children).forEach(function(item, index){
          if( item.tagName.toLowerCase() == 'input' )
            formdata.append(item.name, item.value);
        })

        rpc(item.action, formdata);
        return false;
      }
    });
  </script>
</body>
</html>
