
<?php

//Contenu traduit
include('translation/uploadT.php');

	
$etape = lireDonneeUrl("etape", ""); // pour redirection paypal (annulation ou paiement terminé)
if ($etape == ""){
	$etape = lireDonneePost("etape", "1");
}


	/*	1. sélectionner le fichier a convertir et demander le format de convertion
		2. afficher les informations sur la convertion (nom fichier, format de base, format de convertion, taille, prix de la conversion) et valider
		3. verifier si l'utilisateur est connecté si oui cela demande le paiement
		   sinon cela demande à l'utilisateur soit de se connecter soit de créer un compte
		   		-une fois connecté cela demande le paiement
				-si il souhaite créer un compte cela inclut le contenu de la page compte et le connecte automatiquement
		4. une fois le paiement effectuer cela upload le fichier et ajoute les informations dans la base de données et affiche une confirmation */

?>

<div class="jumbotron">
	<div class="container">
	    <h2>Upload</h2>
	</div>
</div>


<div class="container" id="container">

	<div class="imgetape" id="imgetape">
		<div class="elementetape" id="elementetape1"><?php echo $tabTexteUpload[37] ?></div>
		<div class="elementetape" id="elementetape2"><?php echo $tabTexteUpload[38] ?></div>
		<div class="elementetape" id="elementetape3"><?php echo $tabTexteUpload[39] ?></div>
		<div class="elementetape" id="elementetape4"><?php echo $tabTexteUpload[40] ?></div>
	</div>


	<?php
	if ($etape == 1){
	?>
		<style>
			#imgetape{background: url('../images/upload/uploadEtape1.png') no-repeat;}
			#elementetape1{color: #FFFFFF;}
			#elementetape2{color: #000000;}
			#elementetape3{color: #000000;}
			#elementetape4{color: #000000;}
		</style>

		<div class="row">

			<div class="col-sm-6 col-sm-offset-3" style="width:500px;">
				<h3><?php echo $tabTexteUpload[0]; ?></h3>
				<br/>
				<br/>
				<div>
					<fieldset>
						<div class="form-group">
							<button type="button" class="btn" style="background-color: #B0C4DE; color:white;"; onclick="$('#fileselect').click();"><?php echo $tabTexteUpload[1]; ?></button>
							<div id="divtxtfile"><?php echo $tabTexteUpload[2]; ?></div>
						</div>
						<div class="form-group">
								<label for="url"><?php echo $tabTexteUpload[3]; ?></label>
								<input type="text" id="urlfile" placeholder="<?php echo $tabTexteUpload[4]; ?>" style="width:300px;" value="" onkeydown="formatTranscode();"/>

							</div>
						<div class="form-group">
							<select id="formatselect">
								<option>format</option>																																			
							</select>
						</div>
					</fieldset>
					<br/>
					<button type="submit" class="btn btn-primary" onclick="valideFormFile();"><?php echo $tabTexteUpload[34]; ?></button>
				</div>
			</div>

		</div>


		<form method="post" name="formFile" id="formFile" role="form" data-toggle="validator" style="visibility:hidden" enctype="multipart/form-data">
				<input type="file" accept="video/*, audio/*" id="fileselect" name="fichierselectionne" onchange="fileSelected();"/>
				<input type="hidden" name="url" id="urlenvoyer" value=""/>
				<input type="hidden" name="formatselectionne" id="formatenvoyer" value=""/>
				<input type="hidden" name="etape" value="2"/>		
		</form>

	<?php } elseif($etape == 2) {

		$url = lireDonneePost("url", "");

		enregistrerFileSession("fichierselectionne", $url);

		$formatTranscode = lireDonneePost("formatselectionne", "");
		if($formatTranscode != ""){
			enregistrerSession("formatTranscode", $formatTranscode);
		} else {
			$formatTranscode = lireDonneeSession("formatTranscode", "");
		}

		?>

			<style>
				#imgetape{background: url('../images/upload/uploadEtape2.png') no-repeat;}
				#elementetape1{color: #000000;}
				#elementetape2{color: #FFFFFF;}
				#elementetape3{color: #000000;}
				#elementetape4{color: #000000;}
			</style>

			<div class="row">

				<div class="col-sm-6 col-sm-offset-3">
					<h3><?php echo $tabTexteUpload[5]; ?></h3>
					<br/>
					<br/>
					<p><?php 
						if((isset($_FILES['fichierselectionne']['name']))&&($url == "")){
							$mediaName = $_FILES['fichierselectionne']['name'];
						} elseif($url != "") {
							$path_parts = pathinfo($url);
							$mediaName = $path_parts['basename'];
						} else {
							$mediaName = lireDonneeSession("name", "");
						}

						echo $tabTexteUpload[6]." : ".$mediaName."<br/><br/>";
						echo $tabTexteUpload[7]." : ".$formatTranscode."<br/><br/>";

						/* le montant est calculé en fonction du format et du temps de la video,
						car le temps de trancodage va varier selon ces 2 parametres*/
						$pathTmpName = lireDonneeSession("tmp_name", "");

						// récupère la durée du média
						$ffmpeg = '/usr/local/bin/ffmpeg';
						$command = "$ffmpeg -i $pathTmpName 2>&1";
						exec($command, $output);
						foreach ($output as $v){
				    		if(strstr($v, "Duration:")) {
				    			$valeurs = explode("Duration:", $v);
				    			$donnees = $valeurs[1];
				    			$valeurs2 = explode(",", $donnees);
				    			$duree = $valeurs2[0];
				    		}
						}

						if ($duree != null) {
							$duration = tempsEnSeconde($duree);
							$timeMin = $duration/60;
						} else {
							$timeMin = 5;
						}
						

						$montant = montantTranscode($connect, $formatTranscode, $timeMin);

						echo $tabTexteUpload[8]." : ".$montant."€";

					?></p>
					<br/>
					<form method="post" role="form" id="form" data-toggle="validator" action="?page=upload">
						<fieldset>
							<div class="form-group">
								<label for="typeserveur"><?php echo $tabTexteUpload[9]." :"; ?></label>
								<select name="typeserveur" id="type1" onchange="montantFinal('<?php echo $tabTexteUpload[15]; ?>');">
									<option><?php echo $tabTexteUpload[12]; ?></option>
									<option><?php echo $tabTexteUpload[13]; ?></option>																																			
								</select>
							</div>
							<div class="form-group">
								<label for="priorite"><?php echo $tabTexteUpload[10]." :"; ?></label>
								<select name="priorite" id="type2" onchange="montantFinal('<?php echo $tabTexteUpload[15]; ?>');">
									<option><?php echo $tabTexteUpload[12]; ?></option>
									<option><?php echo $tabTexteUpload[13]; ?></option>																																			
								</select>
							</div>
							<div class="form-group">
								<label for="modepaiement"><?php echo $tabTexteUpload[11]." :"; ?></label>
								<select name="modepaiement">
									<option><?php echo $tabTexteUpload[14]; ?></option>
									<option>Paypal</option>																																			
								</select>
							</div>
							<p id="montantfinal"><?php echo $tabTexteUpload[15]." : ".$montant."€"; ?></p>
						</fieldset>
						<br/>
						<input type="hidden" id="montantbase" value="<?php echo $montant; ?>"/>
						<input type="hidden" name="montant" id="montant" value="<?php echo $montant; ?>"/>
						<input type="hidden" name="etape" id="etape" value="3"/>
						<input type="submit" class="btn btn-primary" value="<?php echo $tabTexteUpload[33]; ?>" onclick="retour(1);"/>
						<input type="submit" class="btn btn-primary" value="<?php echo $tabTexteUpload[34]; ?>"/>
					</form>
				</div>

			</div>
	<?php
	} elseif($etape == 3) { 

		$formregister = lireDonneePost("formregister", "");
		if ($formregister == 1){
			registerUser($connect);
		}

		$modepaiement = lireDonneePost("modepaiement", "");
		if($modepaiement != ""){
			enregistrerSession("modepaiement", $modepaiement); 
		} else {
			$modepaiement = lireDonneeSession("modepaiement", "");
		}

		$typeServeur = lireDonneePost("typeserveur", "");
		if($typeServeur != ""){
			enregistrerSession("typeserveur", $typeServeur);
		} else {
			$typeServeur = lireDonneeSession("typeserveur", "");
		}

		$priorite = lireDonneePost("priorite", "");
		if($priorite != ""){
			enregistrerSession("priorite", $priorite);
		} else {
			$priorite = lireDonneeSession("priorite", "");
		}

		$montant = lireDonneePost("montant", "");
		if($montant != ""){
			enregistrerSession("montant", $montant);
		} else {
			$montant = lireDonneeSession("montant", "");
		}

		?><style>
			#imgetape{background: url('../images/upload/uploadEtape3.png') no-repeat;}
			#elementetape1{color: #000000;}
			#elementetape2{color: #000000;}
			#elementetape3{color: #FFFFFF;}
			#elementetape4{color: #000000;}
		</style><?php


		// teste si l'utilisateur est connecté
		if ($connexionUtilisateur == $nbr){
			
			$tailleFichier = lireDonneeSession("size", "")/1000000000;
			//$stockageUtiliser = stockageUtiliser($connect, $idUtilisateur);
			$stockageUtiliser = stockageUtiliser($connect); // en Go
			$stockageUtiliserApresTranscode = $stockageUtiliser + $tailleFichier;
			$stockageTotal = 10;

			if ($stockageUtiliserApresTranscode < $stockageTotal){

				if ($modepaiement == $tabTexteUpload[14]){
				?>

					<div class="row">

						<div class="col-sm-6 col-sm-offset-3">
							<h3><?php echo $tabTexteUpload[16]; ?></h3>
							<br/>
							<br/>
							<form method="post" role="form" data-toggle="validator" action="?page=upload">
								<fieldset>
									<div class="form-group">
										<select name="typecarte">
												<option><?php echo $tabTexteUpload[17]; ?></option>
												<option>Visa</option>
												<option>MasterCard</option>																																			
										</select>
									</div>
									<div class="form-group">
										<input type="text" name="montantdupaiement" placeholder="<?php echo $tabTexteUpload[18]; ?>" value="<?php echo $montant ?>" class="form-control" data-error="" required style="width:100px;">
									</div>
									<div class="form-group">
										<input type="password" name="numerocb" placeholder="<?php echo $tabTexteUpload[19]; ?>" value="" class="form-control" data-error="" style="width:300px;">
										<input type="password" name="cryptcb" placeholder="Crypto" value="" class="form-control" data-error="" style="width:75px;">
									</div>
								</fieldset>
								<br/>
								<input type="hidden" name="etape" id="etape" value="4"/>
								<input type="submit" class="btn btn-primary" value="<?php echo $tabTexteUpload[33]; ?>"  onclick="retour(2);"/>
								<input type="submit" class="btn btn-primary" value="<?php echo $tabTexteUpload[34]; ?>"/>
							</form>
						</div>

					</div>

				<?php
				} elseif ($modepaiement == "Paypal") {
				?>

					<div class="row">

						<div class="col-sm-6 col-sm-offset-3">
							<h3><?php echo $tabTexteUpload[20]; ?></h3>
							<br/>
							<br/>
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="amount"  value="20.00" />
								<input type="hidden" name="shipping" value="20.00" />
								<input type="hidden" name="currency_code" value="EUR">
								<!--input name="return" type="hidden" value="http://votredomaine/paiementValide.php" />
								<input name="cancel_return" type="hidden" value="http://votredomaine/paiementAnnule.php" />
								<input name="notify_url" type="hidden" value="http://votredomaine/validationPaiement.php" /-->
								<input type="hidden" name="item_name" value="Transcode Media" />
								<input type="hidden" name="item_number" value="23974">
								<input type="hidden" name="quantity" value="1">
								<input type="hidden" name="hosted_button_id" value="PCBEVRZLQNX2W">
								<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal, le réflexe sécurité pour payer en ligne">
								<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
							</form>
							<br/>
							<br/>
							<form method="post" role="form" data-toggle="validator"  action="?page=upload">
								<input type="hidden" name="etape" id="etape" value=""/>
								<input type="submit" class="btn btn-primary" value="<?php echo $tabTexteUpload[33]; ?>"  onclick="retour(2);"/>
							</form>
							</form>
						</div>

					</div>
				<?php 
				}

			} else {
	
				echo "Vous n'avez pas assez d'espace disque pour faire le transcodage";

			}

		} else {
			?>
			<div class="row">

					<div class="col-sm-6 col-sm-offset-3">

						<!-- formulaire de connexion -->
						<h3><?php echo $tabTexteUpload[21]; ?></h3>
						<form method="post" action="?page=upload&etape=3">
				              <div class="form-group">
				                <input type="text" name="pseudo" placeholder="<?php echo $tabTexteUpload[22]; ?>" class="form-control">
				              </div>
				              <div class="form-group">
				                <input type="password" name="motdepasse" placeholder="<?php echo $tabTexteUpload[23]; ?>" class="form-control">
				              </div>
				              <input type="hidden" name="formconnexion" value="1"/>
				              <button type="submit" class="btn btn-success"><?php echo $tabTexteUpload[35]; ?></button>
			          	</form>

			          	<br/>
			          	<br/>

			          	<!-- formulaire pour créer un compte -->
			          	<?php
			          	if ($formregister != 1){
			          	?>
				          	<h3><?php echo $tabTexteUpload[24]; ?></h3>
							<form method="post" role="form" data-toggle="validator" action="?page=upload&etape=3">
								<fieldset>
									<legend></legend>
									<div class="form-group">
										<label for="firstname"><?php echo $tabTexteUpload[25]; ?></label>
										<input type="text" name="firstname" id="firstname" class="form-control" data-error="" required>
										<div class="help-block with-errors"></div>
									</div>
									<div class="form-group">
										<label for="lastname"><?php echo $tabTexteUpload[26]; ?></label>
										<input type="text" name="lastname" id="lastname" class="form-control" data-error="" required>
										<div class="help-block with-errors"></div>
									</div>
									<div class="form-group">
										<label for="email"><?php echo $tabTexteUpload[27]; ?></label>
										<input type="email" name="email" id="email"class="form-control" data-error="" required>
										<div class="help-block with-errors"></div>
									</div>
									<div class="form-group">
										<label for="username"><?php echo $tabTexteUpload[28]; ?></label>
										<input type="text" name="username" id="username" class="form-control" data-error="" required>
										<div class="help-block with-errors"></div>
									</div>
									<div class="form-group">
										<label for="matchingPasswords"><?php echo $tabTexteUpload[29]; ?></label>
										<input type="password" name="password" class="form-control" id="matchingPasswords" data-error="" data-minlength="6" required>
										<div class="help-block with-errors"></div>
									</div>
									<div class="form-group">
										<label for="confpassword"><?php echo $tabTexteUpload[30]; ?></label>
										<input type="password" name="confpassword" id="confpassword" class="form-control" data-match="#matchingPasswords" data-match-error="" data-error="" required>
										<div class="help-block with-errors"></div>
									</div>
								</fieldset>
								<input type="hidden" name="formregister" value="1"/>
								<button type="submit" class="btn btn-primary"><?php echo $tabTexteUpload[34]; ?></button>
							</form>
						<?php } ?>
			 		</div>

			</div>

		<?php
		}

	 } elseif($etape == 4) { 

		$typecarte = lireDonneePost("typecarte", "");

		$montant = lireDonneePost("montantdupaiement", "");

		// téléchargement
		uploadMedia($connect);

		// enregistrer la tache dans la basse de données
		addTache($connect);	

		?>

		<style>
			#imgetape{background: url('../images/upload/uploadEtape4.png') no-repeat;}
			#elementetape1{color: #000000;}
			#elementetape2{color: #000000;}
			#elementetape3{color: #000000;}
			#elementetape4{color: #FFFFFF;}
		</style>

		<div class="row">
			<div class="col-sm-6 col-sm-offset-3" style="width:600px;">
				<h3><?php echo $tabTexteUpload[31]; ?></h3>
				<br/>
				<p><?php echo $tabTexteUpload[32]; ?></p>
				<br/>
				<form method="post" role="form" data-toggle="validator" action="?page=upload">
					<button type="submit" name="etape" value="1" class="btn btn-primary"><?php echo $tabTexteUpload[36]; ?></button>
				</form>
			</div>
		</div>

	<?php }  ?>

