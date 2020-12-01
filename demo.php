<?php
  $dir = 'gallery';
  if (!is_dir($dir))
    exit ("direcotiy not found");
  $images = glob($dir.'/*.[jJ][pP][gG]');
  if ($images == array())
    exit ("direcotiy is empty");
  $images = array_map(function ($path) { return pathinfo($path)['filename'];}, $images);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
      integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <style type="text/css">
    .card-img-top {
      max-height: 160px;
      object-fit: cover;
    }
  </style>
  <title>Gallery</title>
</head>
<body>
  <div class="container mt-5">
    <div class="row bg-white gy-5">
    <?php foreach ($images as $image): ?>
      <div class="col col-10 col-md-3 p-4 col-sm-6">
        <div  class="card"
              data-toggle="modal" 
              data-target="#pop-up"
              data-img-name="<?=$image?>">
          <img src="generator.php?name=<?=$image?>&size=min" class="card-img-top">
          <div class="card-body text-center">
            <?=$image?>
          </div>
        </div>
      </div>
    <?php endforeach ?>
    </div>
  </div>
  <div class="modal fade" id="pop-up" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Выбор размера изображения</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row" id="gallery">
          <div class="col col-md-auto">
            <img src="/"  id="view-image" class="rounded w-100 mx-auto d-block">
          </div>
        </div>
      </div>
      <div id="pop-up-button-group" class="modal-footer d-flex justify-content-between"></div>
    </div>
    </div>
  </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script>
  function load_image(name, size) {
    $('#view-image').attr('src', 'generator.php?name=' + name + '&size=' + size);
  }

  $('#pop-up').on('show.bs.modal', (event) => {
    var card = $(event.relatedTarget)
    var recipient = card.data('imgName') 
    var modal = $(this);
    $.get( "get_sizes.php")
      .fail(() => {
        alert( "Fali to load size info" );
      })
      .done((data) => {
        if (data === '')
        {
          alert( "Fali to load size info" );
          return false;
        }
        var sizes = JSON.parse(data);
        var modalFooter = $('#pop-up-button-group');
        modalFooter.empty();
        for (var index in sizes)
        {
          var size = sizes[index];
          var button = $('\
            <button type="button" class="btn btn-secondary"></button>')
          .text(size.size_x + 'x' + size.size_y)
          .data('size', size.name)
          .click((obj) => {
            load_image(recipient, $(obj.target).data('size'));
          });
          if (index == '0')
            button.addClass('d-lg-none');
          else if (index == sizes.length - 1)
            button.addClass('d-none d-sm-block');
          else if (index == '1')
            load_image(recipient, size.name)
          button.appendTo(modalFooter);
        }
      })
  });
</script>
</html>
