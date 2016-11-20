<?php

// --------- Connexion base de données -------
function connecterBdd() {
    $login = "root";
	$password = "Supinfo01";
	return new PDO('mysql:host=localhost;dbname=bddtranscode', $login, $password);
}

// --------- récupérer un Get -------
function lireDonneeUrl($nomDonnee, $valDefaut="") {
    if ( isset($_GET[$nomDonnee]) ) { //test si le champ n'est pas vide pour retourner la valeur
        $val = $_GET[$nomDonnee];
    }
    else { //si il est vide alors la valeur retourné sera celle que l'on aura définis par défaut
        $val = $valDefaut;
    }
    return $val;
}

// --------- récupérer un Post -------
function lireDonneePost($nomDonnee, $valDefaut="") {
    if ( isset($_POST[$nomDonnee]) ) {
        $val = $_POST[$nomDonnee];
    }
    else {
        $val = $valDefaut;
    }
    return $val;
}

// --------- Enregistrer un utilisateur -------
function registerUser($connect){
	// récupération des champs du formulaire
	$firstname = lireDonneePost("firstname", "");
	$lastname = lireDonneePost("lastname", "");
	$email = lireDonneePost("email", "");
	$username = lireDonneePost("username", "");
	$password = lireDonneePost("password", "");
	$confpassword = lireDonneePost("confpassword", "");
	
	// test si tous les champs sont remplis et si les motsdepasse sont identique
	if (($firstname != "")&&($lastname != "")&&($email != "")&&($username != "")&&($password != "")&&($confpassword == $password)){			
		// récupère le nombres de ligne identique au pseudo rentré pour vérifier que le pseudo n'existe pas déjà
		$req = $connect->prepare("SELECT * FROM utilisateur WHERE pseudo = :pseudo");
		$req->execute(array('pseudo' => $username));
		$count = $req->rowCount();
		
		if ($count == 0) { // si le nombres de ligne = 0 alors on enregiste l'utilisateur
			$req = $connect->prepare("INSERT INTO utilisateur(nom, prenom, email, pseudo, motdepasse) VALUES (:nom, :prenom, :email, :pseudo, :motdepasse)");
			$req->execute(array('prenom' => $firstname, 'nom' => $lastname, 'email' => $email, 'pseudo' => $username, 'motdepasse' => md5($password))); // mot de passe en MD5
		}
	}		
}

// --------- Mise à jour des informations d'un utilisateur -------
function updateUser($connect){
	$idUser = idUser($connect);
	
	$res = donneesUser($connect);
	$password = $res["motdepasse"];
	$username = $res["pseudo"];
	$email = $res["email"];
	
	// récupère la valeur à modifier et si le post n'existe pas cela mais la valeur par défaut de la BDD 
	$newPassword = lireDonneePost("newPassword",$password);	
	$newEmail = lireDonneePost("newEmail",$email);
	$newUsername = lireDonneePost("newUsername",$username);
	
	// Si la variable est vide cela mais la valeur par défaut de la BDD
	if ($newPassword == ""){ $newPassword = $password; }
	if ($newEmail == ""){ $newEmail = $email; }
	if ($newUsername == ""){ $newUsername = $username; }

	if ($idUser != "") {
		$req = $connect->prepare("UPDATE utilisateur SET motdepasse = :password, pseudo = :username, email = :email WHERE idutilisateur = :iduser");
		if ($newPassword == $password){
			// si $newPassword == $password cela enregistre $newPassword(contenant la valeur initial de la BDD) qui est déjà au format MD5
			$req->execute(array('password' => $newPassword, 'username' => $newUsername, 'email' => $newEmail, 'iduser' => $idUser));			
		} else {
			$req->execute(array('password' => md5($newPassword), 'username' => $newUsername, 'email' => $newEmail, 'iduser' => $idUser));
		}
						
		if ($password != $newPassword){ 
			enregistrerSession("motdepasse", $newPassword);
		}			
		if ($username != $newUsername){ 
			enregistrerSession("pseudo", $newUsername);
		}		
	}
}

