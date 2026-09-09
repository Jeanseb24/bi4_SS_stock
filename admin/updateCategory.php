<?php 
    require "../config/session.php";
    
    if(!isset($_SESSION['email']) || !isset($_SESSION['id'])){
        header("Location: ../403.php");
        exit();
    }

    // vérification de l'id de la catégorie à modifier
    if(!isset($_GET['id']) || !filter_var($_GET['id'],FILTER_VALIDATE_INT)){
        header("Location: ../404.php");
        exit();
    }

    // vérification si la catégorie existe bien
    require "../config/connexion.php";
    require "functions.php";
    
    $category = fetchOne($bdd,"SELECT * FROM categories WHERE id=?",[$_GET['id']]);
    if(!$category){
        header("Location: ../404.php");
        exit();
    }

?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<?php include("partials/head.php"); ?>
<body>
    <?php include("partials/nav.php"); ?>
    <div class="container-fluid py-5 mx-auto" style="width: 80vw;">
        <h2>Modifier catégorie: <?= htmlspecialchars($category['name']) ?></h2>
        <form action="treatmentUpdateCategory.php?id=<?= $category['id'] ?>" method="POST">
            <?php 
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            ?>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-group">
               <label for="nom">Nom: </label>
               <input type="text" name="name" id="nom" class="form-control" value="<?= ($category['name']) ?>">
            </div>
            <div class="form-group my-2">
                <label for="description">Déscription: </label>
                <textarea name="description" id="description" class="form-control"><?= ($category['description']) ?></textarea>
            </div>
            <div class="form-group my-2">
                <input type="submit" value="Modifier" class="btn btn-warning">
            </div>
        </form>
    </div>
</body>
</html>

