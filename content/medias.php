
<?php
//Contenu traduit
include('translation/mediasT.php');
?>

<div class="jumbotron">
	<div class="container">
	    <h2><?php echo $tabTexteMedias[0]; ?></h2>
	</div>
</div>

<div class="container">
	<div class="row" style="max-width:1025px;">
	    <?php

	    	$path = lireDonneeUrl("path", "");
	    	$extension = lireDonneeUrl("extension", "");
	    	$nomFichier = lireDonneeUrl("nomfichier", "");
			if (($path != "")&&($extension != "")&&($nomFichier != "")){
				downloadFile($path, $nomFichier, $extension);
			}

	    	// permet de supprimer un fichier
			$supprimerFichier = lireDonneePost("supprimerFichier", "");
			if ($supprimerFichier == 1) {
				supprimerFichierTranscode($connect);
			}

	    	// afficher la taille utiliser sur les 10Go disponible par compte
	    	$stockageUtiliser = stockageUtiliser($connect);

	    	// pourcentage de stocakge utilisé
	    	$pourcentageUtiliser = number_format(($stockageUtiliser/10)*100,1);


	   	?>	

	    <p><?php echo $tabTexteMedias[1]; ?></p>	
		<div id="barre_100">
			<div id="barre_valeur" style="width:<?php echo $pourcentageUtiliser; ?>%;">
				<h1><?php echo $pourcentageUtiliser; ?>%</h1>
			</div>
		</div>

			<!-- afficher la liste des taches avec l'etat "terminer" (fichier sauvegardé sur le serveur) -->

			<br/>
			<br/>
			<p><?php echo $tabTexteMedias[2]; ?></p>
			<div class="zoneTache">
				<?php
				// récupére les ids des messages de l'utilisateur
				$idUtilisateur = idUser($connect);	
	
				$req = $connect->prepare("SELECT * FROM tache WHERE idutilisateur = :idutilisateur AND etat = :etat");
				$req->execute(array('idutilisateur' => $idUtilisateur, 'etat' => "terminer"));

				// calcule le nbr d'id
				$res = $req->fetchAll();
				$nbrId = count($res);
			
				if ($nbrId != 0) {
					$req->execute(array('idutilisateur' => $idUtilisateur, 'etat' => "terminer"));
					while($row = $req->fetch(PDO::FETCH_ASSOC)) {
						?>
						<div class="tache">
							<?php
								$idTache = $row["idtache"];
								$nomFichier = $row["nomfichier"];
								$transcodeFormat = $row["transcodeformat"];
								$type = $row["type"];
								$taille = (number_format($row["taille"],3))*1000; // taille en Mo
								$date = $row["date"];
								$prix = $row["prix"];
								$uploadFormat = $row["uploadformat"];
								
								$path = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/transcode/".$nomFichier.".".$transcodeFormat;

								if($type == "video>audio"){
									$type = "audio";
								}


								?><div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[4]; ?></div><?php echo $idTache;?></div>
								<div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[5]; ?></div><?php echo $nomFichier.".".$transcodeFormat;?></div>
								<div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[6]; ?></div><?php echo $type;?></div>
								<div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[7]; ?></div><?php echo $taille."Mo";?></div>
								<div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[8]; ?></div><?php echo $date;?></div>
								<div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[9]; ?></div><?php echo $prix."€";?></div>
								<div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[10]; ?></div><?php echo $uploadFormat;?></div>
								<div class="elementTache">
									<div class='btnTelechargement'>
										<a style="display:block; width:100%; height:100%;" href="?page=medias&path=<?php echo $path."&extension=".$transcodeFormat."&nomfichier=".$nomFichier; ?>"></a>
									</div>
								</div>
								<div class="elementTache">
									<div class='btnSupprimer'>
										<a style="display:block; width:100%; height:100%;" data-toggle="modal" href="" data-target="#supprimerFichierModal" onclick="sendIdTache(<?php echo $idTache; ?>);"></a>
									</div>
								</div>
						</div>
					<?php }
				} else {
					echo $tabTexteMedias[11];
				}
				?>

			</div>

			<!-- afficher la liste des taches avec un transcodage en cour ou en attente  -->

			<br/>
			<br/>
			<p><?php echo $tabTexteMedias[3]; ?></p>
			<div class="zoneTache" style="max-width:650px">
				<?php
				// récupére les ids des messages de l'utilisateur
				$idUtilisateur = idUser($connect);	
	
				$req = $connect->prepare("SELECT * FROM tache WHERE idutilisateur = :idutilisateur AND (etat = :etat1 OR etat = :etat2 OR etat = :etat3 OR etat = :etat4 OR etat = :etat5)");
				$req->execute(array('idutilisateur' => $idUtilisateur, 'etat1' => "attente", 'etat2' => "encour", 'etat3' => "split", 'etat4' => "jointure", 'etat5' => "splitencour"));

				// calcule le nbr d'id
				$res = $req->fetchAll();
				$nbrId = count($res);
			
				if ($nbrId != 0) {
					$req->execute(array('idutilisateur' => $idUtilisateur, 'etat1' => "attente", 'etat2' => "encour", 'etat3' => "split" , 'etat4' => "jointure", 'etat5' => "splitencour"));
					while($row = $req->fetch(PDO::FETCH_ASSOC)) {
						?>
						<div class="tache">
							<?php
								$idTache = $row["idtache"];
								$nomFichier = $row["nomfichier"];
								$transcodeFormat = $row["transcodeformat"];
								$type = $row["type"];
								$taille = (number_format($row["taille"],3))*1000; // taille en Mo
								$date = $row["date"];
								$prix = $row["prix"];
								$uploadFormat = $row["uploadformat"];
								

								if($type == "video>audio"){
									$type = "audio";
								}


								?><div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[4]; ?></div><?php echo $idTache;?></div>
								<div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[5]; ?></div><?php echo $nomFichier.".".$transcodeFormat;?></div>
								<div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[6]; ?></div><?php echo $type;?></div>
								<div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[7]; ?></div><?php echo $taille."Mo";?></div>
								<div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[9]; ?></div><?php echo $prix."€";?></div>
								<div class="elementTache"><div class="titreTache"><?php echo $tabTexteMedias[10]; ?></div><?php echo $uploadFormat;?></div>

						</div>
					<?php }
				} else {
					echo $tabTexteMedias[12];
				}
				?>

			</div>


			<!-- Modal supprimer fichier -->
			<div class="modal fade" id="supprimerFichierModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
				    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel"><?php echo "Supprimer Fichier"; ?></h4>
					      </div>
					      <div class="modal-body">
					      	<form method="post" role="form" data-toggle="validator">
								<fieldset>
									<div class="form-group">
										<?php echo "Etes vous certain de vouloir supprimer le fichier?"; ?>
									</div>
								</fieldset>
								<div class="modal-footer">
							      	<input type="hidden" name="supprimerFichier" value="1"/>
							      	<input type="hidden" id="idTache" name="idTache" value=""/>
							        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo "Annuler"; ?></button>
							        <button type="submit" class="btn btn-primary"><?php echo "Valider"; ?></button>
							    </div>
							</form>
					      </div>
				    </div>
				  </div>
			</div>

	</div>
</div>



<script>
	// récupère l'id du contact associé au bouton et l'intègre au input hidden du form
	function sendIdTache(idTache) {

			var inputIdTache = document.getElementById('idTache');
			inputIdTache.value = idTache;

	}
</script>