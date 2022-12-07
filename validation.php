<?php
session_start();
include 'functions.php';
include 'head.php';

// màj les quantités du panier
if (isset($_POST['nb_article'])) {
    updateQuantity();
}

// supprimer un article
if (isset($_POST['delete_article_id'])) {
    $articleId = $_POST['delete_article_id'];
    removeToCart($articleId);
}

// si je reçois un input qui a le name chosenArticle
// = si je viens d'un bouton Valider le panier
if (isset($_POST['chosenArticle'])) {
    $chosenArticleId = $_POST['chosenArticle'];
    $article = getArticleFromId($chosenArticleId);
    addToCart($article);
}

// supprimer le panier
if (isset($_POST['delete_cart_id'])) {
    emptyCart(true);
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
                    <h4>Récapitulatif de votre commande</h4>
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
                                <form action="validation.php" method="POST">
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
                                <form action="validation.php" method="POST">
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
                    <div class="col-md-4 mx-auto p-2 mb-5 text-bg-light" style="font-size:20px">
                        <?= "Votre panier s'élève à : " . "<b> " . totalPrice() . "</b>" . " €"; ?>
                    </div>
                </div>
            </div>

            <!-- Btn Vider le panier -->
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-4 mx-auto p-2 mb-5 text-bg-light" style="font-size:20px">
                        <?= "Taxe de séjour (30€ par voyage) : " . "<b> " . totalShipment() . "</b>" . " €"; ?>
                    </div>
                </div>
            </div>
            <!-- Total à payer -->
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-4 mx-auto p-2 mb-5 text-bg-light" style="font-size:28px">
                        <?= "TOTAL À PAYER : " . "<b> " . totalToPay() . "</b>" . " €"; ?>
                    </div>
                </div>
            </div>
            <!-- Btn Confirmer l'achat -->
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-4 mx-auto p-2 mb-5 text-dark" style="font-size:28px">
                        <form action="validation.php" method="POST">
                            <input type="hidden" name="validate_article_id" value="" . <?php echo $article['id'] ?>>
                            <button class="btn btn-success btn-lg" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">Confirmer l'achat</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Livraison validée -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Félicitation !</h1>
                        </div>
                        <div class="modal-body">
                            <h2>Votre commande a été validée.</h2>
                            <h4>Montant total : <?= "<b> " . totalToPay() . "</b>" . " €"; ?></h4>
                            <p>Elle sera expédiée le <strong><?= dateExpedition() ?></strong></p>
                            <p>Livraison prévue le <strong><?= dateLivraison() ?></strong></p>
                            <p>Merci pour votre confiance</p>
                        </div>
                        <div class="modal-footer">
                            <form action="index.php" method="POST">
                                <input type="hidden" name="validation_commande">
                                <button class="btn btn-secondary" type="submit" data-bs-dismiss="modal" name="delete_cart">Retour à l'accueil</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </main>

    <?php
    include 'footer.php';
