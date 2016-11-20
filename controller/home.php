<?php
$title = "Home";
include('partials/header.php');
include('partials/menu.php');
if ($connexionUtilisateur == $nbr){ //si l'utilisateur est connecté cela inclut la page home de l'utilisateur
	include('content/homeUser.php');

} else {
	include('content/home.php');
}
include('partials/footer.php');