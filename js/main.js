function createClene(id, nome, user)
{
  var clene = document.createElement('div');
  clene.className = 'clene';

  var imgClene = document.createElement('img');
  imgClene.className = 'imgClene';
  imgClene.src = 'imagem.php?thumb&id=' + id;

  var nomeClene = document.createElement('div');
  nomeClene.className = 'nomeClene';
  nomeClene.innerHTML = nome;

  var uploader = document.createElement('div');
  uploader.className = 'nomeClene';
  uploader.innerHTML = 'Uploader: ' + user;

  clene.appendChild(imgClene);
  clene.appendChild(nomeClene);
  clene.appendChild(uploader);
  return clene;
}

function atualizaClenes(result)
{
  var wall = document.getElementById('wall');
  var numClenes = document.getElementsByClassName('clene').length;
  var clenes = [];
  var count = 0;

  result.forEach(function(item, index){
    if ( index >= numClenes )
      clenes.push(createClene(result[count].ID, result[count].nome, result[count++].uploader));
  });

  clenes.reverse();
  for ( var i=0; i<clenes.length; i++ )
  {
    if ( !wall.firstElementChild )
      wall.appendChild(clenes[i]);
    else
    {
      if ( wall.firstElementChild.className == 'cleneup'  )
        wall.insertBefore(clenes[i], wall.firstElementChild.nextSibling);
      else
        wall.insertBefore(clenes[i], wall.firstChild);

    }
  }
}

function previewClene(event)
{
  var img = document.getElementsByClassName('imgClene')[0];
  img.src = URL.createObjectURL(event.target.files[0]);
  img.className = 'imgClene';
}
