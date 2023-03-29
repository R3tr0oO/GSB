
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="UTF-8">
        <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title> 
        <meta name="author" content="PENKOV Miroslav,HERQUE Maxime et RAMOS Victor">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./styles/style.css">
      
    </head>
    <body>
        <div class="conteneur-general">
            <?php
            $uc = filter_input(INPUT_GET, 'uc');
            if ($estConnecte) {
                ?>
            <div class="header">
                <div>
                    <div>
                        <h1>
                            <img src="./images/logo.png" class="img-responsive" 
                                 alt="Laboratoire Galaxy-Swiss Bourdin" 
                                 title="Laboratoire Galaxy-Swiss Bourdin">
                        </h1>
                    </div>
                    <div>
                        <div class="btn-group" role="tablist">
                            <span <?php if (!$uc || $uc == 'accueil') { ?>class="active" <?php } ?>><!class="active"permet de cibler un élément lorsque celui ci est activé par l'utilisateur>
                                <a href="index.php">
                                <button class="button-classic">Accueil</button> &nbsp;
                                    
                                </a>
                            </span>
                            <span <?php if($_SESSION['statut']=='visiteur'){ // si l'utilisateur est un simple visiteur 
                                if ($uc == 'gererFrais') { ?>class="active"<?php } ?>>
                                <a href="index.php?uc=gererFrais&action=saisirFrais">
                                <button class="button-classic">Renseigner la fiche de frais</button> &nbsp;
                                    
                                </a>
                            </span>
                            
                            <span
                            <?php } elseif($_SESSION['statut']=='comptable'){ // quand l'utilisateur est comptable
                                if ($uc == 'gererFrais') { ?>class="active"<?php } ?>>
                                <a href="index.php?uc=validerFrais&action=listeVisiteurs"> 
                                <button class="button-classic">Valider la fiche de frais</button> &nbsp;

                                <?php
                            }
                            ?>
                           
                                </a>
                            </span>
                            <span <?php if($_SESSION['statut']=='visiteur'){
                            if ($uc == 'etatFrais') { ?>class="active"<?php } ?>>
                                <a href="index.php?uc=etatFrais&action=selectionnerMois">
                                <button class="button-classic"> Afficher mes fiches de frais</button> &nbsp;
                                   
                                </a>
                             </span>
                            <span <?php }elseif($_SESSION['statut']=='comptable'){
                               if ($uc == 'etatFrais') { ?>class="active"<?php } ?>>
                                <a href="index.php?uc=SuivreLePaiement&action=listeVisiteurs">
                                <button class="button-classic">Suivre le paiement des fiches de frais</button> &nbsp;
                                    
                                    <?php
                                    
                                }
                            ?>
                                </a>
                            </span>
                            
                            <span
                            <?php if ($uc == 'deconnexion') { ?>class="active"<?php } ?>>
                                <a href="index.php?uc=deconnexion&action=demandeDeconnexion">
                                <button class="button-classic-d">Déconnexion</button> &nbsp;
                                    
                                </a>
                            </span>
                    </div>
                </div>
            </div>
            <?php
      }else{  
           ?> 

                 
                <h1>
                    <img src="./images/logo.png"
                         alt="Laboratoire Galaxy-Swiss Bourdin"
                         title="Laboratoire Galaxy-Swiss Bourdin">
                </h1>
            <?php
               } 
               ?>