<?php
/** Vue Liste des frais au forfait **/

if($_SESSION['statut']== 'visiteur' ){ // si l'utilisateur est un visiteur
?>
<div class="row" style="float: center;">  
 
    <h2>Renseigner ma fiche de frais du mois 
        <?php echo $numMois . '-' . $numAnnee ?>
    </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="conteneur-general" >
        <form method="post" 
              action="index.php?uc=gererFrais&action=validerMajFraisForfait" 
              role="form">
            <fieldset class="fieldset">       
                <?php
}else{ 
// si l'utilisateur est un comptable
     ?>
              
   <div class="conteneur-general">
        
               <div class="mx-auto"  >  
             </br>
			 </br>
                  <h2 style="color:red">Valider la fiche de frais </h2>
     <h3>Eléments forfaitisés</h3>
     <div class="col-md-5" style="float:none;">
         <form action="index.php?uc=corriger_frais&action=validerMajFraisForfaitt"  method="post" role="form">
            <fieldset class="fieldset">  
                <?php
}
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite']; ?>
                    <div class="form-group">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">
                        </div>
                
                        <?php
                }
                ?>
                </div>  
                </div>
   
                <div >
               <button class="button-classic " type="submit">Ajouter</button>
                <button class="button-classic-d " type="reset">Réinitialiser</button>
            </fieldset>
        </form>
    </div>
</div>
  
