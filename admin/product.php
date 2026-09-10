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
    <div class="container-fluid py-5 mx-auto" style="max-width: 80vw;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Détail du produit</h2>
                <?php
                    $product = fetchOne(
                        $bdd,
                        "SELECT products.id as pid, products.name as pname, categories.name as cname, products.prix as pprix, products.cover as pcover, products.description as pdescription FROM products INNER JOIN categories ON products.id_category = categories.id WHERE products.id = ?",
                        [$_GET['id']]
                    );
                    $galleryImages = fetchAll(
                        $bdd,
                        "SELECT * FROM images WHERE id_product = ?",
                        [$_GET['id']]
                    );
                    
                ?>
            <a href="products.php" class="btn btn-outline-primary btn-sm my-3">
                &larr; Retour à la liste
            </a>
        </div>
        
        
            <div class="row justify-content-center py-5">
                <div class="">
                    <div class="card shadow-sm overflow-hidden p-5">
                        <div class="row g-0 align-items-stretch">
                            <!-- Colonne gauche : image -->
                            <div class="col-md-5 d-flex flex-column">
                                <div class="flex-grow-1">
                                    <?php 
                                    $serverImagePath = __DIR__ . '/../images/' . $product['pcover'];
                                    $hasValidImage = !empty($product['pcover']) && file_exists($serverImagePath);
                                    ?>

                                    <?php if ($hasValidImage) : ?>
                                        <img src="../images/<?= htmlspecialchars($product['pcover']) ?>" 
                                            alt="image de <?= htmlspecialchars($product['pname']) ?>" 
                                            class="img-fluid w-100 h-100 object-fit-cover rounded" 
                                            style="min-height: 250px;">
                                    <?php else : ?>
                                        <div class="bg-secondary w-100 h-100 d-flex align-items-center justify-content-center rounded" style="min-height: 250px;">
                                            <i class="bi bi-image text-white fs-1"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Affichage de la galerie d'images secondaires -->
                                <?php if (!empty($galleryImages)) : ?>
                                    <div class="d-flex gap-2 mt-3 flex-wrap">
                                        <?php foreach ($galleryImages as $img) : ?>
                                            <?php 
                                            // Remplacez 'name' par le nom exact de votre colonne dans la table 'images' (ex: 'path' ou 'file')
                                            $imgPath = '../images/' . $img['file']; 
                                            ?>
                                            <img src="<?= htmlspecialchars($imgPath) ?>" 
                                                alt="Galerie <?= htmlspecialchars($product['pname']) ?>" 
                                                class="img-thumbnail" 
                                                style="width: 70px; height: 70px; object-fit: cover;">
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Colonne droite : infos -->
                            <div class="col-md-7 d-flex align-items-center px-5">
                                <div class="card-body p-5">
                                    <h3 class="card-title fw-bold mb-3"><?= htmlspecialchars($product['pname']) ?></h3>

                                    <h5 class="text-uppercase text-muted fw-semibold mb-1">Catégorie :</h5>
                                    <p class="mb-3"><?= htmlspecialchars($product['cname']) ?></p>

                                    <h5 class="text-uppercase text-muted fw-semibold mb-1">Description :</h5>
                                    <p class="text-muted mb-3"><?= htmlspecialchars($product['pdescription']) ?></p>

                                    <p class="card-text fs-3 fw-bold text-primary mb-4">
                                        <?= number_format($product['pprix'], 2, ',', ' ') ?>€
                                    </p>

                                    <div class="d-flex gap-2">
                                        <a href="updateProduct.php?id=<?= urlencode($product['pid']) ?>" class="btn btn-warning btn-sm">
                                            Modifier
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $product['pid'] ?>">
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
            <div class="modal fade" id="exampleModal<?= $product['pid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?= $product['pid'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel<?= $product['pid'] ?>">
                                <i class="bi bi-exclamation-octagon text-danger"></i> Confirmation de suppression
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-2">Voulez-vous supprimer le produit :</p>
                            <p class="fw-bold fs-5 mb-3 text-break"><?= htmlspecialchars($product['pname']) ?></p>
                            <p class="text-warning small mb-0">
                                <i class="bi bi-exclamation-triangle"></i> Toutes les images associées seront supprimées.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                            <a href="products.php?delete=<?= $product['pid'] ?>" class="btn btn-danger">Supprimer</a>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</body>
</html>