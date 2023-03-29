<?php
/**
 * Gestion de l'affichage des possibilité de l'admin
 */
$monControleur = 'administration';
$action = filter_input(INPUT_GET, 'action');
$idVisiteur = $_SESSION['idUtilisateur'];

switch ($action) {
case 'createUser': /* Permet la création d'un nouvel utilisateur de l'intranet*/
    $idUser = filter_input(INPUT_POST, 'idUser');
    $nom = filter_input(INPUT_POST, 'nom');
    $prenom = filter_input(INPUT_POST, 'prenom');
    $login = filter_input(INPUT_POST, 'login');
    $mdp = filter_input(INPUT_POST, 'mdp');
    $adresse = filter_input(INPUT_POST, 'adresse');
    $cp = filter_input(INPUT_POST, 'cp');
    $ville = filter_input(INPUT_POST, 'ville');
    $dateEmbauche = filter_input(INPUT_POST, 'dateEmbauche');
    $roleUser = filter_input(INPUT_POST, 'roleUser');

     
    if (isset($_POST['valid_create'])) {
        $pdo->createUser($idUser, $nom, $prenom, $login, $mdp, $adresse, $cp, $ville, $dateEmbauche, $roleUser);
         echo '<script type="text/javascript">window.alert("utilisateur  crée ");</script>';
    }
    include 'vues/v_createUser.php';
    break;

case 'updateUser': /*Permet la modification d'un utilisateur deja existant*/
    $idUser = filter_input(INPUT_POST, 'idUser');
    $adresse = filter_input(INPUT_POST, 'adresse');
    $cp = filter_input(INPUT_POST, 'cp');
    $ville = filter_input(INPUT_POST, 'ville');
    $roleUser = filter_input(INPUT_POST, 'roleUser');

    if (isset($_POST['modif_user'])) {
        $pdo->updateUser($idUser, $adresse, $cp, $ville, $roleUser);
         echo '<script type="text/javascript">window.alert("utilisateur  modifié ");</script>';
    }
    include 'vues/v_updateUser.php';
    break;

case 'deleteUser': /* Permet la suppression d'un utilisateur de l'intranet*/
    $idUser = filter_input(INPUT_POST, 'idUser');
    if (isset($_POST['delete_user'])) {
        $pdo->deleteUser($idUser);
         echo '<script type="text/javascript">window.alert("utilisateur  supprimé ");</script>';
    }
    include 'vues/v_deleteUser.php';
    break;


}

