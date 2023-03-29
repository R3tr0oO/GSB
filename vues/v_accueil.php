<div id="accueil">  <?php 
if($_SESSION['statut']== 'visiteur' ){
  echo"  <h2>
        Gestion des frais<small> - Visiteur : ";
           
          
            echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']
            ?></small>
    </h2>
</div>

<?php
}elseif($_SESSION['statut']== 'admin' ){
?>
<div id="accueil">
    
    <h2>
        Gestion BDD<small> - Admin: 
            <?php 
            echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']
            ?></small>
    </h2>
</div>
<div>
    <div>
        <div>
            <div>
                <h3>
                    <span></span>
                    Navigation
                </h3>
            </div>
            <div>
                <div>
                    <div>
                        <a href="index.php?uc=administration&action=createUser"
                           role="button" class="button-classic">
                            <span></span>
                            <br>Créer user</a>
                             <a href="index.php?uc=administration&action=updateUser"
                                role="button" class="button-classic">
                            <span></span>
                            <br>Modifier user </a>
                             <a href="index.php?uc=administration&action=deleteUser"
                                role="button" class="button-classic">
                            <span></span>
                            <br>Delete user </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
}else{
    ?>
    <div id="accueil">
        
        <h2>
            Gestion des frais<small> - Comptable: 
                <?php 
                echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']
                ?></small>
        </h2>
    </div>

    <?php 
    }
?>