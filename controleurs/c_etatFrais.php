<?php
/**
 * Gestion de l'affichage des frais
 */
$monControleur = 'paiementFrais';
$action = filter_input(INPUT_GET, 'action');
$idVisiteur = $_SESSION['idUtilisateur'];
switch ($action) {
case 'selectionnerMois':
    $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
    // Afin de sélectionner par défaut le dernier mois dans la zone de liste
    // on demande toutes les clés, et on prend la première,
    // les mois étant triés décroissants
   
    $lesCles = array_keys($lesMois);
    $moisASelectionner = $lesCles[0];
  
    include 'vues/v_listeMois.php';
    break;
case 'voirEtatFrais':
    $leMois = filter_input(INPUT_POST, 'lstMois');
    $lesMois = $pdo->getLesMoisDisponibles($idVisiteur); //Retourne les mois pour lesquel un visiteur a une fiche de frais
    $moisASelectionner = $leMois; // pour que quand la page se recharge le mois seletionner est mis par defaut 
    include 'vues/v_listeMois.php';
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include 'vues/v_etatFrais.php';
}
