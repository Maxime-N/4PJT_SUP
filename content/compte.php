
<?php
//Contenu traduit
include('translation/compteT.php');
?>

<div class="jumbotron">
	<div class="container">
	    <h2><?php echo $tabTexteCompte[0]; ?></h2>
	</div>
</div>

<div class="container">
	<div class="row">
	    <?php

			$updateDataUser = lireDonneePost("updateDataUser", "");
			if ($updateDataUser == 1){
				updateUser($connect);
			}
			
			// ----- affichage des données utilisateur -----
			
			$res = donneesUser($connect);

			$nom = $res["nom"];
			$prenom = $res["prenom"];
			$email = $res["email"];
			$username = $res["pseudo"];
			$userId = $res["idutilisateur"];


			

			// affichage des informations utilisateur
			?>
			<div class="profiluser">

				<p><?php
					echo $tabTexteCompte[1]." : ".$nom."<br/><br/>";
					echo $tabTexteCompte[2]." : ".$prenom."<br/><br/>";
					echo $tabTexteCompte[3]." : ".$email."<br/><br/>";
					echo $tabTexteCompte[4]." : ".$username; 
				?></p>


				<br/>
				<br/>


				<div class="col-sm-3">
			
					<!-- bouton modification mot de passe -->
					<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#passwordModal">
					 	<?php echo $tabTexteCompte[5]; ?>
					</button>

					<br/>
					<br/>

					<!-- bouton modification Nom Utilisateur/E-mail -->
					<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#generalDataModal">
					 	<?php echo $tabTexteCompte[9]; ?>
					</button>
								
					
					<!-- Modal motdepasse-->
					<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					  	<div class="modal-dialog">
					    	<div class="modal-content">
						      	<div class="modal-header">
						        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        	<h4 class="modal-title" id="myModalLabel"><?php echo $tabTexteCompte[5]; ?></h4>
						      	</div>
						      	<div class="modal-body">
						      		<form method="post" role="form" data-toggle="validator">
										<fieldset>
											<div class="form-group">
												<input type="password" name="oldPassword" placeholder="<?php echo $tabTexteCompte[6]; ?>" class="form-control">
											</div>
											<div class="form-group">
												<input type="password" name="newPassword" placeholder="<?php echo $tabTexteCompte[7]; ?>" class="form-control" id="matchingNewPasswords" data-error="<?php echo ""; ?>" data-minlength="6">
												<div class="help-block with-errors"></div>
											</div>
											<div class="form-group">
												<input type="password" name="confNewPassword" placeholder="<?php echo $tabTexteCompte[8]; ?>" class="form-control" data-match="#matchingNewPasswords" data-match-error="<?php echo ""; ?>" data-error="<?php echo ""; ?>">
												<div class="help-block with-errors"></div>
											</div>
										</fieldset>
										<div class="modal-footer">
								      		<input type="hidden" name="updateDataUser" value="1"/>
								        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $tabTexteCompte[12]; ?></button>
								        	<button type="submit" class="btn btn-primary"><?php echo $tabTexteCompte[13]; ?></button>
								      	</div>
									</form>
						      	</div>
					    	</div>
					  	</div>
					</div>

					<!-- Modal nom/e-mail/nom utilisateur-->
					<div class="modal fade" id="generalDataModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					  	<div class="modal-dialog">
					    	<div class="modal-content">
						      	<div class="modal-header">
						        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        	<h4 class="modal-title" id="myModalLabel"><?php echo $tabTexteCompte[9]; ?></h4>
						      	</div>
						      	<div class="modal-body">
						      		<form method="post" role="form" data-toggle="validator">
										<fieldset>
											<div class="form-group">
												<input type="email" name="newEmail" placeholder="<?php echo $tabTexteCompte[10]; ?>" class="form-control">
											</div>
											<hr />
											<div class="form-group">
												<input type="text" name="newUsername" placeholder="<?php echo $tabTexteCompte[11]; ?>" class="form-control">
											</div>
										</fieldset>
										<div class="modal-footer">
								      		<input type="hidden" name="updateDataUser" value="1"/>
								        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $tabTexteCompte[12]; ?></button>
								        	<button type="submit" class="btn btn-primary"><?php echo $tabTexteCompte[13]; ?></button>
								      	</div>
									</form>
						      	</div>
					    	</div>
					  	</div>
					</div>
				</div>
			</div>	
	</div>
</div>