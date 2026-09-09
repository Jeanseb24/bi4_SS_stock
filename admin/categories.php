<?php 
    require "../config/session.php";
    
    if(!isset($_SESSION['email']) || !isset($_SESSION['id'])){
        header("Location: ../403.php");
        exit();
    }

    require_once "../config/connexion.php";
    require "functions.php";

     if(isset($_GET['delete']) && filter_var($_GET['delete'],FILTER_VALIDATE_INT)){
       $delCategory = fetchOne($bdd,"SELECT * FROM categories WHERE id=?",[$_GET['delete']]);
       if(!$delCategory){
        header("Location: ../404.php");
        exit();
       }

       // retrouver les produits associés à ma catégorie
       $prods = fetchAll($bdd, "SELECT * FROM products WHERE id_category=?",[$_GET['delete']]);
       foreach($prods as $prod){
            if(file_exists("../images/".$prod['cover'])){
                unlink("../images/".$prod['cover']);
            }
       }
       // supprimer les données des produits associés à la catégorie cible
       $resultProd = execute($bdd,"DELETE FROM products WHERE id_category=?",[$_GET['delete']]);
       
       // supprimer les données de la catégorie ciblée
       $resultCat = execute($bdd,"DELETE FROM categories WHERE id=?",[$_GET['delete']]);
    //    var_dump($resultProd);
    //    var_dump($resultCat);
    }

?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<?php include("partials/head.php"); ?>
<body>
    <?php include("partials/nav.php"); ?>
    <div class="container-fluid py-5 mx-auto" style="width: 80vw;">
        <h2>Gestion des catégories</h2>
        <?php
            // récupère toutes les données de la table catégorie en une fois => fetchAll()
            $categories = fetchAll($bdd, "SELECT * FROM categories ORDER BY id ASC");
        ?>
        <a href="addCategory.php" class="btn btn-outline-primary my-3">Ajouter une catégorie</a>

        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle text-center w-100 border">
                <thead>
                    <tr>
                        <th scope="col"># Id</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Description</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category) : ?>
                        <tr>
                            <th scope="row"><?= $category['id'] ?></th>
                            <td><?= htmlspecialchars($category['name']) ?></td>
                            <td><?= htmlspecialchars($category['description']) ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="updateCategory.php?id=<?= urlencode($category['id']) ?>" class="btn btn-warning btn-sm">
                                        Modifier
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $category['id'] ?>">
                                        Supprimer
                                    </button>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal<?= $category['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?= $category['id'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel<?= $category['id'] ?>">Confirmation de suppression</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Voulez-vous vraimet<br>supprimer la catégorie <strong><?= htmlspecialchars($category['name']) ?></strong> ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Non</button>
                                                <a href="categories.php?delete=<?= urlencode($category['id']) ?>" class="btn btn-danger btn-sm">Supprimer</a>
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

