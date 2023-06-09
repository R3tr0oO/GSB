<?php
/**
 * Gestion des frais
 */
date_default_timezone_set('Europe/Paris');
$idVisiteur = $_SESSION['idUtilisateur'];
$mois = getMois(date('d/m/Y'));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
$action = filter_input(INPUT_GET, 'action');
switch ($action) {
case 'saisirFrais'://permet de tester si il y a deja des fiches de frais pour ce moi
    if ($pdo->estPremierFraisMois($idVisiteur, $mois)) { // si yen a ps
        $pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
    } //il creer la fiche de frais avec comme valeure 0 pour ts les montants mais il a crer la fiche avec le mois l
    break;
case 'validerMajFraisForfait':
     $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    if (lesQteFraisValides($lesFrais)) { //verifie que lesFrais contienne que des valeurs numerique  RETOURNE VRAI OU FAUX EN FONCTION 
        $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
    } else {
        ajouterErreur('Les valeurs des frais doivent être numériques');
        include 'vues/v_erreurs.php';
    }
    break;
case 'validerCreationFrais': //pour les hors forfait
    $dateFrais = filter_input(INPUT_POST, 'dateFrais');
    $libelle = filter_input(INPUT_POST, 'libelle');
    $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
    valideInfosFrais($dateFrais, $libelle, $montant); // verifie que la date ... ts est coherent et bien remplit
    if (nbErreurs() != 0) {
        include 'vues/v_erreurs.php';
    } else { // Crée un nouveau frais hors forfait pour un visiteur un mois donné
        $pdo->creeNouveauFraisHorsForfait(
            $idVisiteur,
            $mois,
            $libelle,
            $dateFrais,
            $montant
        );
    }
    break;
case 'supprimerFrais': //Supprime le frais hors forfait dont l'id est passé en argument
    $idFrais = filter_input(INPUT_GET, 'idFrais');
    $pdo->supprimerFraisHorsForfait($idFrais);
    break;
}
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
require 'vues/v_listeFraisForfait.php';
require 'vues/v_listeFraisHorsForfait.php';