// ---------- Connecte l'utilisateur -------------
function connexion($connect, $nbr) {
	$formConnexion = lireDonneePost("formconnexion", "");
	$sessionConnexion = lireDonneeSession("sessionconnexion", "");
	
	if (($sessionConnexion == 1)||($formConnexion == 1)){
		// récupèration du pseudo et motdepasse de la session ou du formulaire
		if ($sessionConnexion == 1){
			$pseudo = lireDonneeSession("pseudo", "");
			$motdepasse = lireDonneeSession("motdepasse", "");
		} else {
			$pseudo = lireDonneePost("pseudo", "");
			$motdepasse = lireDonneePost("motdepasse", "");
		}
		
		if (($pseudo != "")&&($motdepasse != "")) {
			// récupère le nombres de ligne pour vérifier que l'utilisateur existe et que le motdepasse est correcte
			$req = $connect->prepare("SELECT * FROM utilisateur WHERE pseudo = :pseudo AND motdepasse = :motdepasse ");
			$req->execute(array('pseudo' => $pseudo, 'motdepasse' => md5($motdepasse)));
			$count = $req->rowCount();
					
			if($count == 1) { // si le nombres de ligne = 1 alors on connecte l'utilisateur
				if ($sessionConnexion != 1){ // enregistre les données de connexion dans une session
					enregistrerSession("sessionconnexion", "1");
					enregistrerSession("pseudo", $pseudo);
					enregistrerSession("motdepasse", $motdepasse);
				}
				return $nbr;
			}		
		}
	} else {
		return "";
	}
}

// -------- initialiser la session -----------
function initSession() {
    session_start();
}

// -------- enregistrer une donnée Session ---------
function enregistrerSession($nomDonnee, $val) {
    $_SESSION[$nomDonnee] = $val;
}

// -------- récupérer une donnée Session ----------
function lireDonneeSession($nomDonnee, $valDefaut="") {
   if(isset($_SESSION[$nomDonnee])){
       $val = $_SESSION[$nomDonnee];
   }
   else {
       $val = $valDefaut;
   }
   return $val;
}

// -------- déconnecter l'utilisateur ---------
function deconnexion() {
	 supprimerDonneeSession("sessionconnexion");
	 supprimerDonneeSession("pseudo");
	 supprimerDonneeSession("motdepasse");
}

// -------- supprimer des donnée Session ---------
function supprimerDonneeSession($nomDonnee) {
	 unset($_SESSION[$nomDonnee]);
}


// -------- choix langage ---------
function langage(){
	$sessionLang = lireDonneeSession("sessionlang", "");
	$formLang = lireDonneePost("formlang", "");
	
	//par défaut
	
	$defaut = "Fr";
	
	if ($formLang == 1){
		$lang = lireDonneePost("lang", $defaut);
		enregistrerSession("lang", $lang);
		if ($sessionLang != 1){
			enregistrerSession("sessionlang", "1");
		}	
	} elseif (($sessionLang == 1)&&($formLang != 1)) {
		$lang = lireDonneeSession("lang", $defaut);	
	} else {
		$lang = $defaut;
	}

	return $lang;
}

// ------- traduction du texte --------
function traduction($connect, $element, $lang){
	
	if ($lang == "Fr"){
		$champ = 'textefr';
	} elseif ($lang == "En"){
		$champ = 'texteen';
	}
		
	$req = $connect->prepare("SELECT * FROM textepage WHERE idtexte = :element");
	$req->execute(array('element' => $element));	
	$res = $req->fetch();
	$texte = $res[$champ];
		
	return utf8_encode($texte);
}

// ------- récupérer données utilisateur --------
function donneesUser($connect){	
	$idUser = idUser($connect);
	
	$req = $connect->prepare("SELECT * FROM utilisateur WHERE idutilisateur = :iduser ");
	$req->execute(array('iduser' => $idUser));
	$res = $req->fetch();
	
	return $res;
}

// ------- récupérer id utilisateur --------
function idUser($connect){
	$pseudo = lireDonneeSession("pseudo", "");
	$motdepasse = lireDonneeSession("motdepasse", "");	
	
	$req = $connect->prepare("SELECT idutilisateur FROM utilisateur WHERE pseudo = :pseudo AND motdepasse = :motdepasse ");
	$req->execute(array('pseudo' => $pseudo, 'motdepasse' => md5($motdepasse)));
	$res = $req->fetch();
	
	return $res["idutilisateur"];	
}

