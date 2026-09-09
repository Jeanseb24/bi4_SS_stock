<?php 
    require "../config/session.php";
    
    if(!isset($_SESSION['email']) || !isset($_SESSION['id'])){
        header("Location: ../403.php");
        exit();
    }

    if(isset($_GET['deco'])){
        session_destroy();
        unset($_SESSION['email']);
        unset($_SESSION['id']);
        header("Location: index.php");
        exit();
    }

    require "../config/connexion.php";
    require "functions.php";

    // Nombre total de produits
    $totalProducts = fetchOne($bdd, "SELECT COUNT(*) AS total FROM products");

    // Nombre total de catégories
    $totalCategories = fetchOne($bdd, "SELECT COUNT(*) AS total FROM categories");

    // Valeur totale du stock (somme des prix)
    $stockValue = fetchOne($bdd, "SELECT SUM(price) AS total FROM products");

    // Prix moyen
    $avgPrice = fetchOne($bdd, "SELECT AVG(price) AS moyenne FROM products");

    // Répartition produits par catégorie
    $productsByCategory = fetchAll($bdd, "
        SELECT categories.name AS category_name, COUNT(products.id) AS nb_products
        FROM categories
        LEFT JOIN products ON products.id_category = categories.id
        GROUP BY categories.id, categories.name
        ORDER BY nb_products DESC
    ");

    // Produits sans image
    $productsNoImage = fetchAll($bdd, "SELECT id, name FROM products WHERE cover IS NULL OR cover = ''");

    // Derniers produits ajoutés (les 5 plus récents par id décroissant)
    $recentProducts = fetchAll($bdd, "SELECT id, name, price FROM products ORDER BY id DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<?php include("partials/head.php"); ?>
<body>
    <?php include("partials/nav.php"); ?>
    <div class="container-fluid py-5 mx-auto" style="width: 80vw;">
        <h2 class="mb-4">Tableau de bord</h2>

        <!-- KPIs -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-box-seam fs-2 text-primary"></i>
                        <h3 class="mt-2"><?= (int) $totalProducts['total'] ?></h3>
                        <p class="text-muted mb-0">Produits</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-tags fs-2 text-success"></i>
                        <h3 class="mt-2"><?= (int) $totalCategories['total'] ?></h3>
                        <p class="text-muted mb-0">Catégories</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-currency-euro fs-2 text-warning"></i>
                        <h3 class="mt-2"><?= number_format($stockValue['total'] ?? 0, 2) ?> €</h3>
                        <p class="text-muted mb-0">Valeur totale du stock</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-graph-up fs-2 text-info"></i>
                        <h3 class="mt-2"><?= number_format($avgPrice['moyenne'] ?? 0, 2) ?> €</h3>
                        <p class="text-muted mb-0">Prix moyen</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <!-- Répartition par catégorie -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">Produits par catégorie</div>
                    <div class="card-body">
                        <?php if (empty($productsByCategory)) : ?>
                            <p class="text-muted mb-0">Aucune donnée disponible.</p>
                        <?php else : ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($productsByCategory as $cat) : ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= htmlspecialchars($cat['category_name']) ?>
                                        <span class="badge bg-primary rounded-pill"><?= (int) $cat['nb_products'] ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Derniers produits ajoutés -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">Derniers produits ajoutés</div>
                    <div class="card-body">
                        <?php if (empty($recentProducts)) : ?>
                            <p class="text-muted mb-0">Aucun produit enregistré.</p>
                        <?php else : ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($recentProducts as $p) : ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= htmlspecialchars($p['name']) ?>
                                        <span><?= number_format($p['price'], 2) ?> €</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Produits sans image -->
            <?php if (!empty($productsNoImage)) : ?>
                <div class="col-12">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <i class="bi bi-exclamation-triangle"></i> Produits sans image (<?= count($productsNoImage) ?>)
                        </div>
                        <div class="card-body">
                            <ul class="mb-0">
                                <?php foreach ($productsNoImage as $p) : ?>
                                    <li>
                                        <a href="updateProduct.php?id=<?= urlencode($p['id']) ?>">
                                            <?= htmlspecialchars($p['name']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>