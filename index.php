

<?php


require_once 'modeles/m_gestionUser.php';//le suffixe _once sert à limiter cette inclusion à une seule par page.cette bibliotheque est necessaire pour le php
require_once 'modeles/m_dao.php';//require: inclure
session_start();
$pdo = PdoGsb::getPdoGsb();//connection ouverture de l'application:dans la variable on appelle la fonction getPdoGsb de la classe PdoGsb 
$estConnecte = estConnecte();
require 'vues/v_entete.php';//c'est l'entete . message d'erreur si il n'arrive pas à l'inclure
$uc = filter_input(INPUT_GET, 'uc');//filtre le contenue qui est envoye pr qu'il soit que en string pr pouvoir l'exploiter
if ($uc && !$estConnecte) { // si on est pas connecté et si il ya qqch dans $uc
    $uc = 'connexion';
} elseif (empty($uc)) {
    $uc = 'accueil'; 
}
switch ($uc) {
case 'connexion':
    include 'controleurs/c_connexion.php';
    break;
case 'accueil': //$uc prend la valeur accueil une foie que l'utlisateur est connecte(plus haut) 
    include 'controleurs/c_accueil.php';
    break;
case 'gererFrais':
    include 'controleurs/c_gererFrais.php'; //permettre de creer la nouvelle fiche
    break;
case 'validerFrais': //pour valider l fiche de frais quand il clique sur valider fiche de frais
    $monControleur = 'validerFrais';
    include 'controleurs/c_validerFrais.php'; 
    break;
case'corriger_frais':
    $monControleur = 'validerFrais';
    include 'controleurs/c_corriger_frais.php';
    break;
case 'etatFrais':
    include 'controleurs/c_etatFrais.php';
    break;
case 'SuivreLePaiement':
    $monControleur = 'paiementFrais';
    include 'controleurs/c_validerFrais.php';
    break;
case 'SuivrePaiement':
    $monControleur = 'paiementFrais';
    include 'controleurs/c_suivrePaiment.php';
    break;
case 'deconnexion':
    include 'controleurs/c_deconnexion.php';
    break;
case 'administration':
    include 'controleurs/c_admin.php';
    break;
}
require 'vues/v_pied.php';//pied de page de l'accueil