// ------- recupère l'id du worker qui à le moins de taches --------
function idWorkerMinTache($connect){

	// requete qui recupère l'id du premier worker ayant le nombre de tache le plus petit
    $req = $connect->prepare("SELECT idworker FROM worker WHERE nbrtaches=(SELECT MIN(nbrtaches) FROM worker) LIMIT 1");
	$req->execute();
	$res = $req->fetch();
	
	return $res["idworker"];
}

// ------- récupérer le nombre d'état en "attente", encour ou "split" --------
function nombreEtat($connect, $etat){

	$req = $connect->prepare("SELECT * FROM tache WHERE etat = :etat");
	$req->execute(array('etat' => $etat));
	$res = $req->fetchAll();
	$nbrEtat = count($res);

	return $nbrEtat;
}

// ------- récupérer le nombre d'état "encour" d'un worker --------
function nbrEtatEnCourWorker($connect, $idWorker){

	$req = $connect->prepare("SELECT * FROM tache WHERE etat = :etat AND idworker = :idworker");
	$req->execute(array('etat' => 'encour', 'idworker' => $idWorker));
	$res = $req->fetchAll();
	$nbrEtat = count($res);

	return $nbrEtat;
}

// ------- récupérer les données de la tache à trancoder --------
function donneesTache($connect, $idWorker, $etat){
	if( $idWorker != ""){
		$req = $connect->prepare("SELECT * FROM tache WHERE idworker = :idworker AND etat = :etat LIMIT 1");
		$req->execute(array('idworker' => $idWorker, 'etat' => $etat));
	} else {
		$req = $connect->prepare("SELECT * FROM tache WHERE etat = :etat LIMIT 1");
		$req->execute(array('etat' => $etat));
	}
	$res = $req->fetch();
	
	return $res;
}

// ------- change l'état de la tache en terminer --------
function changeEtat($connect, $idTache, $etat, $date){

	if($date != ""){
		$req = $connect->prepare("UPDATE tache SET etat = :etat, date = :date WHERE idtache = :idtache");
		$req->execute(array('etat' => $etat, 'date' => $date, 'idtache' => $idTache));
	} else {
		$req = $connect->prepare("UPDATE tache SET etat = :etat WHERE idtache = :idtache");
		$req->execute(array('etat' => $etat, 'idtache' => $idTache));
	}

}

// ------- envoi de mail a l'utilisateur --------
function envoiMail($connect, $idUtilisateur, $nomFichier, $date){

	// récupère l'email de l'utilisateur
	$req = $connect->prepare("SELECT email FROM utilisateur WHERE idutilisateur = :idutilisateur");
	$req->execute(array('idutilisateur' => $idUtilisateur));
	$res = $req->fetch();
	$email = $res["email"];

	print_r($email);

	// mise en forme message
	$sujet = "Transcodage terminé";		
	$message = "Le transcodage du fichier : ".$nomFichier." c'est terminé le :".$date;
	print_r($message);
	/*$entete = 'From: '.$email."\r\n".
				'Reply-To: '.$email."\r\n".
				'X-Mailer: PHP/'.phpversion();*/

	// envoi du mail
	
	print_r(mail($email,$sujet,$message));
	//mail($email,$sujet,$message,$entete);

}

// ------- espace de stockage utilisé --------
/*utiliser pour savoir si il est encore possible de valider un transcodage avant paiemment
et pour afficher l'info à l'utilisateur dans sont compte*/
function stockageUtiliser($connect){

	$idUtilisateur = idUser($connect);

	// somme de la taille de toutes les taches avec l'etat "terminer"
	$req = $connect->prepare("SELECT SUM(taille) AS somme FROM tache WHERE idutilisateur = :idutilisateur AND etat = :etat ");
	$req->execute(array('idutilisateur' => $idUtilisateur, 'etat' => 'terminer'));
	$res = $req->fetch();

	$somme = $res["somme"];
	
	return $somme;

}

