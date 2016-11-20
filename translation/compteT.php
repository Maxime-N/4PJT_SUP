<?php
//--------------- Traduction compte--------------

//valeur pour chaque langage
switch ($lang) {
    case 'Fr':
		
		$titre = "Profil Utilisateur";
		$Nom = "Nom";
		$Prenom = "Prenom";
		$Email = "Email";
		$NomUtilisateur = "Nom Utilisateur";

		$btnTitreMdp = "Modifier Mot de passe";
		$formAncienMdp = "Ancien mot de passe";
		$formNewMdp = "Nouveau mot de passe";
		$formConfMdp = "Confirmer mot de passe";

		$btnTitreNomEmail = "Modifier Nom Utilisateur/Email";
		$formEmail = "Email";
		$formNomUtilisateur = "Nom Utilisateur";

		$btnAnnuler = "Annuler";
		$btnModifier = "Modifier";

        break;
		
    case 'En':
		
		$titre = "User profile";
		$Nom = "Firstname";
		$Prenom = "Lastname";
		$Email = "Email";
		$NomUtilisateur = "Username";

		$btnTitreMdp = "Edit password";
		$formAncienMdp = "Old password";
		$formNewMdp = "New password";
		$formConfMdp = "Confirm password";

		$btnTitreNomEmail = "Edit Username/Email";
		$formEmail = "Email";
		$formNomUtilisateur = "Username";

		$btnAnnuler = "Cancel";
		$btnModifier = "Edit";

        break;
		
    default : echo " erreur 404"; break;
}

//tableau avec les valeurs
$tabTexteCompte =array($titre, $Nom, $Prenom, $Email, $NomUtilisateur, $btnTitreMdp, $formAncienMdp, $formNewMdp, $formConfMdp, $btnTitreNomEmail,
$formEmail, $formNomUtilisateur, $btnAnnuler, $btnModifier);

?>