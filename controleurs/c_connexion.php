<?php


$action = filter_input(INPUT_GET, 'action');
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
case 'demandeConnexion':
    include 'vues/v_connexion.php';
    break;
case 'valideConnexion'://on a juste validé le mot de passe et l'identifiant mais on est pas allé encore a une autre page
    $login = filter_input(INPUT_POST, 'login');
    $mdp = filter_input(INPUT_POST, 'mdp');
    $validLogin=false;
    $validMdp=false;

    //RegEx champ Login
    if (empty($login)){ //si champ vide: faux
        echo '<div class="alert-info"> Le login est un champ obligatoire. </div> <br/>';
    }
    elseif (!preg_match("/^[a-zA-Z]*$/",$login)) { //si format faux: faux
        echo '<div class="alert-info"> Pour le login, seulement les lettres minuscules, majuscules sont autorisés. </div> <br/>';
    }
    elseif(strlen($login)>20){ //si la taille est trop longue: faux
        echo '<div class="alert-info"> Le login est trop long. </div> <br/>';
    }
    else{
        $validLogin=true;
        $login_hash = hash("sha3-512", $login);
    }
    
    //RegEx champ mot de passe
    if (empty($mdp)){ //si champ vide: faux
        echo ' <div class="alert-info"> Le mot de passe est un champ obligatoire. </div> <br/>';
    }
    elseif (!preg_match("/^[a-zA-Z0-9]*$/",$mdp)) { //si format faux: faux
        echo '<div class="alert-info"> Pour le mot de passe, seulement les lettres minuscules, majuscules sont autorisés. </div> <br/>';
        
    }
    
    else{
        $validMdp=true;
        $mdp_hash = hash("sha3-512", $mdp); // hash du mdp
    }
    
    if($validLogin==true and  $validMdp==true){
            $visiteur = $pdo->getInfosVisiteur($login_hash, $mdp_hash);//dans variable visiteur ya un tableau avec les infos id, nom et prénom
            $comptable= $pdo->getInfosComptable($login_hash, $mdp_hash);
            $ad= $pdo->getInfosAd($login_hash, $mdp_hash);
            if (!is_array($visiteur) && !is_array($comptable)&& !is_array($ad)) {//si la variable visiteur na pas de tableau(array) alors...
                ajouterErreur('Login ou mot de passe incorrect');
                include 'vues/v_erreurs.php';
                include 'vues/v_connexion.php';
            } else {
                if(is_array($visiteur)) {//on a séparé les variables du tableau
                    $idUtilisateur = $visiteur['id'];
                    $nom = $visiteur['nom'];
                    $prenom = $visiteur['prenom'];
                    $statut = 'visiteur';//pour que par la suite on ditingue facilement visiteur de comptable
                } elseif(is_array ($comptable)) {
                    $idUtilisateur = $comptable['id'];
                    $nom = $comptable['nom'];
                $prenom = $comptable['prenom'];   
                $statut = 'comptable';
                } elseif(is_array ($ad)) {
                    $idUtilisateur = $ad['id'];
                    $nom = $ad['nom'];
                $prenom = $ad['prenom'];   
                $statut = 'admin';
                }
                connecter($idUtilisateur, $nom, $prenom,$statut);
                header('Location: index.php');//permet de renvoyer a une page avec les données existantes
            }
        }
    else {
        echo "<a href=\"index.php\">Cliquez ici pour revenir</a>";
    }

    break;

default:
    include 'vues/v_connexion.php';
    break;
}
