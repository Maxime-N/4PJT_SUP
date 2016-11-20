<?php
//--------------- Traduction about--------------

//valeur pour chaque langage
switch ($lang) {
    case 'Fr':
		
		$titre = "Transcode";
		$sousTitre = "A propos";
		$creerPar = "Transcode est un outil créé par :";

        break;
		
    case 'En':
		
		$titre = "Transcode";
		$sousTitre = "About";
		$creerPar = "Transcode is a tool created by:";

        break;
		
    default : echo " erreur 404"; break;
}

//tableau avec les valeurs
$tabTexteAbout =array($titre, $sousTitre, $creerPar);

?>