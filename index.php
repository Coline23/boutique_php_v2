<?php
session_start();
include 'functions.php';
createCart();
include 'head.php';

//Vider le panier une fois qu'on retourne à l'accueil
if (isset($_POST['validation_commande'])) {
  emptyCart();
}

?>

<body>
    <?php 
    include 'header.php';
    ?>

    <main>
      <div class="container">
         <div class="row">
         <?php 
       showArticles();
       ?>
         </div>
      </div>
    </main>

    <?php 
    include 'footer.php';
    ?>