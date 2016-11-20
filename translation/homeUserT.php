<?php
//--------------- Traduction Home User--------------

//valeur pour chaque langage
switch ($lang) {
    case 'Fr':

    	$titre = "Bienvenue sur votre compte";

    	$sousTitre1 = "Votre Compte";
    	$btn1 = "Compte";
        
    	$sousTitre2 = "Upload de Fichiers";
    	$btn2 = "Upload";

    	$sousTitre3 = "Informations et Téléchargement des Fichiers";
    	$btn3 = "Aller";

        break;
		
    case 'En':

    	$titre = "Welcome to your account";

    	$sousTitre1 = "Your Account";
    	$btn1 = "Compte";
        
    	$sousTitre2 = "Upload Files";
    	$btn2 = "Upload";

    	$sousTitre3 = "Information and Download Files";
    	$btn3 = "Go";

        break;
		
    default : echo " erreur 404"; break;
}

//tableau avec les valeurs
$tabTexteHomeUser =array($titre, $sousTitre1, $btn1, $sousTitre2, $btn2, $sousTitre3, $btn3);

?>