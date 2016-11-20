<?php

//--------------- Traduction création compte --------------

//valeur pour chaque langage
switch ($lang) {
    case 'Fr':

    	$titre = "Creation de compte";

    	$form1 = "Nom";
    	$form2 = "Prenom";
    	$form3 = "Email";
    	$form4 = "Nom Utilisateur";
    	$form5 = "Mot de passe";
    	$form6 = "Confirmation";

		$btn = "Valider";

        break;
		
    case 'En':
		
		$titre = "Create compte";

    	$form1 = "Firstname";
    	$form2 = "Lastname";
    	$form3 = "Email";
    	$form4 = "Username";
    	$form5 = "Password";
    	$form6 = "Confirm";

		$btn = "Submit";

        break;
		
    default : echo " erreur 404"; break;
}

//tableau avec les valeurs
$tabTexteCreerCompte =array($titre, $form1, $form2, $form3, $form4, $form5, $form6, $btn);

?>
