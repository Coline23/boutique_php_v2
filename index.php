<?php
include 'functions.php';
session_start();
include 'head.php';
?>

<body>
    <?php 
    include 'header.php';
    ?>

    <main>
       <?php 
       showArticles
       ?>
    </main>

    <?php 
    include 'footer.php';
    ?>