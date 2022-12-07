<?php
session_start();
include 'functions.php';
include 'head.php';
?>

<body>
    <?php
    include 'header.php';
    ?>
    
    <main>
        <div class="container">
            <div class="row">
            <?php
            $article = getArticleFromId($_POST['articleToDisplay']);
            ?>
        
            <div class="card col md-6 mx-auto text-center" style="width: 20rem;">
                <div class="row mx-auto mt-5" id="imgart">
                    <img src="./images/<?php echo $article['picture'] ?>" class="card-img-top" alt="images des produits" style="width: 30rem">
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $article['name'] ?></h5>
                    <p class="card-text"><?php echo $article['detailedDescription'] ?></p>
                </div>
                
                <form action="panier.php" method="post">
                    <input type="hidden" name="chosenArticle" value="" <?php echo $article['id'] ?>>
                    <input class="btn btn-dark" type="submit" value="Je voyage !">
                </form>
            </div>
            </div>
        </div>
    </main>
    
    <?php
    include 'footer.php';

?>
