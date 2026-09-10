<?php 
    require "../config/session.php";
    
    if(!isset($_SESSION['email']) || !isset($_SESSION['id'])){
        header("Location: ../403.php");
        exit();
    }

?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<?php include("partials/head.php"); ?>
<body>
    <?php include("partials/nav.php"); ?>
    <div class="container-fluid px-3 px-md-5 py-5 mx-auto" style="max-width: 1400px;">
        <h2>Ajouter un produit</h2>
        <form action="treatmentAddProduct.php" method="POST" enctype="multipart/form-data">
            <?php 
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            ?>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="form-group my-2">
                <label for="categorie">Catégorie: </label>
                <select name="categorie" id="categorie" class="form-control my-2">
                    <?php
                        require "../config/connexion.php";
                        require "functions.php";
                        $categories = fetchAll($bdd,"SELECT * FROM categories ORDER BY id");
                    ?>
                    <?php foreach($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group my-2">
               <label for="nom">Nom: </label>
               <input type="text" name="name" id="nom" class="form-control my-2">
            </div>
            <div class="form-group my-2">
                <label for="description">Déscription: </label>
                <textarea name="description" id="description" class="form-control my-2"></textarea>
            </div>
            <div class="form-group my-2">
                <label for="prix">Prix: </label>
                <input type="number" name="prix" id="prix" step="0.01" class="form-control my-2">
            </div>
            <div class="form-group my-2">
                <label for="cover">Image de couverture: </label>
                <input type="file" name="cover" id="cover" class="form-control my-2">
            </div>
            <div class="form-group my-2">
                <input type="submit" value="Ajouter" class="btn btn-sm btn-outline-primary my-2">
            </div>
        </form>
    </div>
</body>
</html>