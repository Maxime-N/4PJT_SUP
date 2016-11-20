<?php
//--------------- Traduction home--------------

//valeur pour chaque langage
switch ($lang) {
    case 'Fr':
    	$titrePrincipal = "Bienvenue sur Transcode";

    	$etape = "3 étapes :";

    	$sousTitre1 = "Création d'un compte";
    	$btn1 = "Creer";

    	$sousTitre2 = "Upload des Fichiers";
    	$btn2 = "Upload";

    	$sousTitre3 = "Informations et Téléchargement des Fichiers";

    	$sousTitre4 = "Nos tarifs";

    	$sousTitre5 = "Stockage";

        break;
		
    case 'En':
    	$titrePrincipal = "Welcome to Transcode";

    	$etape = "3 stages:";

    	$sousTitre1 = "Create an account";
    	$btn1 = "Create";

    	$sousTitre2 = "Upload Files";
    	$btn2 = "Upload";

    	$sousTitre3 = "Information and Download Files";

    	$sousTitre4 = "Our prices";

    	$sousTitre5 = "Storage";
        
        break;
		
    default : echo " erreur 404"; break;
}

//tableau avec les valeurs
$tabTexteHome =array( $titrePrincipal, $etape, $sousTitre1, $btn1, $sousTitre2, $btn2, $sousTitre3, $sousTitre4, $sousTitre5 );

?>