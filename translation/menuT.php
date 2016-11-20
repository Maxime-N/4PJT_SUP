<?php
//--------------- Traduction Menu--------------

//valeur pour chaque langage
switch ($lang) {
    case 'Fr':
        $menu1 = "Accueil";
		$menu2 = "Upload";
		$menu3 = "CreerCompte";
		$menu4 = "Medias";
		$menu5 = "Compte";
		$form1 = "NomUtilisateur";
		$form2 = "Motdepasse";
		$bouton1 = "Connexion";
		$bouton2 = "Déconnexion";
        break;
		
    case 'En':
        $menu1 = "Home";
		$menu2 = "Upload";
		$menu3 = "CreateAccount";
		$menu4 = "Medias";
		$menu5 = "Compte";
		$form1 = "Username";
		$form2 = "Password";
		$bouton1 = "Sign in";
		$bouton2 = "Sign out";
        break;
		
    default : echo " erreur 404"; break;
}

//tableau avec les valeurs
$tabTexteMenu =array( $menu1, $menu2, $menu3, $menu4, $menu5, $form1, $form2, $bouton1, $bouton2);

?>