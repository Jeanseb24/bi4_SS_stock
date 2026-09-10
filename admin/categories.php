<?php
    // sécurité admin
    require "../config/session.php";
    
    if(!isset($_SESSION['email']) || !isset($_SESSION['id'])){
        header("Location: ../403.php");
        exit();
    }

    // base de données
    require_once "../config/connexion.php";
    require "functions.php";

    // fonctionnalité de suppression 
     if(isset($_GET['delete']) && filter_var($_GET['delete'],FILTER_VALIDATE_INT)){
        // vérifier que ce que je souhaite supp existe
       $delCategory = fetchOne($bdd,"SELECT * FROM categories WHERE id=?",[$_GET['delete']]);
       if(!$delCategory){
        header("Location: ../404.php");
        exit();
       }
      
       // retrouver les produits associés à ma catégorie
       $prods = fetchAll($bdd,"SELECT * FROM products WHERE id_category=?",[$_GET['delete']]);
       foreach($prods as $prod){
            if(file_exists("../images/".$prod['cover'])){
                unlink("../images/".$prod['cover']);
            }
            if(file_exists("../images/mini_".$prod['cover'])){
                unlink("../images/mini_".$prod['cover']);
            }

            $deleteGal = fetchAll($bdd,"SELECT * FROM images WHERE id_product=?",[$prod['id']]);
            foreach($deleteGal as $img){
                if(file_exists("../images/".$img['file'])){
                    unlink("../images/".$img['file']);
                }
            }
            $galResult = execute($bdd,"DELETE FROM images WHERE id_product=?",[$prod['id']]);
       }

       // supprimer les données des produits assocé à la catégorie ciblée
       $resultProd = execute($bdd,"DELETE FROM products WHERE id_category=?",[$_GET['delete']]);
       
        // supprimer les données de la catégorie ciblée
       $resultCat = execute($bdd,"DELETE FROM categories WHERE id=?",[$_GET['delete']]);
       //var_dump($resultProd);
       //var_dump($resultCat);
       header("Location: categories.php?successdelete=".$_GET['delete']);
       exit();
    }
    /*****************************/

?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<?php include("partials/head.php"); ?>
<body>
    <?php include("partials/nav.php"); ?>
    <div class="container-fluid px-3 px-md-5 py-5 mx-auto" style="max-width: 1400px;">
        <h2>Gestion des categories</h2>
        <?php
            // récup toutes les données de la table catégorie en une fois => fetchAll()
            $categories = fetchAll($bdd, "SELECT * FROM categories ORDER BY id ASC");
        ?>
        <a href="addCategory.php" class="btn btn-outline-primary btn-sm my-3">Ajouter une catégorie</a>
        <table class="table-responsive">
            <table class="table table-hover table-striped align-middle text-center w-100 border">
                <thead>
                    <tr>
                        <th scope="col" class="d-none d-md-table-cell"># Id</th>
                        <th scope="col">Nom</th>
                        <th scope="col" class="d-none d-md-table-cell">Description</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categories as $category) : ?>
                        <tr>
                            <th scope="row" class="d-none d-md-table-cell"><?= $category['id'] ?></th>
                            <td><?= htmlspecialchars($category['name']) ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars($category['description']) ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="updateCategory.php?id=<?= $category['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $category['id'] ?>">
                                    Supprimer
                                    </button>
                                </div>
                            </td>
    
                            <!-- Button trigger modal -->
    
    
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal<?= $category['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?= $category['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel<?= $category['id'] ?>"><i class="bi bi-exclamation-octagon text-danger"></i> Confirmation de suppression</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-2">Voulez-vous supprimer la catégorie :</p>
                                <p class="fw-bold fs-5 mb-3 text-break"><?= $category['name'] ?></p>
                                <p class="text-warning small mb-0">
                                    <i class="bi bi-exclamation-triangle"></i> Tous les produits et images associé(e)s seront supprimés.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                                <a href="categories.php?delete=<?= $category['id'] ?>" class="btn btn-danger">Supprimer</a>
                            </div>
                            </div>
                        </div>
                        </div>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </table>
    </div>
</body>
</html>