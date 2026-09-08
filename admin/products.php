<?php 
    require "../config/session.php";
    
    if(!isset($_SESSION['email']) || !isset($_SESSION['id'])){
        header("Location: ../403.php");
        exit();
    }

    require_once "../config/connexion.php";
    require "functions.php";

     if(isset($_GET['delete']) && filter_var($_GET['delete'],FILTER_VALIDATE_INT)){
       $delete = fetchOne($bdd,"SELECT * FROM products WHERE id=?",[$_GET['delete']]);
       if(!$delete){
        header("Location: ../404.php");
        exit();
       }else{
        if(file_exists("../images/".$delete['cover'])){
            unlink("../images/".$delete['cover']);
        }
      
       }

       $result = execute($bdd,"DELETE FROM products WHERE id=?",[$_GET['delete']]);
       //var_dump($result);
    }

?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<?php include("partials/head.php"); ?>
<body>
    <?php include("partials/nav.php"); ?>
    <div class="container-fluid py-5 mx-auto" style="width: 80vw;">
        <h2>Gestion des produits</h2>
        <?php
            $selectedCategory = $_GET['category'] ?? '';

            if (!empty($selectedCategory) && filter_var($selectedCategory, FILTER_VALIDATE_INT)) {
                $products = fetchAll($bdd, "
                    SELECT products.*, categories.name AS category_name 
                    FROM products 
                    JOIN categories ON products.id_category = categories.id 
                    WHERE products.id_category = ?
                    ORDER BY products.id ASC
                ", [$selectedCategory]);
            } else {
                $products = fetchAll($bdd, "
                    SELECT products.*, categories.name AS category_name 
                    FROM products 
                    JOIN categories ON products.id_category = categories.id 
                    ORDER BY products.id ASC
                ");
            }
            $categories = fetchAll($bdd, "SELECT * FROM categories ORDER BY name ASC");
        ?>
        <div class="d-flex justify-content-between align-items-center">
            <a href="addProduct.php" class="btn btn-outline-primary my-3">Ajouter un produit</a>

            <form method="GET" action="products.php" class="d-flex align-items-center gap-2">
                <label for="category" class="mb-0">Catégorie :</label>
                <select name="category" id="category" class="form-select" style="width: auto;" onchange="this.form.submit()">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= htmlspecialchars($category['id']) ?>"
                            <?= ($selectedCategory == $category['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle text-center w-100 border">
                <thead>
                    <tr>
                        <th scope="col"># Id</th>
                        <th scope="col">Image</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Catégorie</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <th scope="row"><?= $product['id'] ?></th>
                            <?php
                                $coverPath = "../images/" . ($product['cover'] ?? '');
                                $hasImage = !empty($product['cover']) && file_exists($coverPath);
                            ?>
                            <td>
                                <?php if ($hasImage) : ?>
                                    <img src="<?= htmlspecialchars($coverPath) ?>" 
                                        alt="<?= htmlspecialchars($product['name']) ?>" 
                                        class="rounded" 
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                <?php else : ?>
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center mx-auto" 
                                        style="width: 60px; height: 60px;">
                                        <i class="bi bi-image text-white"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category_name']) ?></td>
                            <td><?= $product['price'] ?>€</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="updateProduct.php?id=<?= urlencode($product['id']) ?>" class="btn btn-warning btn-sm">
                                        Modifier
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $product['id'] ?>">
                                        Supprimer
                                    </button>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal<?= $product['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?= $product['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel<?= $product['id'] ?>">Confirmation de suppression</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Voulez-vous vraimet<br>supprimer le produit <strong><?= htmlspecialchars($product['name']) ?></strong> ?
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
</body>
</html>

