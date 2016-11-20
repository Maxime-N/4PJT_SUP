<?php
$title = "Compte";
include('partials/header.php');
include('partials/menu.php');
if ($connexionUtilisateur == $nbr){
	include('content/compte.php');
} else {
	include('content/home.php');
}
include('partials/footer.php');