// ------- supprimer le fichier upload après le transcode -------
function supprimerFichierUpload($connect, $idTache){

	//requete pour recupérer le nom et l'extension du fichier
	$req = $connect->prepare("SELECT idutilisateur, nomfichier, uploadformat FROM tache WHERE idtache = :idtache");
	$req->execute(array('idtache' => $idTache));	
	$res = $req->fetch();

	$idUtilisateur = $res["idutilisateur"];

	$nom = $res["nomfichier"];
	$format = $res["uploadformat"];
	$nomFichier = $nom.".".$format;

	// requete pour verifier que d'autres taches n'ont pas le meme fichier
	$req = $connect->prepare("SELECT * FROM tache WHERE nomfichier = :nomfichier AND uploadformat = :uploadformat AND etat != :etat ");
	$req->execute(array('nomfichier' => $nom, 'uploadformat' => $format, 'etat' => "terminer"));	
	$nbrTacheMemeFichier = $req->rowCount();

	if ($nbrTacheMemeFichier < 2) {
		// supprimer le fichier du serveur
		$lienSuppr = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/upload/".$nomFichier;
		unlink ($lienSuppr);
	}

}

// ------- supprimer ficher pour libérer de l'espace disque --------
function supprimerFichierTranscode($connect){

	$idTache = lireDonneePost("idTache", "");

	//requete pour recupérer le nom et l'extension du fichier
	$req = $connect->prepare("SELECT idutilisateur, nomfichier, transcodeformat FROM tache WHERE idtache = :idtache");
	$req->execute(array('idtache' => $idTache));	
	$res = $req->fetch();

	$idUtilisateur = $res["idutilisateur"];

	$nom = $res["nomfichier"];
	$format = $res["transcodeformat"];
	$nomFichier = $nom.".".$format;

	// supprimer le fichier du serveur
	$lienSuppr = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/transcode/".$nomFichier;
	unlink($lienSuppr);

	// change l'etat de la tache en "supprimer" (pour garder une trace des taches soumise par l'utilisateur)
	changeEtat($connect, $idTache, "supprimer", "");

}

// ------- supprimer les splits du dossier upload et transcode --------
function supprimerSplitUploadEtTranscode($connect, $idTache){

	//requete pour recupérer le nom, l'extension du fichier et le nombre de split
	$req = $connect->prepare("SELECT * FROM tache WHERE idtache = :idtache");
	$req->execute(array('idtache' => $idTache));	
	$res = $req->fetch();

	$idUtilisateur = $res["idutilisateur"];
	$nom = $res["nomfichier"];
	$transcodeFormat = $res["transcodeformat"];
	$uploadFormat = $res["uploadformat"];
	$nbrsplit = $res["nbrsplit"];

	// requete pour verifier que d'autres taches n'ont pas le meme fichier
	$req = $connect->prepare("SELECT * FROM tache WHERE nomfichier = :nomfichier AND uploadformat = :uploadformat AND etat != :etat ");
	$req->execute(array('nomfichier' => $nom, 'uploadformat' => $uploadFormat, 'etat' => "terminer"));	
	$nbrTacheMemeFichier = $req->rowCount();

	
		for ($i=0; $i<$nbrsplit; $i++){
			
			$nomSplitUpload = $nom.$i.".".$uploadFormat;
			$nomSplitTranscode = $nom.$i.".".$transcodeFormat;

			if ($nbrTacheMemeFichier < 2) {
				// supprimer le split upload
				$lienSupprUpload = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/upload/split/".$nomSplitUpload;
				unlink ($lienSupprUpload);
			}

			// supprimer le split transcode
			$lienSupprTranscode = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/transcode/split/".$nomSplitTranscode;
			unlink ($lienSupprTranscode);

		}
	

}

// ------- recupère le nombre de worker --------
function nbrWorkers($connect){

    $req = $connect->prepare("SELECT * FROM worker");
	$req->execute();
	$res = $req->fetchAll();
	$nbrWorkers = count($res);
	
	return $nbrWorkers;
}

// ------- ajouter le nombre de split total a la tache --------
function ajoutSplitTache($connect, $idTache, $nombreSplit){

	$req = $connect->prepare("UPDATE tache SET nbrsplit = :nbrsplit WHERE idtache = :idtache");
	$req->execute(array('nbrsplit' => $nombreSplit, 'idtache' => $idTache));

}

