<?php
session_start();
include 'functions.php';
include 'head.php';

createCart();

//mettre à jour la quantité du panier
if (isset($_POST['nb_article'])) {
    updateQuantity();
}
// supprimer un article
if (isset($_POST['delete_article_id'])) {
    $articleId = $_POST['delete_article_id'];
    removeToCart($articleId);
}

// si je reçois un input qui a le name chosenArticle
// = si je viens d'un bouton Ajout au panier
if (isset($_POST['chosenArticle'])) {
    $chosenArticleId = $_POST['chosenArticle'];
    $article = getArticleFromId($chosenArticleId);
    addToCart($article);
}

// supprimer le panier
if (isset($_POST['delete_cart_id'])) {
    emptyCart();
}

?>

<body>
    <?php
    include 'header.php';
    ?>

    <main>
        <div class="container">
            <div class="row text-center home-title">
                <div class="col-md-4 mx-auto p-2 mb-5" style="font-size:18px">
                    <h4>Votre panier</h4>
                </div>
            </div>
        </div>
        <!-- Afficher le panier s'il y a des articles -->
        <?php
        if (count($_SESSION['cart']) > 0) {
            foreach ($_SESSION['cart'] as $article) {
        ?>
        <!-- Les articles du panier -->
                <div class="card mx-auto m-12" style="max-width: 1500px;">
                    <div class="row g-0 bg-success text-white">
                        <div class="col-md-2 my-auto mx-auto">
                            <img src="./images/<?php echo $article['picture'] ?>" class="card-img-top p-5" alt="images des produits" style="width:18rem">
                        </div>
                        <div class="col-md-2 my-auto mx-auto">
                            <h5 class="card-title" style="font-weight:600"><?php echo $article['name'] ?></h5>
                        </div>
                        <div class="col-md-2 my-auto mx-auto">
                            <h5 class="card-title" style="font-size:12px; font-style:italic"><?php echo $article['description'] ?></h5>
                        </div>
                        <div class="col-md-1 my-auto mx-auto">
                            <h5 class="card-title" style="font-weight:600"><?php echo $article['price'] . " €" ?></h5>
                        </div>
                        <div class="col-md-3 my-auto mx-auto">
                            <div class="card-body">
                                <form action="panier.php" method="POST">
                                    <input type="hidden" name="modif_article_id" value="<?php echo $article['id'] ?>">
                                    <input type="number" min="1" max="10" name="nb_article" value="<?php echo $article['quantity'] ?>" style="width:50px; background-color:azure">
                                    <button class="btn btn-dark" type="submit" name="modif_quantity">Modifier la quantité</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-1 my-auto mx-auto">
                            <h5 class="card-title" style="font-weight:600"><?php echo $article['price'] * $article['quantity'] . " €" ?></h5>
                        </div>
                        <div class="col-md-1 my-auto mx-auto">
                            <div class="card-body">
                                <form action="panier.php" method="POST">
                                    <input type="hidden" name="delete_article_id" value="<?php echo $article['id'] ?>">
                                    <button class="btn btn-dark" type="submit" name="delete_quantity">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <!-- Total panier -->
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-4 mx-auto p-2 mb-5 text-bg-light" style="font-size:28px">
                        <?= "Votre panier s'élève à : " . "<b> " . totalPrice() . "</b>" . " €"; ?>
                    </div>
                </div>
            </div>
            <!-- Btn Vider le panier -->
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-12 mx-auto p-12 mb-5 text-dark" style="font-size:28px">
                        <form action="panier.php" method="POST">
                            <input type="hidden" name="delete_cart_id" value="<?php echo $article['id'] ?>">
                            <button class="btn btn-danger btn-lg" type="submit" name="delete_cart">Vider le panier</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Btn Valider la commande -->
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-4 mx-auto p-2 mb-5 text-dark" style="font-size:28px">
                        <form action="validation.php" method="POST">
                            <input type="hidden" name="validate_article_id" value="" . <?php echo $article['id'] ?>>
                            <button class="btn btn-success btn-lg" type="submit" name="validate_cart">Valider la commande</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </main>

    <?php
    include 'footer.php';
    ?>