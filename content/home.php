    <?php
    //Contenu traduit
    include('translation/homeT.php');
    $textePresentation = traduction($connect, "texteHome1", $lang);
    $texteCreationCompte = traduction($connect, "texteHome2", $lang);
    $texteUpload = traduction($connect, "texteHome3", $lang);
    $texteMedias = traduction($connect, "texteHome4", $lang);
    $texteTarifs = traduction($connect, "texteHome5", $lang);
    $texteStockage = traduction($connect, "texteHome6", $lang);
    ?>

    <div class="jumbotron">
      <div class="container">
        <h1><?php echo $tabTexteHome[0] ?></h1>
        <p><?php echo $textePresentation; ?></p> 
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
  	    <h2><?php echo $tabTexteHome[1] ?></h2>
        <div class="col-md-4">
          <h3><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> <?php echo $tabTexteHome[2] ?></h3>
          <p><?php echo $texteCreationCompte; ?></p>
          <p><a class="btn btn-default" href="?page=createCompte" role="button"><?php echo $tabTexteHome[3] ?></a></p>
        </div>
        <div class="col-md-4">
          <h3><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> <?php echo $tabTexteHome[4] ?></h3>
          <p><?php echo $texteUpload; ?></p>
          <p><a class="btn btn-default" href="?page=upload" role="button"><?php echo $tabTexteHome[5] ?></a></p>
        </div>
        <div class="col-md-4">
          <h3><span class="glyphicon glyphicon-download" aria-hidden="true"></span> <?php echo $tabTexteHome[6] ?></h3>
          <p><?php echo $texteMedias; ?></p>
        </div>
      </div>
    </div>
    <hr>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
  	      <h2><span class="glyphicon glyphicon-euro" aria-hidden="true"></span> <?php echo $tabTexteHome[7] ?></h2>
  	      <p><?php echo $texteTarifs; ?></p>
  	    </div>
  	    <div class="col-md-6">
          <h2><span class="glyphicon glyphicon-hdd" aria-hidden="true"></span> <?php echo $tabTexteHome[8] ?></h2>
  	      <p><?php echo $texteStockage; ?></p>
        </div>
      </div>
    </div>