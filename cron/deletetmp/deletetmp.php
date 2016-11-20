<?php

$heureActuel = (int)date("H");

$dirname = $_SERVER["DOCUMENT_ROOT"]."/tmp/";
$dir = opendir($dirname); 

while($file = readdir($dir)) {
	if($file != '.' && $file != '..' && !is_dir($dirname.$file))
	{
	
		$heureFile = (int)substr(strrchr($file, '@'), 1, 2);
		// car sinon certain fichiers ne seront jamais supprimer car heureActuel(0 à 23)-2 = maxi 21h
		if ($heureActuel == 0){
			$heureActuel = 24;
		} elseif ($heureActuel == 1){
			$heureActuel = 25;
		}

		// si l'heure de création du fichier tmp qui se trouve dans son nom a été créé il y a plus 1h alors on le supprime
		if($heureFile <= ($heureActuel-2)){
			unlink($dirname.$file);
		}

	}
}

closedir($dir);

?>
