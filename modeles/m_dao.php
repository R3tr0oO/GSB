<?php

class PdoGsb
{
  
    private static $serveur = 'mysql:host=localhost';
    private static $bdd = 'dbname=gsb_test';
    private static $user = 'root';
    private static $mdp = ''; 
    private static $monPdo;
    private static $monPdoGsb = null;//correspond a la connection a la base de donnée

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct()
    {
		
        PdoGsb::$monPdo = new PDO(
            PdoGsb::$serveur . ';' . PdoGsb::$bdd,
            PdoGsb::$user,
            PdoGsb::$mdp
        );
        PdoGsb::$monPdo->query('SET CHARACTER SET utf8');//pdo permet la connection mais aussi l'interaction avec la bdd et la il s'agit d'une requete ( PdoGsb::$monPdo->query)qui veut que tt la bdd soit codé en certains caracteres
    }
    /**
     * Méthode destructeur appelée dans qu'il n'y a plus de référence sur un
     * objet donné, ou dans n'importe quel ordre pendant la séquence d'arrêt.
     */
    public function __destruct()//se deconnecter
    {
        PdoGsb::$monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     */
    public static function getPdoGsb()// static:qui retourne tjrs pareil
    {//SI MA BASE DE DONN2E:PdoGsb N4EST PAS COnnéctée  refaire une connection
        if (PdoGsb::$monPdoGsb == null) {//si monPdoGsb == nul ca signifie que ya pas eu de connection  
            PdoGsb::$monPdoGsb = new PdoGsb();//une instance c qu'on realise tt ce qu'il ya dans le constructeur pdo (coorespond en php a la connection)
        }
        return PdoGsb::$monPdoGsb;
    } 
                                                           
    /**
     * Retourne les informations de l'admin
     */
    public function getInfosAd($login, $mdp)
            
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT visiteur.id AS id, visiteur.nom AS nom, '
            . 'visiteur.prenom AS prenom '
            . 'FROM visiteur '
            . 'WHERE visiteur.login = :unLogin AND visiteur.mdp = :unMdp and visiteur.idRole = 2'
        );
        $requetePrepare->bindParam(':unLogin', $login);//:unLogin correspond a $login
        $requetePrepare->bindParam(':unMdp', $mdp);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    /**
     * Retourne les informations d'un visiteur
     */
    public function getInfosVisiteur($login, $mdp)
            
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT visiteur.id AS id, visiteur.nom AS nom, '
            . 'visiteur.prenom AS prenom '
            . 'FROM visiteur '
            . 'WHERE visiteur.login = :unLogin AND visiteur.mdp = :unMdp and visiteur.idRole = 0'
        );
        $requetePrepare->bindParam(':unLogin', $login);//:unLogin correspond a $login
        $requetePrepare->bindParam(':unMdp', $mdp);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }
    
    /**
     * Retourne les visiteurs qui ont une fiche cloturé qui doit etre valider
     */
     public function getVisiteur($etatRechercher){
                 if ($etatRechercher=="CL"){
        $requetePrepare = PdoGSB::$monPdo->prepare(
          'SELECT DISTINCT visiteur.nom AS nom, visiteur.prenom AS prenom, visiteur.id AS id '
            . 'FROM visiteur '
            .'INNER JOIN fichefrais ON visiteur.id=fichefrais.idvisiteur '   
                . ' Where idetat= :unIdEtat'
       );
        $requetePrepare->bindParam(':unIdEtat', $etatRechercher);
            }else{
       $R= "MP";
       $RB='RB';
       $VA='VA';
        $requetePrepare = PdoGSB::$monPdo->prepare(
              'SELECT DISTINCT visiteur.nom AS nom, visiteur.prenom AS prenom, visiteur.id AS id '
            . 'FROM visiteur '
            .'INNER JOIN fichefrais ON visiteur.id=fichefrais.idvisiteur '
            . 'Where fichefrais.idetat = :unIdEtat OR fichefrais.idetat = :remb OR fichefrais.idetat = :mp '
            . 'ORDER BY visiteur.nom desc'
                         
            
        ); 
          $requetePrepare->bindParam(':unIdEtat',$VA);
           $requetePrepare->bindParam(':mp',$R ); 
      $requetePrepare->bindParam(':remb',$RB ); 
            }
        $requetePrepare->execute();
          $nom = array();
        $rowAll=$requetePrepare->fetchAll(PDO::FETCH_BOTH);
        foreach( $rowAll as $row )
        {
            $nom[]=$row['nom'];
            $nom[]=$row['prenom'];
            $nom[]=$row['id'];
             
        }
     return $nom ;
 
     }

    /** Retourne les informations d'un comptable
     */
    public function getInfosComptable($login, $mdp)
    {
    
		
		$requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT visiteur.id AS id, visiteur.nom AS nom, '
            . 'visiteur.prenom AS prenom '
            . 'FROM visiteur '
            . 'WHERE visiteur.login = :unLogin AND visiteur.mdp = :unMdp and visiteur.idRole = 1'
			 );
        $requetePrepare->bindParam(':unLogin', $login);
        $requetePrepare->bindParam(':unMdp', $mdp);
        $requetePrepare->execute();
        return $requetePrepare->fetch();
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * hors forfait concernées par les deux arguments.
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT * FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraishorsforfait.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
        $requetePrepare->bindParam(':unMois', $mois);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        for ($i = 0; $i < count($lesLignes); $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

   
    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * au forfait concernées par les deux arguments
     */
    public function getLesFraisForfait($idVisiteur, $mois)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT fraisforfait.id as idfrais, '
            . 'fraisforfait.libelle as libelle, '
            . 'lignefraisforfait.quantite as quantite '
            . 'FROM lignefraisforfait '
            . 'INNER JOIN fraisforfait '
            . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait '
            . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraisforfait.mois = :unMois '
            . 'ORDER BY lignefraisforfait.idfraisforfait'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
        $requetePrepare->bindParam(':unMois', $mois);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }
    
 
    /**
     * Retourne tous les id de la table FraisForfait
     */
    public function getLesIdFrais()
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fraisforfait.id as idfrais '
            . 'FROM fraisforfait ORDER BY fraisforfait.id'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }
    
    
    /**
     * Met à jour la table ligneFraisForfait
     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais)
    {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $requetePrepare = PdoGSB::$monPdo->prepare( // on rajoute a la table lignefraisForfait la quantite pour tel utillisateur , tel mois 
                'UPDATE lignefraisforfait '
                . 'SET lignefraisforfait.quantite = :uneQte '   // les frais c'est a dire le forfait etape 
                . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraisforfait.mois = :unMois '
                . 'AND lignefraisforfait.idfraisforfait = :idFrais'   //pour quel type de frais (nuit restaurant ...)
            );
            $requetePrepare->bindParam(':uneQte', $qte, PDO::PARAM_INT);
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
            $requetePrepare->bindParam(':unMois', $mois);
            $requetePrepare->bindParam(':idFrais', $unIdFrais);
            $requetePrepare->execute();
        }
    }

     /**
     * Supprime un frais hors forfait
     * Supprime un frais hors forfait pour un visiteur et
     * un mois et un id, libelle de frais 
     */
   function SupprimerFrais($idVisiteur, $mois,$libelle,$idFrais){
   
        foreach ($idFrais as $unIdFraisH) {
         $qteL = $libelle[$unIdFraisH];
	    $qteL = 'Refuser-'.$qteL ;
          $requetePrepare = PdoGSB::$monPdo->prepare(         
         'UPDATE lignefraishorsforfait '
          
	        . 'SET lignefraishorsforfait.libelle = :libelle '   // les frais c'est a dire le forfait etape 
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois '
                . 'AND lignefraishorsforfait.id = :idFrais'   //pour quel type de frais (nuit restaurant ...)
            );
		  $requetePrepare->bindParam(':libelle', $qteL);
           $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
            $requetePrepare->bindParam(':unMois', $mois);
            $requetePrepare->bindParam(':idFrais', $unIdFraisH);
            $requetePrepare->execute();
			
    }
    }  
  
     /**
     * met un jour un frais hors forfait
     * met un jour un frais hors forfait pour un visiteur et
     * un mois et un id, libelle de frais 
     */
    
  public function majFraisForfaitHdd($idVisiteur, $mois , $idFrais,$libelle,$montant,$date)
    {
       foreach ($idFrais as $unIdFraisH) {
       $qteL = $libelle[$unIdFraisH];
       $qteM = $montant[$unIdFraisH];
       $qteD = $date[$unIdFraisH];
       $dateFr = dateFrancaisVersAnglais($qteD);
       $requetePrepare = PdoGSB::$monPdo->prepare(         
         'UPDATE lignefraishorsforfait '
                . 'SET lignefraishorsforfait.libelle = :libelle,' 
                . 'lignefraishorsforfait.montant = :montant,'
                . 'lignefraishorsforfait.date = :date '  // les frais c'est a dire le forfait etape 
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois '
                . 'AND lignefraishorsforfait.id = :idFrais'   //pour quel type de frais (nuit restaurant ...)
            );
          $requetePrepare->bindParam(':libelle', $qteL, PDO::PARAM_INT);
            $requetePrepare->bindParam(':montant', $qteM, PDO::PARAM_INT);
             $requetePrepare->bindParam(':date', $dateFr, PDO::PARAM_INT);
           $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
            $requetePrepare->bindParam(':unMois', $mois);
            $requetePrepare->bindParam(':idFrais', $unIdFraisH);
            $requetePrepare->execute();
    }
    
    }
    /**
     * Met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs)
    {

        $requetePrepare = PdoGsB::$monPdo->prepare(
            'UPDATE fichefrais '
            . 'SET nbjustificatifs = :unNbJustificatifs '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam( ':unNbJustificatifs',$nbJustificatifs,PDO::PARAM_INT);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
        $requetePrepare->bindParam(':unMois', $mois);
        $requetePrepare->execute();
    }

    /**
     * Teste si un visiteur possande une fiche de frais pour le mois passé en argument
     */
    public function estPremierFraisMois($idVisiteur, $mois)
    {
        $boolReturn = false;
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fichefrais.mois FROM fichefrais '
            . 'WHERE fichefrais.mois = :unMois '
            . 'AND fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unMois', $mois); //Représente les types de données CHAR, VARCHAR ou les autres types de données sous forme de chaÃ®ne de caractanres SQL. 
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
        $requetePrepare->execute();
        if (!$requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }
    
    /**
     * Teste si la fiche est dans l'etat MP pour 
     * pouvoir passer a l'etat RB
     */
    
    public function testEtat($idVisiteur,$mois)
    {
        
        $etat="MP";
         $boolReturn = false;
         $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fichefrais.mois FROM fichefrais '
            . 'WHERE fichefrais.mois = :unMois '
            . 'AND fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.idetat = :unEtat '
                 
        );
         $requetePrepare->bindParam(':unMois', $mois); //Représente les types de données CHAR, VARCHAR ou les autres types de données sous forme de chaÃ®ne de caractanres SQL. 
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
         $requetePrepare->bindParam(':unEtat', $etat);
        $requetePrepare->execute();
        if (!$requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur
     */
    public function dernierMoisSaisi($idVisiteur)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT MAX(mois) as dernierMois '
            . 'FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait
     * pour un visiteur et un mois donnés
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois)
    {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur); //Retourne le dernier mois en cours d'un visiteur
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois); //Retourne les informations d'une fiche de frais(montant, son etat ..) d'un visiteur pour un mois donne 
    
        if ($laDerniereFiche['idEtat'] == 'CR') { // fiche en cours de creation
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL'); // modifie l'etat de la fiche, la met a CL qui signifie saisie cloture
        }
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'INSERT INTO fichefrais (idvisiteur,mois,nbJustificatifs,'
            . 'montantValide,dateModif,idEtat) '
            . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR')"
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
        $requetePrepare->bindParam(':unMois', $mois);
        $requetePrepare->execute();
        $lesIdFrais = $this->getLesIdFrais(); //retourne tous les id de la table ficheforfait (ensemble d'id pour definir les fofrait nuit...)
        foreach ($lesIdFrais as $unIdFrais) {
            $requetePrepare = PdoGsb::$monPdo->prepare(
                'INSERT INTO lignefraisforfait (idvisiteur,mois,'
                . 'idFraisForfait,quantite) '
                . 'VALUES(:unIdVisiteur, :unMois, :idFrais, 0)'
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
            $requetePrepare->bindParam(':unMois', $mois);
            $requetePrepare->bindParam(
                ':idFrais',
                $unIdFrais['idfrais'],
                PDO::PARAM_STR
            );
            $requetePrepare->execute();
        }
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre
     */
    public function creeFraisHorsForfait(
        $idVisiteur,
        $mois,
        $libelle,
        $date,
        $montant,
        $idFrais
    ) {
        foreach ($idFrais as $unIdFraisH) {
         $libelle = $libelle[$unIdFraisH];
        $montant = $montant[$unIdFraisH];
        $date = $date[$unIdFraisH];
        $dateFr = dateFrancaisVersAnglais($date);
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'INSERT INTO lignefraishorsforfait '
            . 'VALUES (null, :unIdVisiteur,:unMois, :unLibelle, :uneDateFr,'
            . ':unMontant,null) '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
        $requetePrepare->bindParam(':unMois', $mois);
        $requetePrepare->bindParam(':unLibelle', $libelle);
        $requetePrepare->bindParam(':uneDateFr', $dateFr);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->execute();
    }
    
    }
    
    
        public function creeNouveauFraisHorsForfait(
        $idVisiteur,
        $mois,
        $libelle,
        $date,
        $montant
    ) {
       
        $dateFr = dateFrancaisVersAnglais($date);
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'INSERT INTO lignefraishorsforfait '
            . 'VALUES (null, :unIdVisiteur,:unMois, :unLibelle, :uneDateFr,'
            . ':unMontant,null) '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
        $requetePrepare->bindParam(':unMois', $mois);
        $requetePrepare->bindParam(':unLibelle', $libelle);
        $requetePrepare->bindParam(':uneDateFr', $dateFr);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->execute();
    }
    

    /**
     * Supprime le frais hors forfait dont l'id est passé en argument
     */
    public function supprimerFraisHorsForfait($idFrais)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'DELETE FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.id = :unIdFrais'
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais);
        $requetePrepare->execute();
    }
    
    /**
     * supprimer la fiche hors forfait lorsqu'elle est reporter selon
     * son ID et le mois
     */
     public function supprimerLeFraisHorsForfait($idFrais,$mois)
    {
         foreach ($idFrais as $id){
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'DELETE FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.id = :unIdFrais AND lignefraishorsforfait.mois = :mois '  
        );
        $requetePrepare->bindParam(':unIdFrais', $id);
         $requetePrepare->bindParam(':mois', $mois, PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    }
    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais
     */
    public function getLesMoisDisponibles($idVisiteur)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT fichefrais.mois AS mois FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
        $requetePrepare->execute();
        $lesMois = array();
     
        while ($laLigne = $requetePrepare->fetch()) {
           
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array( 
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois   
            );
    }//retourne le moi en fonction du statut des fiches de frais et du vsiteur
  return $lesMois;
    }
    
    
    /*Retourne tous les mois pour lesquel les utilisateurs ont 
     * une fiche cloture
     * ainsi le comptable pourra les valider
     * ou a une fiche MP VA RB selon l'etat mis en parametre
     */
    
        public function getLesMois($idVisiteur,$etat)
    {     
            if ($etat=="CL"){
                $CL='CL';
        $requetePrepare = PdoGSB::$monPdo->prepare(
           'SELECT fichefrais.mois AS mois FROM fichefrais '
           . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
          . 'AND fichefrais.idetat = :cl '
            . 'ORDER BY fichefrais.mois desc'
       );
           $requetePrepare->bindParam(':cl',$CL); 
            }else{
             $R= "MP";
             $RB='RB';
             $VA ='VA';
                $requetePrepare = PdoGSB::$monPdo->prepare(
           'SELECT DISTINCT fichefrais.mois AS mois FROM fichefrais '
           . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
          . 'AND fichefrais.idetat = :va OR fichefrais.idetat = :remb OR fichefrais.idetat = :mp '
            . 'ORDER BY fichefrais.mois desc'
        ); 
            $requetePrepare->bindParam(':mp',$R ); 
             $requetePrepare->bindParam(':va',$VA ); 
             $requetePrepare->bindParam(':remb',$RB ); 
            }
                
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
        $requetePrepare->execute();
        $lesMois = array();
           $i=0;    
        while ($laLigne = $requetePrepare->fetch()) {
            
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
          
        }
       
        return $lesMois;
    }
    
    
    
