<?php

//--------------- Traduction upload --------------

//valeur pour chaque langage
switch ($lang) {
    case 'Fr':
		$sousTitre1 = "Sélection du fichier et format de conversion";
		$btnFile = "Parcourir";
		$txtFileSelect = "Aucun fichier sélectionné";
		$ou = "OU";
		$txtUrl = "Url fichier + (appuyer sur entrer)";

		$sousTitre2 = "Mode de paiement et Options";
		$infoFichierUpload = "Fichier Upload";
		$infoFormatTranscode = "Transcode Format";
		$infoMontantBase = "Montant de base";
		$choixPuissanceServeur = "Puissance Serveur";
		$choixPriorité = "Priorité";
		$choixModePaiement = "Mode de Paiement";
		$normal = "Normal";
		$elever = "Elevé";
		$cb = "Carte Bancaire";
		$infoMontantFinal = "Montant final";

		$sousTitre3 = "Paiement Carte bancaire";
		$choixTypeCarte = "Type Carte";
		$montant = "montant";
		$numCarte = "Numéro de carte";

		$sousTitre4 = "Paiement Paypal";

		$sousTitre5 = "Connexion";
		$form1 = "NomUtilisateur";
    	$form2 = "Motdepasse";

		$sousTitre6 = "Creation de compte";
    	$form3 = "Nom";
    	$form4 = "Prenom";
    	$form5 = "Email";
    	$form6 = "Nom Utilisateur";
    	$form7 = "Mot de passe";
    	$form8 = "Confirmation";


		$sousTitre7 = "Paiement validé, transcodage en cour";
		$info = "Vous recevrez un email une fois le transcodage terminé";

		$btn1 = "Retour";
		$btn2 = "Valider";
		$btn3 = "Connexion";
		$btn4 = "Autre Fichier?";

		$etape1 = "Fichier";
		$etape2 = "Options";
		$etape3 = "Paiement";
		$etape4 = "Transcode";

        break;
		
    case 'En':

    	$sousTitre1 = "Selecting the file and format conversion";
		$btnFile = "Browse";
		$txtFileSelect = "No file selected";
		$ou = "OR";
		$txtUrl = "Url fichier + (press enter)";

		$sousTitre2 = "Payment and Options";
		$infoFichierUpload = "File Upload";
		$infoFormatTranscode = "Transcode Format";
		$infoMontantBase = "Basic amount";
		$choixPuissanceServeur = "Server power";
		$choixPriorité = "Priority";
		$choixModePaiement = "Payment method";
		$normal = "Normal";
		$elever = "High";
		$cb = "Credit card";
		$infoMontantFinal = "Final amount";

		$sousTitre3 = "Payment Credit card";
		$choixTypeCarte = "Card type";
		$montant = "Amount";
		$numCarte = "Card number";

		$sousTitre4 = "Payment Paypal";

		$sousTitre5 = "Log in";
		$form1 = "Username";
    	$form2 = "Password";

		$sousTitre6 = "Account creation";
    	$form3 = "Firtname";
    	$form4 = "Lastname";
    	$form5 = "Email";
    	$form6 = "Username";
    	$form7 = "Password";
    	$form8 = "Confirm";


		$sousTitre7 = "Payment validated, transcoding launched";
		$info = "You will receive an email once the completed transcoding";

		$btn1 = "Back";
		$btn2 = "Submit";
		$btn3 = "Log in";
		$btn4 = "Other File?";

		$etape1 = "File";
		$etape2 = "Options";
		$etape3 = "Payment";
		$etape4 = "Transcode";

        break;
		
    default : echo " erreur 404"; break;
}

//tableau avec les valeurs
$tabTexteUpload =array($sousTitre1, $btnFile, $txtFileSelect, $ou, $txtUrl, $sousTitre2, $infoFichierUpload, $infoFormatTranscode, $infoMontantBase, $choixPuissanceServeur,
	$choixPriorité, $choixModePaiement, $normal, $elever, $cb, $infoMontantFinal, $sousTitre3, $choixTypeCarte, $montant, $numCarte, $sousTitre4, $sousTitre5, $form1, $form2,
	$sousTitre6, $form3, $form4, $form5, $form6, $form7, $form8, $sousTitre7, $info, $btn1, $btn2, $btn3, $btn4, $etape1, $etape2, $etape3, $etape4);

?>