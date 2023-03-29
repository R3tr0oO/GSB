<?php
/**
 * Gestion de l'accueil
 */

if ($estConnecte) {
    include 'vues/v_accueil.php';//si on est connecté a l'appli aller dans la page accueil
} else {
    include 'vues/v_connexion.php';//si on ne l'est pas rester dans la page connection de l'appli
}
