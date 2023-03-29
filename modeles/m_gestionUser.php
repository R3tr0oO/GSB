<?php
/**
 * Fonctions pour l'application GSB
 */

/**
 * Teste si un quelconque visiteur est connecté
 */
function estConnecte()
{
    return isset($_SESSION['idUtilisateur']);//isset retourne vrai si le parametre a l'interieur est defini et sinon retourne false  isset(var[parametre]) session //SESSION EN MAJ est une variable superglobale qui contient plusieurs autres variables idvisiteur nom prenom...
}
function action(){
    $action = filter_input(INPUT_GET, 'action');
    echo $action;
}
/**
 * Enregistre dans une variable session les infos d'un visiteur
 */
function connecter($idUtilisateur, $nom, $prenom, $statut)
{
    $_SESSION['idUtilisateur'] = $idUtilisateur;//on met les variables dans la superglobale que fait on de nos 4 variables est ce qu'elles sont vides ou bien elles  rentrent dans la superglobale????????
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
    $_SESSION['statut']=$statut;
}
function VisiteurSelectionne($idVisiteur)
{
    $_SESSION['idVisiteur'] = $idVisiteur;//on met les variables dans la superglobale que fait on de nos 4 variables est ce qu'elles sont vides ou bien elles  rentrent dans la superglobale????????
   
}

function MoiSelectionne($mois)
{
    $_SESSION['mois'] = $mois;//on met les variables dans la superglobale que fait on de nos 4 variables est ce qu'elles sont vides ou bien elles  rentrent dans la superglobale????????
   
}

/**
 * Détruit la session active
 */
function deconnecter()
{
    session_destroy();
}

/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais
 * aaaa-mm-jj
 */
function dateFrancaisVersAnglais($maDate)
{
    @list($jour, $mois, $annee) = explode('/', $maDate);
    return date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));
}

/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format
 * français jj/mm/aaaa
 */
function dateAnglaisVersFrancais($maDate)
{
    @list($annee, $mois, $jour) = explode('-', $maDate);
    $date = $jour . '/' . $mois . '/' . $annee;
    return $date;
}

/**
 * Retourne le mois au format aaaamm selon le jour dans le mois
 */
function getMois($date)
{
    @list($jour, $mois, $annee) = explode('/', $date);
    unset($jour);
    if (strlen($mois) == 1) {
        $mois = '0' . $mois;
    }
    return $annee . $mois;
}

/* gestion des erreurs */

/**
 * Indique si une valeur est un entier positif ou nul
 */
function estEntierPositif($valeur)
{
    return preg_match('/[^0-9]/', $valeur) == 0;
}
/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques
 */
function lesQteFraisValides($lesFrais)
{
   
    return estTableauEntiers($lesFrais);
}
/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 */
function estTableauEntiers($tabEntiers)
{
  
    $boolReturn = true;
    foreach ($tabEntiers as $unEntier) {
        
        if (!estEntierPositif($unEntier)) {
            $boolReturn = false;
        } 
    }
    return $boolReturn;
}

/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 */
function estDateDepassee($dateTestee)
{
    $dateActuelle = date('d/m/Y');
    @list($jour, $mois, $annee) = explode('/', $dateActuelle);
    $annee--;
    $anPasse = $annee . $mois . $jour;
    @list($jourTeste, $moisTeste, $anneeTeste) = explode('/', $dateTestee);
    return ($anneeTeste . $moisTeste . $jourTeste < $anPasse);
}

/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa
 */
function estDateValide($date)
{
    $tabDate = explode('/', $date);
    $dateOK = true;
    if (count($tabDate) != 3) {
        $dateOK = false;
    } else {
        if (!estTableauEntiers($tabDate)) {
            $dateOK = false;
        } else {
            if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
                $dateOK = false;
            }
        }
    }
    return $dateOK;
}



/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais
 */
function valideInfosFrais($dateFrais, $libelle, $montant)
{
    if ($dateFrais == '') {
        ajouterErreur('Le champ date ne doit pas être vide');
    } else {
        if (!estDatevalide($dateFrais)) {
            ajouterErreur('Date invalide');
        } else {
            if (estDateDepassee($dateFrais)) {
                ajouterErreur(
                    "date d'enregistrement du frais dépassé, plus de 1 an"
                );
            }
        }
    }
    if ($libelle == '') {
        ajouterErreur('Le champ description ne peut pas être vide');
    }
    if ($montant == '') {
        ajouterErreur('Le champ montant ne peut pas être vide');
    } elseif (!is_numeric($montant)) {
        ajouterErreur('Le champ montant doit être numérique');
    }
}

/**
 * Ajoute le libellé d'une erreur au tableau des erreurs
 */
function ajouterErreur($msg)
{
    if (!isset($_REQUEST['erreurs'])) {
        $_REQUEST['erreurs'] = array();
    }
    $_REQUEST['erreurs'][] = $msg;
}

/**
 * Retoune le nombre de lignes du tableau des erreurs
 */
function nbErreurs()
{
    if (!isset($_REQUEST['erreurs'])) {
        return 0;
    } else {
        return count($_REQUEST['erreurs']);
    }
}
