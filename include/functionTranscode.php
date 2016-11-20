<?php

 function transcode($connect, $etat, $type, $nomFichier, $uploadFormat, $transcodeFormat, $idUtilisateur, $numSplitATraiter){

 		if (($numSplitATraiter >= 0)&&($numSplitATraiter < 5)){
 			$nomFichierUpload = $nomFichier.$numSplitATraiter.".".$uploadFormat;
    		$nomFichierTranscode = $nomFichier.$numSplitATraiter.".".$transcodeFormat;
 		} else {
 			$nomFichierUpload = $nomFichier.".".$uploadFormat;
	    	$nomFichierTranscode = $nomFichier.".".$transcodeFormat;
 		}
 		

    	// si c'est un "split" à transcoder
    	if ($etat == "split"){

    		// chemin du split Upload "users/iduser/upload/split"
    		$cheminUpload = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/upload/split/";

    		// chemin de destination du split "users/iduser/transcode/split"
    		$cheminDestination = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/transcode/split/";

    	} else {

    		// chemin du fichier Upload "users/iduser/upload"
    		$cheminUpload = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/upload/";

    		// chemin de destination du fichier "users/iduser/transcode"
    		$cheminDestination = $_SERVER["DOCUMENT_ROOT"]."/users/".$idUtilisateur."/transcode/";
    	}


    	//transcodeMedia($connect, $cheminUpload, $cheminDestination, $nomFichierUpload, $nomFichierTranscode);

    	$ffmpeg = '/usr/local/bin/ffmpeg';
    	$mediaUpload = $cheminUpload.$nomFichierUpload;
		$mediaTranscode = $cheminDestination.$nomFichierTranscode;


		// action en fonction des formats de transcodage
		if ((($uploadFormat == "flv")&&($transcodeFormat == "avi"))||(($uploadFormat == "flv")&&($transcodeFormat == "wav"))
			||(($uploadFormat == "avi")&&($transcodeFormat == "wav"))||(($uploadFormat == "mp4")&&($transcodeFormat == "mp3"))
			||(($uploadFormat == "mp4")&&($transcodeFormat == "wav"))||($type == "audio")){

			transcodeType1($ffmpeg, $mediaUpload, $mediaTranscode);

		} else if ((($uploadFormat == "flv")&&($transcodeFormat == "mp3"))||(($uploadFormat == "avi")&&($transcodeFormat == "flv"))
			||(($uploadFormat == "avi")&&($transcodeFormat == "mp3"))||(($uploadFormat == "mp4")&&($transcodeFormat == "avi"))
			||(($uploadFormat == "mp4")&&($transcodeFormat == "flv"))){

			transcodeType2($ffmpeg, $mediaUpload, $mediaTranscode);

		}/* else if (($uploadFormat == "flv")&&($transcodeFormat == "mp4")){

			transcodeType3($ffmpeg, $mediaUpload, $mediaTranscode);

		}  else if (($uploadFormat == "avi")&&($transcodeFormat == "mp4")){

			transcodeType4($ffmpeg, $mediaUpload, $mediaTranscode);

		}*/

}




//############## Fonctions de traitement du trancode ################



function transcodeType1($ffmpeg, $mediaUpload, $mediaTranscode){

	$command1 = "$ffmpeg -i $mediaUpload $mediaTranscode";
	exec($command1);

}

function transcodeType2($ffmpeg, $mediaUpload, $mediaTranscode){
	
	$command2 = "$ffmpeg -i $mediaUpload -acodec copy $mediaTranscode";
	exec($command2);		

}

/*function transcodeType3($ffmpeg, $mediaUpload, $mediaTranscode){
	
	$command3 = "$ffmpeg -i $mediaUpload -map 0 -c:v libx264 -c:a copy $mediaTranscode";
	exec($command3);		

}

function transcodeType4($ffmpeg, $mediaUpload, $mediaTranscode){
	
	$command4 = "$ffmpeg -i $mediaUpload -acodec libfaac -b:a 128k -vcodec mpeg4 -b:v 1200k -flags +aic+mv4 $mediaTranscode";
	exec($command4);		

}*/



function miseEnFormeTempsSplit($temps){

				// ---------- heure ----------

				if ($temps>=3600){ // plus ou = 1h
					$h = (int)($temps/3600);
					if($h<10){ $h = "0".$h; }
					$temps = $temps-($h*3600);
				} else {
					$h = "00";
				}

				// ---------- minute ----------

				if ($temps>=60){ // plus ou = 1min
					$m = (int)($temps/60);
					if($m<10){ $m = "0".$m; }
					$temps = $temps-($m*60);
				} else {
					$m = "00";
				}

				// ---------- seconde ----------

				if ($temps>0){ // plus 1s
					$s = (int)$temps;
					if($s<10){ $s = "0".$s; }
				} else {
					$s = "00";
				}

				// ---------- Temps mise en forme ----------

				$temps = $h.":".$m.":".$s;

				return $temps;

}


?>