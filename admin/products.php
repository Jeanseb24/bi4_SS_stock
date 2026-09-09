<?php 
    require "../config/session.php";
    
    if(!isset($_SESSION['email']) || !isset($_SESSION['id'])){
        header("Location: ../403.php");
        exit();
    }

    require_once "../config/connexion.php";
    require "functions.php";

    if(isset($_GET['delete']) && filter_var($_GET['delete'], FILTER_VALIDATE_INT)){
        $delete = fetchOne($bdd, "SELECT * FROM products WHERE id=?", [$_GET['delete']]);
        if(!$delete){

            header("Location: ../404.php");
            exit();
        } else {

            if(file_exists("../images/".$delete['cover'])){
                unlink("../images/".$delete['cover']);
            }
        }


        $result = execute($bdd, "DELETE FROM products WHERE id=?", [$_GET['delete']]);

    }
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<?php include("partials/head.php"); ?>
<body>
    <?php include("partials/nav.php"); ?>
    <div class="container-fluid px-3 px-md-5 py-5 mx-auto" style="max-width: 1400px;">
        <h2>Gestion des produits</h2>
        
        <?php

            $categories = fetchAll($bdd, "SELECT * FROM categories ORDER BY name ASC");


            $selectedCategory = $_GET['category'] ?? '';


            $products = fetchAll(
                $bdd,
                "SELECT products.id, products.name, categories.name as category_name, products.price, products.cover 
                FROM products 
                INNER JOIN categories ON products.id_category = categories.id" . ($selectedCategory !== '' ? " WHERE products.id_category = ?" : "") . " ORDER BY products.id ASC",

                $selectedCategory !== '' ? [$selectedCategory] : []
            );
        ?>
        
        <!-- Barre d'outils (Ajout, Recherche, Filtrage) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 my-3">
            <a href="addProduct.php" class="btn btn-outline-primary">Ajouter un produit</a>
            
            <div class="d-flex gap-2 align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un produit..." onkeyup="filterProducts()">

                <form method="GET" action="products.php" class="d-flex align-items-center gap-2 mb-0">
                    <select name="category" id="category" class="form-select" style="width: auto;" onchange="filterProducts()">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= htmlspecialchars($category['id']) ?>"<?= ($selectedCategory == $category['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>

        <!-- Tableau des produits -->
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle text-center w-100 border">
                <thead>
                    <tr>
                        <th scope="col"># Id</th>
                        <th scope="col">Image</th>
                        <th scope="col" class="d-none d-md-table-cell">Nom</th>
                        <th scope="col" class="d-none d-md-table-cell">Catégorie</th>
                        <th scope="col" class="d-none d-md-table-cell">Prix</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <!-- ID unique ici pour l'injection AJAX -->
                <tbody id="productsTableBody">
                    <?php foreach ($products as $product) : ?>
                        <tr class="clickable-row" style="cursor: pointer;" data-href="product.php?id=<?= urlencode($product['id']) ?>">
                            <th scope="row"><?= $product['id'] ?></th>

                            <?php

                                $coverPath = "../images/" . ($product['cover'] ?? '');
                                
                                $hasImage = !empty($product['cover']);

                            ?>
                            <td>
                                <?php if ($hasImage) : ?>

                                    <img src="<?= htmlspecialchars($coverPath) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                
                                    <?php else : ?>
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                                        <i class="bi bi-image text-white"></i>
                                    </div>

                                <?php endif; ?>
                            </td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars($product['name']) ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars($product['category_name']) ?></td>
                            <td class="d-none d-md-table-cell"><?= number_format($product['price'], 2, ',', ' ') ?>€</td>


                            <td data-no-click>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="updateProduct.php?id=<?= urlencode($product['id']) ?>" class="btn btn-warning btn-sm">Modifier</a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $product['id'] ?>">Supprimer</button>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal<?= $product['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?= $product['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel<?= $product['id'] ?>">Confirmation</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Voulez-vous vraiment<br>supprimer <strong><?= htmlspecialchars($product['name']) ?></strong> ?
                                            </div>
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Non</button>

                                                <a href="products.php?delete=<?= urlencode($product['id']) ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

<script src="../assets/js/script.js"></script>

</body>
</html>

