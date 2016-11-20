    <?php
    //Contenu traduit
    include('translation/homeUserT.php');
    $texteUpload = traduction($connect, "texteHome3", $lang);
    $texteMedias = traduction($connect, "texteHome4", $lang);
    $texteCompte = traduction($connect, "texteHome7", $lang);
    ?>

    <div class="jumbotron">
      <div class="container">
        <h2><?php echo $tabTexteHomeUser[0]; ?></h2>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <h3><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> <?php echo $tabTexteHomeUser[1]; ?></h3>
          <p><?php echo $texteCompte; ?></p>
          <p><a class="btn btn-default" href="?page=compte" role="button"><?php echo $tabTexteHomeUser[2]; ?></a></p>
        </div>      
        <div class="col-md-4">
          <h3><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> <?php echo $tabTexteHomeUser[3]; ?></h3>
          <p><?php echo $texteUpload; ?></p>
          <p><a class="btn btn-default" href="?page=upload" role="button"><?php echo $tabTexteHomeUser[4]; ?></a></p>
        </div>
        <div class="col-md-4">
          <h3><span class="glyphicon glyphicon-download" aria-hidden="true"></span> <?php echo $tabTexteHomeUser[5]; ?></h3>
          <p><?php echo $texteMedias; ?></p>
          <p><a class="btn btn-default" href="?page=medias" role="button"><?php echo $tabTexteHomeUser[6]; ?></a></p>
        </div>
      </div>
    </div>