/*
     * Retourne les informations d'une fiche de frais d'un visiteur pour un
     * mois donné
  */
   public function getLesInfosFicheFrais($idVisiteur, $mois)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT fichefrais.idetat as idEtat, '
            . 'fichefrais.datemodif as dateModif,'
            . 'fichefrais.nbjustificatifs as nbJustificatifs, '
            . 'fichefrais.montantvalide as montantValide, '
            . 'etat.libelle as libEtat '
            . 'FROM fichefrais '
            . 'INNER JOIN etat ON fichefrais.idetat = etat.id '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
        $requetePrepare->bindParam(':unMois', $mois);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne;
    }
     /*
     * Retourne l'etat des fiches selon uc 
     * mois donné
     */
    public function uc_visit(){
        $uc = filter_input(INPUT_GET, 'uc');
        if($uc=='validerFrais'|| $uc=='corriger_frais'){
            
           $etatRechercher="CL";
   }else{
        $etatRechercher="VA";
   }
   
   return $etatRechercher;
        
    }
    /**
     * Modifie l'état et la date de modification d'une fiche de frais.
     * Modifie le champ idEtat et met la date de modif à aujourd'hui.
*/
         public function majEtatFicheFrais($idVisiteur, $mois, $etat)
    {
		
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'UPDATE fichefrais '
            . 'SET idetat = :unetat, datemodif = now() '
            . 'WHERE fichefrais.idvisiteur = :unidvisiteur '
            . 'AND fichefrais.mois = :unmois'
        );
        $requetePrepare->bindParam(':unetat', $etat);
        $requetePrepare->bindParam(':unidvisiteur', $idVisiteur);
        $requetePrepare->bindParam(':unmois', $mois);
        $requetePrepare->execute();
    }
	  /**
     * Modifie le montant valider en fonction de la fiche et des frais (forfait et hors forfait valider ) 
   */ 
	public function montantValider($idVisiteur,$mois,$quantite){
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'UPDATE fichefrais '
                 .'SET montantvalide = :montant '
                 . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                  . 'AND fichefrais.mois = :unMois'
                );
         $requetePrepare->bindParam(':montant', $quantite, PDO::PARAM_INT);
         $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur);
        $requetePrepare->bindParam(':unMois', $mois);
        $requetePrepare->execute();
    }

    
    /******************************************** parti administrateur**********************************/

    public function createUser($idUser, $nom, $prenom, $login, $mdp, $adresse, $cp, $ville, $dateEmbauche, $roleUser)
            
    {
        try{
            $mdphash = hash("sha3-512", $mdp); // hash du mdp
            $loginhash = hash("sha3-512", $login); // hash du login
            $requetePrepare = PdoGsb::$monPdo->prepare(
                'INSERT INTO visiteur VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?); '
            );
            $requetePrepare->execute([$idUser, $nom, $prenom, $loginhash, $mdphash, $adresse, $cp, $ville, $dateEmbauche, $roleUser]);
        } catch (PDOException $e) {
            echo($e->getMessage());
        }
    }

    public function updateUser($idUser, $adresse, $cp, $ville, $roleUser)
            
    {

        $requetePrepare = PdoGsb::$monPdo->prepare(
            'UPDATE visiteur set adresse = ?, cp = ?, ville = ?, idRole = ?    WHERE visiteur.id = ? ; '
        );
        $requetePrepare->execute([$adresse, $cp, $ville, $roleUser, $idUser]);

    } 

    public function deleteUser($idUser)
           
    {
        try { 
            $requetePrepare = PdoGsb::$monPdo->prepare(
                ' DELETE FROM visiteur WHERE visiteur.id = ? ; '
            );
            $requetePrepare->execute([$idUser]);
        }  catch (PDOException $e) {
            echo($e->getMessage());
        }
    
    }
     
}

