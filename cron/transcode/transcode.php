<?php

// inclut les fontions PHP
include($_SERVER["DOCUMENT_ROOT"]."/include/function.php");
include($_SERVER["DOCUMENT_ROOT"]."/include/functionTranscode.php");

// variable de connexion à la BDD utilisé pour les requetes SQL des fonctions
$connect = connecterBdd();
// ###### Tache Simultané #####

// ---- par worker ----
$nbrTacheParWorker = 2;

// ---- total ----
$nbrWorkers = nbrWorkers($connect);
$nbrTacheTotal = $nbrWorkers * $nbrTacheParWorker;


// ############################


// retourne le nombre de tache avec l'etat attente, encour et split; 
$nombreAttente = nombreEtat($connect, 'attente');
$nombreEnCour = nombreEtat($connect, 'encour');
$nombreSplit = nombreEtat($connect, 'split');
$nombreSplitEnCour = nombreEtat($connect, 'splitencour');

echo $nombreSplit;

// retourne le nombre de tache avec l'etat encour, pour le transcodage de tache en paralelle d'un meme worker
$nbrEtatEnCourWorker = nbrEtatEnCourWorker($connect, $idWorker);

/* Si le nombre de tache en attente est supérieur à 0 et encour est inférieur au nbrTacheTotal
et si le nombre de tache encour par worker est inférieur à nbrTacheParWorker ou si il y a une tache avec l'etat split
alors on execute le code de transcode. */
if (((($nombreAttente > 0) && ($nombreEncour < $nbrTacheTotal)) && ($nbrEtatEnCourWorker < $nbrTacheParWorker)) || ($nombreSplit >= 1)) {
	
	$res = null;

	if ($nombreSplit >= 1){

		// retroune les informations de la tache à transcoder en split
		$res = donneesTache($connect, '','split');

		$nbrSplit = $res["nbrsplit"];
		$nbrTranscodeSplit = $res["nbrtranscodesplit"];

		// retourne les informations du worker
		$res2 = donneesWorker($connect, $idWorker);
		$nbrSplitWorker = $res2["nbrsplits"];
		$nbrTranscodeSplitWorker = $res2["nbrtranscodesplit"];

	} else {
		// retourne les informations de la tache à transcoder en attente
		$res = donneesTache($connect, $idWorker, 'attente');
	}

	$etat = $res["etat"];
	$idTache = $res["idtache"];
	$type = $res["type"];
	$idUtilisateur = $res["idutilisateur"];
	$nomFichier = $res["nomfichier"];
	$uploadFormat = $res["uploadformat"];
	$transcodeFormat = $res["transcodeformat"];
	$taille = $res["taille"]; // en Mo
	$workerID = $res["idworker"];

	if (($nombreSplit >= 1)&&($nbrSplitWorker == 0)){
		// répartir les splits dans les files d'attentes
		ajoutSplitWorker($connect, $idTache);
	}

	echo $nbrTranscodeSplitWorker." ";
	echo $nbrSplitWorker." ";


	// ######### Traitement Split d'une Tache #########
	if (($nombreSplit >= 1)&&($nbrTranscodeSplitWorker < $nbrSplitWorker)){ // tache etat "split" et si le nombre de split traité est inférieur à la totalité

		// répartir les splits dans les files d'attentes
		//ajoutSplitWorker($connect, $idTache);


		//si pas de dossier /transcode/split alors on le crée
		$dossierSplitTranscode = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/transcode/split";
		if(!is_dir($dossierSplitTranscode)){
			mkdir($dossierSplitTranscode, 0777);
		}

		// récupère le numéro du split à traiter
    	$numSplitATraiter = numSplitATraiter($connect, $idWorker, $nbrTranscodeSplitWorker);

    	splitTraiter($connect, $idTache, $idWorker);

		// traitement du split de se worker	
		transcode($connect, $etat, $type, $nomFichier, $uploadFormat, $transcodeFormat, $idUtilisateur, $numSplitATraiter);
		


	// ######### Joindre les Splits d'une Tache #########
	} else if (($nombreSplit >= 1)&&($nbrTranscodeSplit == $nbrSplit)&&($nbrTranscodeSplit > 0)) { // tache etat "split" et si les splits de la totalité des workers on été traité

		changeEtat($connect, $idTache, 'jointure', '');

		// mettre le nbrsplit et nbrtranscodesplit des différents workers à 0
		nbrSplitWorkersZero($connect, $idTache, $nbrWorkers);

		tacheTraiter($connect, $workerID);

		// joindre tous les splits de la tache
		
		$ensembleSplit = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/transcode/split/".$nomFichier."0.".$transcodeFormat;
		for($i=1; $i<$nbrSplit; $i++){
			$videoSplit = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/transcode/split/".$nomFichier.$i.".".$transcodeFormat;
			$ensembleSplit = $ensembleSplit."|".$videoSplit;
		}

		echo $ensembleSplit;
			
		$cheminDestination = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/transcode/".$nomFichier.".".$transcodeFormat;
		$command = "ffmpeg -i \"concat:$ensembleSplit\" $cheminDestination";

		exec($command);

		// supprimer les splits des dossiers upload/split et transcode/split si aucun autre tache non "terminer" a le meme fichier
		supprimerSplitUploadEtTranscode($connect, $idTache);

		// supprimer le fichier dans upload si aucun autre tache non "terminer" a le meme fichier
		supprimerFichierUpload($connect, $idTache);

		// change l'etat en terminer avec la date
		$dateActuel = date('d/m/Y - H:i:s');
		changeEtat($connect, $idTache, 'terminer', $dateActuel);

		// envoi un email à l'utilisateur (permettre l'envoi de mail sur linux avec "sendmail")
		envoiMail($connect, $idUtilisateur, $nomFichier, $dateActuel);

	} else if (($workerID == $idWorker)&&($nombreSplit == 0)) { // tache etat "attente"

		// si pas de dossier transcode alors on le crée
		$dossierTranscode = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/transcode";
		if(!is_dir($dossierTranscode)){
		   	mkdir($dossierTranscode, 0777);
		}

		// récupère la durée du média
		$cheminMedia = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/upload/".$nomFichier.".".$uploadFormat;
		$ffmpeg = '/usr/local/bin/ffmpeg';
		$command = "$ffmpeg -i $cheminMedia 2>&1";
		exec($command, $output);

		foreach ($output as $v){
    		if(strstr($v, "Duration:")) {
    			$valeurs = explode("Duration:", $v);
    			$donnees = $valeurs[1];
    			$valeurs2 = explode(",", $donnees);
    			$duree = $valeurs2[0];
    		}
		}

		$duration = tempsEnSeconde($duree);

		// ajouter la durrée a la tache dans la base de données
		saveDureeMedia($connect, $idTache, $duration);

		// récupère le nombre de tache qui sont supérieur à la limite de split pour les workers précédent celui-ci
		//si supérieur à 0 alors on execute pas le code, pour éviter le traitement de split simultané.
		$limiteSplit = 600; // 10 min
		//$nbrSplitAutresWorker = nbrSplitAutresWorker($connect, $idWorker, $limiteSplit);

		echo $nbrSplitAutresWorker;

		// ######### Split le Fichier #########
		//if (($duration >= $limiteSplit)&&($nbrSplitAutresWorker == 0)){
		if ($duration >= $limiteSplit){
			// change l'etat à "split"
			changeEtat($connect, $idTache, 'split', '');

			//si pas de dossier /upload/split alors on le crée
			$dossierSplitUpload = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/upload/split";
			if(!is_dir($dossierSplitUpload)){
			   	mkdir($dossierSplitUpload, 0777);
			}
			
			// traitement ffmpeg pour spliter la video
			$tempsSplits = $duration/4;
			
			$nbrSplit = 4;

			// ajouter le nombre de split à la tache
			ajoutSplitTache($connect, $idTache, $nbrSplit);

			for ($i=0; $i<$nbrSplit; $i++){

				$temps1 = miseEnFormeTempsSplit($tempsSplits*$i);
				$temps2 = miseEnFormeTempsSplit($tempsSplits * ($i+1));

				//  ---------- Split ----------

				$videoSplit = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/upload/split/".$nomFichier.$i.".".$uploadFormat;
				$command = "$ffmpeg -i $cheminMedia -ss $temps1 -t $temps2 $videoSplit";
				exec($command);

			}


			/*if (($duration-($tempsSplits*4)) != 0){

				$nbrSplit = 5;

				$tempsDernierSplit = $duration-($tempsSplits*4);

				$temps1 = miseEnFormeTempsSplit($tempsSplits*4);
				$temps2 = miseEnFormeTempsSplit(($tempsSplits*4)+$tempsDernierSplit);

				$videoSplitFin = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/upload/split/".$nomFichier."4.".$uploadFormat;
				$command = "$ffmpeg -i $cheminMedia -ss $temps1 -t $temps2 $videoSplitFin";
				exec($command);

			}*/
			

		// ######### Traitement Tache sans Split #########
		} else {

			changeEtat($connect, $idTache, 'encour', '');

			//traitement du split de se worker	
			transcode($connect, $etat, $type, $nomFichier, $uploadFormat, $transcodeFormat, $idUtilisateur, "");

			// supprimer le fichier dans upload
			//supprimerFichierUpload($connect, $idTache);

			tacheTraiter($connect, $idWorker);

			// change l'etat en terminer avec la date
			$dateActuel = date('d/m/Y - H:i:s');
			changeEtat($connect, $idTache, 'terminer', $dateActuel);

			// envoi un email à l'utilisateur (permettre l'envoi de mail sur linux avec "sendmail")
			envoiMail($connect, $idUtilisateur, $nomFichier, $dateActuel);

		}

	}

}



?>
