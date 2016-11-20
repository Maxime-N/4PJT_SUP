    <hr>
    <footer>
    	<div class="container">
        <div class="row">
          <div class="col-md-6">
            <?php
            switch ($lang) {
                case 'Fr':
                    $aPropos = "A propos"; 
                    break;
                
                case 'En':
                    $aPropos = "About"; 
                    break;
                
                default : echo " erreur 404"; break;
            }
            ?>
      	    <p><a href="?page=about"><?php echo $aPropos; ?></a></p>
          </div>
  	    </div>
  	  </div>
    </footer>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
