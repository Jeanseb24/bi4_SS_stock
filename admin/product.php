<?php 
    require "../config/session.php";

    // Vérification de l'authentification : accès réservé aux utilisateurs connectés
    if(!isset($_SESSION['email']) || !isset($_SESSION['id'])){
        header("Location: ../403.php");
        exit();
    }

    require_once "../config/connexion.php";
    require "functions.php";

    // Récupération de l'id du produit à afficher, avec validation stricte
    if(!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)){
        header("Location: ../404.php");
        exit();
    }

    // Récupération du produit ciblé, avec jointure pour obtenir le nom de la catégorie
    $product = fetchOne(
        $bdd,
        "SELECT products.id, products.name, products.price, products.cover, products.description, categories.name as category_name 
         FROM products 
         INNER JOIN categories ON products.id_category = categories.id 
         WHERE products.id = ?",
        [$_GET['id']]
    );

    // Si aucun produit ne correspond à cet id, on redirige vers une 404
    if(!$product){
        header("Location: ../404.php");
        exit();
    }

    // Construction du chemin d'image et vérification de sa présence (basé sur la BDD, pas file_exists)
    $coverPath = "../images/" . ($product['cover'] ?? '');
    $hasImage = !empty($product['cover']);
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<?php include("partials/head.php"); ?>
<body>
    <?php include("partials/nav.php"); ?>
    <div class="container-fluid py-5 mx-auto" style="max-width: 80vw;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Détail du produit</h2>
            <a href="products.php" class="btn btn-outline-primary my-3">
                &larr; Retour à la liste
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card">
                    <div class="row g-0">
                        <!-- Colonne gauche : image -->
                        <div class="col-md-5">

                            <?php if ($hasImage) : ?>

                                <img src="<?= htmlspecialchars($coverPath) ?>" class="img-fluid rounded-start w-100 h-100" alt="<?= htmlspecialchars($product['name']) ?>" style="object-fit: cover;">
                            
                            <?php else : ?>

                                <div class="bg-secondary rounded-start d-flex align-items-center justify-content-center h-100" 
                                    style="min-height: 300px;">
                                    <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                                </div>
                                
                            <?php endif; ?>
                        </div>

                        <!-- Colonne droite : infos -->
                        <div class="col-md-7">
                            <div class="card-body">
                                <h3 class="card-title"><?= htmlspecialchars($product['name']) ?></h3>

                                <h5>Catégorie</h5>
                                <p class="text-muted"><?= htmlspecialchars($product['category_name']) ?></p>

                                <h5>Description</h5>
                                <p class="text-muted"><?= htmlspecialchars($product['description']) ?></p>

                                <p class="card-text fs-4 fw-bold">
                                    <?= number_format($product['price'], 2, ',', ' ') ?>€
                                </p>

                                <div class="d-flex gap-2">
                                    <a href="updateProduct.php?id=<?= urlencode($product['id']) ?>" class="btn btn-warning">
                                        Modifier
                                    </a>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmation avant suppression -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation de suppression</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Voulez-vous vraiment<br>supprimer le produit <strong><?= htmlspecialchars($product['name']) ?></strong> ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Non</button>
                        <a href="products.php?delete=<?= urlencode($product['id']) ?>" class="btn btn-danger btn-sm">Supprimer</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>