// ------- ajouter le nombre de split à chaque worker --------
function ajoutSplitWorker($connect, $idTache){

	// récupère le nombre de split de la tache
	$req = $connect->prepare("SELECT nbrsplit FROM tache WHERE idtache = :idtache");
	$req->execute(array('idtache' => $idTache));
	$res = $req->fetch();
	$nbrTotalSplit = $res["nbrsplit"];

	// récupère le nombre de worker
	$nbrWorkers = nbrWorkers($connect);

	$premierNbrSplit = (int)($nbrTotalSplit/$nbrWorkers);
			
	for ($j=0; $j<$nbrWorkers; $j++){
		$tabSplit[$j] = $premierNbrSplit;	
	}
			
	// calcule du nombre de split restant
	$nbrRestant = $nbrTotalSplit%$nbrWorkers;

	// incrémente le tableau en fonction du nombre de split restant
	for ($j=0; $j<$nbrRestant; $j++){	  
		$tabSplit[$j] = $tabSplit[$j] + 1;	
	}

	for ($i=1; $i<=$nbrWorkers; $i++){

		// récupère le nombre de split de chaque partie du tableau
		$nbrSplit = $tabSplit[$i-1];

		// requete qui ajoute le nombre de splits à chaque worker
	    $req = $connect->prepare("UPDATE worker SET nbrsplits = :nbrsplits WHERE idworker = :idworker");
		$req->execute(array('nbrsplits' => $nbrSplit, 'idworker' => $i));
	}
}

// ------- recupère les donner d'un worker --------
function donneesWorker($connect, $idWorker){

    $req = $connect->prepare("SELECT * FROM worker WHERE idworker = :idworker");
	$req->execute(array('idworker' => $idWorker));
	$res = $req->fetch();
	
	return $res;
}

// ------- retourne le numéro du split à traiter --------
function numSplitATraiter($connect, $idWorker, $nbrTranscodeSplitWorker){

	$numSplit = 0;

	// en fonction de l'id du worker cela récupère le nbr total de split des workers précédent et les additionnes
	for ($i=1; $i<$idWorker; $i++){
		$req = $connect->prepare("SELECT nbrsplits FROM worker WHERE idworker = :idworker");
		$req->execute(array('idworker' => $i));
		$res = $req->fetch();

		$numSplit = $numSplit + $res["nbrsplits"];
	}

	// puis ajoute au résultat le nombre de split traité de se worker + 1
	$numSplit = $numSplit + $nbrTranscodeSplitWorker;


	return $numSplit;
}

// ------- Ajoute 1 split de traité --------
function splitTraiter($connect, $idTache, $idWorker){

	// requete qui ajoute 1 split de traité à la tache
	$req = $connect->prepare("UPDATE tache SET nbrtranscodesplit = nbrtranscodesplit + 1 WHERE idtache = :idtache");
	$req->execute(array('idtache' => $idTache));

	// requete qui ajoute 1 split de traité au worker
	$req = $connect->prepare("UPDATE worker SET nbrtranscodesplit = nbrtranscodesplit + 1 WHERE idworker = :idworker");
	$req->execute(array('idworker' => $idWorker));


}

// ------- initialiser le nombre de split des workers -------
function nbrSplitWorkersZero($connect, $idTache, $nbrWorkers){

	// requete met à 0 le nombre de split total et traité de chaque worker
	for ($i=1; $i<=$nbrWorkers; $i++){
		$req = $connect->prepare("UPDATE worker SET nbrsplits = :nbrsplits, nbrtranscodesplit = :nbrtranscodesplit WHERE idworker = :idworker");
		$req->execute(array('nbrsplits' => 0, 'nbrtranscodesplit' => 0, 'idworker' => $i));
	}

}

// ------- Supprimer 1 tache d'un workers -------

function tacheTraiter($connect, $workId){

	// requete enleve 1 tache du worker
	$req = $connect->prepare("UPDATE worker SET nbrtaches = nbrtaches - 1 WHERE idworker = :idworker");
	$req->execute(array('idworker' => $workId));

}


