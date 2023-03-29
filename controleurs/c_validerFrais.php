  <?php
/**
 * gestion d'affichage des listes des visisteurs et mois pour valider la fiche et pour la mettre en paiement

 */

$action = filter_input(INPUT_GET, 'action');
switch ($action) {
case 'listeVisiteurs':
      $etatRechercher=$pdo->uc_visit();
    $nom = $pdo->getVisiteur($etatRechercher);// pour afficher la liste des visiteurs
     $idVisiteur = filter_input(INPUT_POST, 'visit'); // recupere l'utilisteur selectionner
   VisiteurSelectionne($idVisiteur);
    $nom = $pdo->getVisiteur($etatRechercher); // pour l'affichage
    $nomASelectionner = $idVisiteur;      // pour que quand la page se recharge l'utilisateur seletionner est mis par defaut 
   
  include 'vues/v_listeVisiteur.php';
        break;
    
case 'listeMois': // lorsqu'il a choisit l'utilisateur
     $etatRechercher=$pdo->uc_visit();
   $nom = $pdo->getVisiteur($etatRechercher); // pour l'affichage 
   $idVisiteur = filter_input(INPUT_POST, 'visit'); // recupere l'utilisteur selectionner
   VisiteurSelectionne($idVisiteur);
   $nomASelectionner = $idVisiteur; 
   $lesMois = $pdo->getLesMois($idVisiteur,$etatRechercher);// Afin de sélectionner par défaut le dernier mois dans la zone de liste
    $lesCles = array_keys($lesMois);
   $mois = filter_input(INPUT_POST, 'lstMois');
   MoiSelectionne($mois);
   $moisASelectionner = $mois;
   include 'vues/v_mois.php';
 break;
}
?>