</div>






<script>


function retour(etape){

		document.getElementById('etape').value = etape;

}

function montantFinal(text){

	var type1 = document.getElementById('type1').value;
	var type2 = document.getElementById('type2').value;
	var montantBase = parseFloat(document.getElementById('montantbase').value);

	var multiplicateur1;
	if((type1 == "Elevé")||(type1 == "High")){
		multiplicateur1 = 1.5;
	} else {
		multiplicateur1 = 1;
	}

	var multiplicateur2;
	if((type2 == "Elevé")||(type2 == "High")){
		multiplicateur2 = 1.5;
	} else {
		multiplicateur2 = 1;
	}

	var montantFinal = montantBase*multiplicateur1*multiplicateur2;

	document.getElementById('montantfinal').innerHTML = text+" : "+montantFinal.toFixed(2)+"€";
	document.getElementById('montant').value = montantFinal.toFixed(2);

}


// récupère les informations du fichier sélectionné
function fileSelected() {

	var valueFile = document.getElementById('fileselect').files[0];
	var fileName = valueFile.name;

	if (fileName != ""){
		document.getElementById('divtxtfile').innerHTML = fileName;
	}

	formatTranscode(fileName);

}

// récupération valeurs + validation form
function valideFormFile() {

	var urlFile = document.getElementById('urlfile').value;
	var formatSelect = document.getElementById('formatselect').value;

	document.getElementById('urlenvoyer').value = urlFile;
	document.getElementById('formatenvoyer').value = formatSelect;

	document.forms['formFile'].submit();

}

function formatTranscode(fileName) {

	if (fileName == null){
		var fileName = document.getElementById('urlfile').value;
	}

	var splitUpload = fileName.split(".");

	if (splitUpload != null){
		var tabFormat = null;

		var nbrSplitURLUpload = splitUpload.length;
		var formatUpload = splitUpload[nbrSplitURLUpload-1];

		if (formatUpload == "flv"){
			tabFormat = new Array("avi","mp3");
		} else if (formatUpload == "mp4"){
			tabFormat = new Array("flv","avi","mp3");
		} else if (formatUpload == "mp3"){
			tabFormat = new Array("wav","ogg");
		} else {
			tabFormat = new Array("aucun");
		}

	   	var listeFormat = document.getElementById('formatselect');
	   	listeFormat.length = null;
	    for(var i=0; i < tabFormat.length ; i++){
	    	console.log(listeFormat.length++);
		    listeFormat.options[listeFormat.length-1].text = tabFormat[i];
		}

	}
	
}
</script>