// ------- retourne le montant à payer en fonction du format et du temps de la video/audio --------
function montantTranscode($connect, $format, $tempsMinute){

	// récupère le montant correspondant au format à traiter
	$req = $connect->prepare("SELECT prix FROM montant WHERE transcodeformat = :transcodeformat");
	$req->execute(array('transcodeformat' => $format));
	$res = $req->fetch();
	$prix = $res["prix"];

	$montant = $prix * ($tempsMinute/60); // multiplie le prix par heure de traitement 

	return number_format($montant,2);
}




// --------- enregistrer données File en session -------
function enregistrerFileSession($nomDonnee, $url) {
	
	$mediaName = null;
	$heureActuel = (int)date("H");
	$mediaTmpName = $_SERVER["DOCUMENT_ROOT"]."/tmp/".rand()."@".$heureActuel.".tmp";

	if((isset($_FILES[$nomDonnee]['name']))&&($url == "")){

		$mediaError = $_FILES[$nomDonnee]['error'];
		$mediaName = $_FILES[$nomDonnee]['name'];
		$mediaSize = $_FILES[$nomDonnee]['size'];

		$lienTmpName = $_FILES[$nomDonnee]['tmp_name'];

		move_uploaded_file($lienTmpName, $mediaTmpName);

	} elseif( $url != ""){

		$mediaError = 0;

		$path_parts = pathinfo($url);
		$mediaName = $path_parts['basename'];

		$lienTmpName = $url;

		copy($lienTmpName, $mediaTmpName);

		$mediaSize = filesize($mediaTmpName);
	}

	if($mediaName != null){
		enregistrerSession("error", $mediaError);
		enregistrerSession("name", $mediaName);
		enregistrerSession("size", $mediaSize);
		enregistrerSession("tmp_name", $mediaTmpName);
	}

}


// --------- récupérer données File en session -------
function lireFileSession() {
    $val[0] = lireDonneeSession("error", "");
    $val[1] = lireDonneeSession("name", "");
    $val[2] = lireDonneeSession("tmp_name", "");
    $val[3] = lireDonneeSession("size", "");

    return $val;
}


// ------- Upload video/audio --------
function uploadMedia($connect){

	//print_r(lireFileSession()[0]);
	$mediaError = lireFileSession()[0];
	$mediaName = lireFileSession()[1];
	$mediaTmpName = lireFileSession()[2];

	if ($mediaError > 0){
		$erreur = "Erreur lors du transfert";
	} else {
		$extensions_valides = array('avi', 'mp4', 'flv', 'mp3');
		
		/*strrchr renvoie l'extension avec le point (« . »).
		substr(chaine,1) ignore le premier caractère de chaine.
		strtolower met l'extension en minuscules.*/
		$extension_upload = strtolower(substr(strrchr($mediaName, '.'),1));

		$idUtilisateur = idUser($connect);

		$dossier1 = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur;
		$dossier2 = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/upload";

		if(!is_dir($dossier1)){
		   	mkdir($dossier1, 0777);
		}
		if(!is_dir($dossier2)){
		   	mkdir($dossier2, 0777);

		}

		if($mediaName != "null"){
			$destination = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/upload/".$mediaName;
			if ( in_array($extension_upload,$extensions_valides) ) {
				if (file_exists($mediaTmpName)){
					copy($mediaTmpName,$destination);
				}
			}
		}

		/* supprime le fichier dans tmp si le fichier a eu le temps de se télécharger,
		 sinon on laisse le cron executé en fond le supprimer plutard */
		if (file_exists($destination)) {
			if (file_exists($mediaTmpName)){
				unlink($mediaTmpName);
			}
		}
	}
}

