<?php
/* Vue Liste des mois*/
?>
<h2>Mes fiches de frais</h2>
<div class="row">
    <div class="col-md-4">
        <h3>Sélectionner un mois : </h3>
    </div>
    <div class="col-md-4">
        <form action="index.php?uc=etatFrais&action=voirEtatFrais" 
              method="post" role="form">
            <div class="form-group">
                <label for="lstMois" accesskey="n">Mois : </label>
                 
                <select id="lstMois" name="lstMois" class="form-control">
                   
                    <?php
                     
                    foreach ($lesMois as $unMois) {
                      
                        $mois = $unMois['mois'];
                        
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        if ($mois == $moisASelectionner) {
                            ?>
                            <option selected value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $mois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        }
                       
                    }
                    ?>    

                </select>
            </div>
            
            <button>
            <input id="ok" type="submit" value="Valider" class="button-classic" 
                   role="button">
            </button>
            <button>
            <input id="annuler" type="reset" value="Effacer" class="button-classic-d" 
                   role="button"> 
            </button>
        </form>
    </div>
</div>