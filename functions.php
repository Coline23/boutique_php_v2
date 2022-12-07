<?php
session_start();


// ****************** les produits **********************

function getArticles()
{
    return [
        [
            'name' => 'Road Trip dans les Dolomites',
            'id' => '1',
            'price' => 580,
            'description' => 'Partez 10 jours en van pour découvrir ce petit coin de paradis dans les Alpes.',
            'detailedDescription' => 'Un road trip durant lequel les mots nous manquent et nos émotions sont décuplées. Un voyage dans les Dolomites, au cœur du Sud Tyrol, comblera de bonheur tous les amoureux de nature, d’outdoor et de montagnes mais pas que. C’est aussi la destination idéale pour se faire plaisir, prendre soin de soi et se retrouver en amoureux dans de belles adresses avec des spas et hôtels luxueux, le tout avec une gastronomie d’exception. Le Sud Tyrol, c’est un peu le meilleur des deux cultures (italienne et autrichienne).',
            'picture' => 'img-trip.jpg'
        ],
        [
            'name' => 'La Turquie enchantée',
            'id' => '2',
            'price' => 700,
            'description' => 'Partez à la découverte des merveilleux paysages de la Cappadoce et de la côte Turque.',
            'detailedDescription' => 'Quand on pense « Cappadoce », la première image qui nous vient en tête c’est assurément de colorées montgolfières qui survolent cheminées de fée et paysages spectaculaires. Nemrut Dagi : le site est à couper le souffle avec ses monuments funéraires datant du 1er siècle av. J.-C. à 2200 m d’altitude. Il règne une ambiance particulière sur le site.',
            'picture' => 'img-trip1.jpg'
        ],
        [
            'name' => 'Direction le grand froid',
            'id' => '3',
            'price' => 715,
            'description' => 'Découvrez les pays nordiques avec 10 jours de road trip entre la Norvège, Suède & Finlande.',
            'detailedDescription' => 'Profiter des grands espaces, de la nature et du calme, faire des randonnées, prendre enfin le temps d’apprécier l’instant présent… Les paysages sont ultras diversifiés. Dunes de sable au Danemark, fjords en Norvège, montagnes en Norvège et Suède, forêts boréales en Laponie… C’est LA destination pour les amoureux de la nature. Cette région historique d’Europe du Nord fait partie des pays les moins peuplés au monde. Et qui dit moins d’habitants, dit plus de nature !',
            'picture' => 'img-trip2.jpg'
        ]
    ];
}


// ****************** Card pour afficher les produits **********************

function showArticles()
{
    $articles = getArticles(); // RECUPERATION DES ARTICLES
    foreach ($articles as $article) {
        echo "<div class=\"card col-md-4 mx-auto m-5 p-4 text-center\" style=\"width: 25rem;\">
    <img src=\"./images/" . $article['picture'] . "\" class=\"card-img-top\" alt=\"images des produits\">
    <div class=\"card-body\">
        <h5 class=\"card-title\">" . $article['name'] . "</h5>
        <p class=\"card-text\">" . $article['description'] . "</p>
        
        <form action=\"product.php\" method=\"post\">
            <input type=\"hidden\" name=\"articleToDisplay\" value=\"" . $article['id'] . "\">
            <input class=\"btn btn-dark\" type=\"submit\" value=\"Détails voyage\">
        </form>

        <form action=\"panier.php\" method=\"post\">
            <input type=\"hidden\" name=\"chosenArticle\" value=\"" . $article['id'] . "\">
            <input class=\"btn btn-dark\" type=\"submit\" value=\"Je voyage !\">
        </form>

    </div>
</div>";
    }
}

// ****************** change ID en article **********************
function getArticleFromId($id)
{
    foreach (getArticles() as $article) {
        if ($article['id'] == $id) {
            return $article;
        }
    }
}

// ****************** créer le panier s'il n'existe pas **********************

function createCart()
{
    if (!isset($_SESSION['cart'])) { // si le panier n'existe pas
        $_SESSION['cart'] = array(); // je nl'initialise en tableau vide
    }
}

// ****************** ajouter un article dans le panier **********************

function addToCart($article)
{
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['id'] == $article['id']) {
            echo "<script> alert(\"Article déjà présent dans le panier !\");</script>";
            return;
        }
    }

    $article['quantity'] = 1;
    array_push($_SESSION['cart'], $article);
}

// ****************** retirer un article du panier **********************

function removeToCart($articleId)
{
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['id'] == $articleId) {
            array_splice($_SESSION['cart'], $i, 1);
            echo "<script> alert(\"Article retiré du panier\");</script>";
        }
    }
}

// ****************** mettre à jour les quantités du panier **********************

function updateQuantity()
{
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['id'] == $_POST['modif_article_id']) {
            $_SESSION['cart'][$i]['quantity'] = $_POST['nb_article'];
            echo "<script> alert(\"Quantité modifié !\");</script>";
        }
    }
}

// ****************** vider le panier **********************

function emptyCart()
{
    $_SESSION['cart'] = [];
    echo "<script> alert(\"Votre panier est vide !\");</script>";
}

// ****************** montant total du panier **********************

function totalPrice()
{
    $total = 0;
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        $total += $_SESSION['cart'][$i]['price'] * $_SESSION['cart'][$i]['quantity'];
    }
    return $total;
}


// ****************** frais de port **********************

function totalShipment()
{
    $total = 0;
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        $total += 30 * $_SESSION['cart'][$i]['quantity'];
    }
    return $total;
}


// ****************** total à payer **********************

function totalToPay()
{
    return totalPrice() + totalShipment();
}


// ****************** date expédition **********************

function dateExpedition()
{
    // date affichée ainsi : 6 juin 2021
    // passage au fuseau horaire français
    setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

    // récupération de la date actuelle => 2022-11-04
    $date = date("Y-m-d");

    // on obtient : vendredi 4 novembre 2022
    echo utf8_encode(strftime("%A %d %B %Y", strtotime($date . " + 2 days")));

    // date version 2 : date affichée ainsi : 06-06-2021
    // echo date('d-m-Y', strtotime(date('d-m-Y') . ' + 3 days')); 
}


// ****************** date livraison **********************

function dateLivraison()
{
    // date affichée ainsi : 6 juin 2021
    // passage au fuseau horaire français
    setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

    // récupération de la date actuelle => 2022-11-04
    $date = date("Y-m-d");

    // on obtient : vendredi 4 novembre 2022
    echo utf8_encode(strftime("%A %d %B %Y", strtotime($date . " + 30 days")));

    // date version 2 : date affichée ainsi : 06-06-2021
    // echo date('d-m-Y', strtotime(date('d-m-Y') . ' + 3 days')); 
}