// ------- Ajoute une nouvelle tache à la base de données (fichier à transcoder) --------
function addTache($connect){

	$mediaName = lireFileSession()[1];
	$taille = lireFileSession()[3]/1000000000;

	$prix = lireDonneeSession("montant", "");;

	$uploadFormat = strtolower(substr(strrchr($mediaName, '.'),1));
	$nombreCaractNomFichier = strlen($mediaName)-(strlen($uploadFormat)+1);
	$nomFichier = substr($mediaName, 0 , $nombreCaractNomFichier);

	$transcodeFormat = lireDonneeSession("formatTranscode", "");

	$formatAudio = array('mp3', 'ogg', 'wav');
	$formatVideo = array('avi', 'mp4', 'flv');

	if ((in_array($uploadFormat,$formatAudio))&&(in_array($transcodeFormat,$formatAudio))){
		$typeMedia = "audio";
	} elseif ((in_array($uploadFormat,$formatVideo))&&(in_array($transcodeFormat,$formatVideo))){
		$typeMedia = "video";
	} elseif ((in_array($uploadFormat,$formatVideo))&&(in_array($transcodeFormat,$formatAudio))){
		$typeMedia = "video>audio";
	} else {
		$typeMedia = "";
	}

	$idUtilisateur = idUser($connect);

	$idWorker = idWorkerMinTache($connect);
	
	$etat = "attente";

	if($mediaName != ""){
		// requète qui ajoute la tache avec l'etat "attente" et l'id du worker
		$req = $connect->prepare("INSERT INTO tache(type, idutilisateur, nomfichier, uploadformat, transcodeformat, taille, prix, idworker, etat) VALUES (:type, :idutilisateur, :nomfichier, :uploadformat, :transcodeformat, :taille, :prix, :idworker, :etat)");
		$req->execute(array('type' => $typeMedia, 'idutilisateur' => $idUtilisateur, 'nomfichier' => $nomFichier, 'uploadformat' => $uploadFormat, 'transcodeformat' => $transcodeFormat, 'taille' => $taille, 'prix' => $prix, 'idworker' => $idWorker, 'etat' => $etat));

		// requète qui incrémente le nombre de taches du worker
		$req = $connect->prepare("UPDATE worker SET nbrtaches = nbrtaches + 1 WHERE idworker = :idworker");
		$req->execute(array('idworker' => $idWorker));
	}
}

// ------- telechargement de fichier --------
function downloadFile($fullPath, $nomFichier, $extension){

  if( headers_sent() ){
  	die('Headers Sent');
  }
    

  // requie pour certain navigateur	
  if(ini_get('zlib.output_compression')){
  	ini_set('zlib.output_compression', 'Off');
  }
    

  	// ficher existe?
  	if( file_exists($fullPath) ){

	    $fsize = filesize($fullPath);

	    // Determiner le type de contenue
	    switch ($extension) {
	      case "mpeg": $ctype="video/mpeg"; break;
	      case "mp4": $ctype="video/mp4"; break;
	      case "flv": $ctype="video/x-flv"; break;
	      case "mp3": $ctype="audio/mpeg"; break;
	      case "wav": $ctype="audio/x-wav"; break;   
	      default: $ctype="application/force-download";
	    }

	    $nomAvecExtension = $nomFichier.".".$extension;

	    header("Expires: 0");
	    header("Pragma: no-cache");
	  	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0, public");
	    header("Content-Transfer-Encoding: $ctype\n"); // Surtout ne pas enlever le \n
	    header("Content-Type: application/force-download");
	    header("Content-Disposition: attachment; filename=$nomAvecExtension");
	    header("Content-Transfer-Encoding: binary");
	    header("Content-Length: $fsize");
	    ob_clean();
	    flush();
	    readfile( $fullPath );

	}

}


// ------- temps du fichier en seconde --------

function tempsEnSeconde($durer){

	$tabTemps = explode(":", $durer);
    $h = $tabTemps[0];
    $m = $tabTemps[1];
    $s = $tabTemps[2];

	return ($h*3600)+($m*60)+$s;
}


// ------- enregistrement durée media --------

function saveDureeMedia($connect, $idTache, $duree){

	$req = $connect->prepare("UPDATE tache SET duree = :duree WHERE idtache = :idtache");
	$req->execute(array('idtache' => $idTache, 'duree' => $duree));

}

// ------- nbr split des workers précédent --------

function nbrSplitAutresWorker($connect, $thisWorker, $limite){

	// récupérer le nbr de taches des workers qui précède ayant une durée supérieur à 10min
	$req = $connect->prepare("SELECT * FROM tache WHERE idworker < :thisWorker AND duree >= :duree AND (etat != :etat1 AND etat != :etat2 ");
	$req->execute(array('thisWorker' => $thisWorker, 'duree' => $limite, 'etat1' => "supprimer", 'etat2' => "terminer"));	
	$count = $req->rowCount();

	return $count;
}

?>