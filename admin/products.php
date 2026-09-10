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
        if(file_exists("../images/mini_".$delete['cover'])){
            unlink("../images/mini_".$delete['cover']);
        }
      
       }

       $deleteGal = fetchAll($bdd,"SELECT * FROM images WHERE id_product=?",[$_GET['delete']]);
        foreach($deleteGal as $img){
            if(file_exists("../images/".$img['file'])){
                unlink("../images/".$img['file']);
            }
        }
        
       $galResult = execute($bdd,"DELETE FROM images WHERE id_product=?",[$_GET['delete']]);

       $result = execute($bdd,"DELETE FROM products WHERE id=?",[$_GET['delete']]);
       //var_dump($result);
       header("Location: products.php?successdelete=".$_GET['delete']);
       exit();
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
            $products = fetchAll($bdd, "SELECT products.id as pid, products.name as pname, products.prix as pprix, products.cover as pcover, categories.name as cname FROM products INNER JOIN categories ON products.id_category = categories.id ORDER BY products.id DESC");
            // var_dump($products)
        ?>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3">

            <a href="addProduct.php" class="btn btn-outline-primary btn-sm my-3">Ajouter un produit</a>

        </div>  
        
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
                <tbody>
                    <?php foreach($products as $product) : ?>
                        <tr class="clickable-row" style="cursor: pointer;" data-href="product.php?id=<?= urlencode($product['pid']) ?>">
                            <th  scope="row"><?= $product['pid'] ?></th>
                            <td>
                                <!-- récupération image sinon card grise + icon bootstrap -->
                                <?php 
                                // Chemin physique absolu sur le serveur pour la vérification avec file_exists
                                $imagePath = __DIR__ . '/../images/mini_' . $product['pcover'];

                                // Condition : pcover n'est pas vide ET le fichier existe réellement sur le serveur
                                $hasValidImage = !empty($product['pcover']) && file_exists($imagePath);
                                ?>

                                <?php if ($hasValidImage) : ?>
                                    <!-- CAS 1 : cover renseigné ET fichier image présent dans le dossier -->
                                    <img src="../images/mini_<?= htmlspecialchars($product['pcover']) ?>" 
                                        alt="<?= $product['pname'] ?>" 
                                        title="<?= $product['pname'] ?>" 
                                        class="rounded" 
                                        style="width: 60px; height: 60px; object-fit: cover;">

                                <?php else : ?>
                                    <!-- CAS 2 : cover est NULL, vide OU le texte en BDD ne correspond à aucune image du dossier -->
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center mx-auto" 
                                        style="width: 60px; height: 60px;" 
                                        title="<?= $product['pname'] ?>">
                                        <i class="bi bi-image text-white"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="d-none d-md-table-cell"><?= $product['pname'] ?></td>
                            <td class="d-none d-md-table-cell"><?= $product['cname'] ?></td>
                            <td class="d-none d-md-table-cell"><?= number_format($product['pprix'], 2, ',', ' ') ?>€</td>
                            <!-- data-no-click annule click javascript sur 'Actions' -->
                            <td data-no-click>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="updateProduct.php?id=<?= $product['pid'] ?>" class="btn btn-warning btn-sm">
                                        Modifier
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $product['pid'] ?>">
                                    Supprimer
                                    </button>
                                </div>
                            </td>
    
                            <!-- Button trigger modal -->
    
    
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal<?= $product['pid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?= $product['pid'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel<?= $product['pid'] ?>"><i class="bi bi-exclamation-octagon text-danger"></i> Confirmation de suppression</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-2">Voulez vous supprimer le produit :</p>
                                            <p class="fw-bold fs-5 mb-3 text-break"><?= $product['pname'] ?></p>
                                            <p class="text-warning small mb-0">
                                                <i class="bi bi-exclamation-triangle"></i> Toutes les images associées seront supprimés.
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                                            <a href="products.php?delete=<?= $product['pid'] ?>" class="btn btn-danger">Supprimer</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

<script src="../assets/js/script.js"></script>

</body>
</html>