<?php

$nbr = rand(); //génère un nombre aléatoire
$connexionUtilisateur = connexion($connect, $nbr);

//Gestion du langage
$lang = langage();

//Contenu traduit
include('translation/menuT.php');
?>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          

          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>

          </button>
            
              <a class="navbar-brand" href="index.php" style="display:block; width:100%; height:100%;">
                <div class="logo"></div>
              </a>
            
          
        </div>


        <!------- menu -------->
        <div id="navbar" class="navbar-collapse collapse">

          <ul class="nav navbar-nav">
              <li><a href="?page=home"><?php echo $tabTexteMenu[0]; ?></a></li>
              <li><a href="?page=upload"><?php echo $tabTexteMenu[1]; ?></a></li>
              <?php
              if ($connexionUtilisateur == $nbr){
              ?>
                <li><a href="?page=medias"><?php echo $tabTexteMenu[3]; ?></a></li>
                <li><a href="?page=compte"><?php echo $tabTexteMenu[4]; ?></a></li>
              <?php
              } else {
              ?>
                <li><a href="?page=createCompte"><?php echo $tabTexteMenu[2]; ?></a></li>
              <?php
              }
              ?>           
          </ul>

          <!------- form de connection -------->
          <form class="navbar-form navbar-right" method="post" action="?page=home">
            <?php
            if ($connexionUtilisateur == $nbr){ // Il y a connexion si la valeur est égale au nbr aléatoire
              $pseudo = lireDonneeSession("pseudo", "");
            ?>              
              <p style="color:white;"><?php echo $pseudo; ?>
            
              <form method="post">
                <input type="hidden" name="formdeconnexion" value="1"/>
                <button type="submit" class="btn btn-success"><?php echo $tabTexteMenu[8]; ?></button>
              </form>
              </p>
            <?php
            } else {
            ?>
              <div class="form-group">
                <input type="text" name="pseudo" placeholder="<?php echo $tabTexteMenu[5]; ?>" class="form-control">
              </div>
              <div class="form-group">
                <input type="password" name="motdepasse" placeholder="<?php echo $tabTexteMenu[6]; ?>" class="form-control">
              </div>
              <input type="hidden" name="formconnexion" value="1"/>
              <button type="submit" class="btn btn-success"><?php echo $tabTexteMenu[7]; ?></button>
            <?php
            }
            ?>
          </form>

          <!------- form selection langage -------->
          <form class="navbar-form navbar-right" method="post" style="margin-top:20px;">
              <select name="lang" onchange="this.form.submit();">
                <?php 
                if ($lang == "En") {
                ?>
                  <option><?php echo "En"; ?></option>
                  <option>Fr</option> 
                <?php
                } else {
                ?>
                  <option><?php echo "Fr"; ?></option>
                  <option>En</option> 
                <?php
                }
                ?>                                
                                                
              </select>
              <input type="hidden" name="formlang" value="1"/>
          </form>
          
        </div>
      </div>
</nav>