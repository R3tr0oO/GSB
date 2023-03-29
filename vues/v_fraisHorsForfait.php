
<hr>
</br>


 <div>
    <div>
        <div>Descriptif des éléments hors forfait</div>
        <table class="table">
            <thead>
                <tr>
                    <th class="date">Date</th>
                    <th class="libelle">Libellé</th>  
                    <th class="montant">Montant</th>  
                    <th class="action">&nbsp;</th> 
                </tr>
            </thead>  
            <tbody>
     
               
            <?php
            $nbJustificatifs=0;
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
  
                $nbJustificatifs++;
                
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                //$libelle=  substr($libelle,0,20);
               $date = $unFraisHorsForfait['date'];
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id'];
                ?>     
            <form action="index.php?uc=corriger_frais&action=horsforfait"  method="post" role="form"> 
                 
                <tr> 
                   
                    <td><input class="conteneur-form"  type="text" id="iddate" size="10" name="lesFraisD[<?php echo $id ?>]" value="<?php echo $date ; ?>" ></td>
                    <td><input class="conteneur-form" type="text" id="idlib" size="10"  name="lesFraisL[<?php echo $id ?>]" value="<?php echo $libelle ; ?>" ></td>    
                    <td><input class="conteneur-form" type="text" id="idmontant" size="10" name="lesFraisM[<?php echo $id ?>]" value="<?php echo $montant ;?>" ></td>
                     <input id="id"  name="FraisHorsForfait[<?php echo $id ?>]" value="<?php echo $id ?>" type="hidden">  <!-- pour pouvoir recuperer tous les id et ansi modifier chaque champ selon son id -->
                     <td><button class="button-classic" type="submit" class="btn btn-default" name="corriger" value="corriger">Corriger</button>
                         <button class="button-classic" type="submit" class="btn btn-default" name="reporter" value="reporter">reporter</button>
                         <button class="button-classic-d" type="reset" class="btn btn-default" >Reinitialiser</button> </td>     
          <!--  <input class="form-control" value="<?php //echo $nbJustificatifs; ?>" name="nbJustificatifs" type="hidden"> -->
          
               </form> 
    </tr>
    </div>                           
<?php
                     
            }
       
            ?>
      </div>

  </tbody>   
        </table>
       
             </div>
       
<div class="form-group ">
    <form action="index.php?uc=corriger_frais&action=valider_frais"  method="post" role="form"> 
     <label>Nombre de justifiquatifs:</label>
    <input class="form-control" id="nbJustificatifs" name="nbJustificatifs" value="<?php echo $nbJustificatifs; ?>" name="nbJustificatifs" style="width:50px;display:inline;">
 
        
     <div class="">
        <button class="button-classic" type="submit">Valider</button> 
        <button class="button-classic-d" type="reset">Reinitialiser</button>  
     <div>
    </form></div><br><br>
             
             
              </div>
                </div>