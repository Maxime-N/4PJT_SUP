<?php

//--------------- Traduction medias --------------

//valeur pour chaque langage
switch ($lang) {
    case 'Fr':
		
		$titre = "Vos Medias";
		$sousTitre1 = "Espace de stockage utilisé";
		$sousTitre2 = "Vos fichiers sauvegardé";
		$sousTitre3 = "Transcodage en cours";

		$titreTab1 = "ID";
		$titreTab2 = "Fichier";
		$titreTab3 = "Type";
		$titreTab4 = "Taille";
		$titreTab5 = "Date";
		$titreTab6 = "Prix";
		$titreTab7 = "FormatBase";

		$message1 = "Vous n'avez aucun fichier sauvegardé";
		$message2 = "Aucun transcodage en cours";

        break;
		
    case 'En':
		
    	$titre = "Your Medias";
		$sousTitre1 = "Storage used";
		$sousTitre2 = "Your saved files";
		$sousTitre3 = "Transcoding in progress";

		$titreTab1 = "ID";
		$titreTab2 = "File";
		$titreTab3 = "Type";
		$titreTab4 = "Size";
		$titreTab5 = "Date";
		$titreTab6 = "Price";
		$titreTab7 = "BaseFormat";

		$message1 = "You have no saved file";
		$message2 = "No current transcoding";

        break;
		
    default : echo " erreur 404"; break;
}

//tableau avec les valeurs
$tabTexteMedias =array( $titre, $sousTitre1, $sousTitre2, $sousTitre3, $titreTab1, $titreTab2, $titreTab3, $titreTab4, $titreTab5, $titreTab6, $titreTab7, $message1, $message2);

?>