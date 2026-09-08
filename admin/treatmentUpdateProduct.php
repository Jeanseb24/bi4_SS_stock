<?php
    require "../config/session.php";

    if($_SERVER['REQUEST_METHOD'] !== "POST"){
        http_response_code(405); // 405 Méthode non autorisée
        header("Allow: POST"); // indiquer la méthode autorisée
        exit("Méthode non autorisée, Utilisez POST");
    }

    if(!isset($_SESSION['csrf_token'], $_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'],$_POST['csrf_token'])){
        http_response_code(403);
        exit("Jeton de sécurité invalide");
    }

    if(!isset($_GET['id']) || !filter_var($_GET['id'],FILTER_VALIDATE_INT)){
        header("Location: ../404.php");
        exit();
    }

    require "../config/connexion.php";
    require "functions.php";

    $product = fetchOne($bdd,"SELECT * FROM products WHERE id=?",[$_GET['id']]);
    if(!$product){
        header("Location: ../404.php");
        exit();
    }

    $err = 0;

    // opérateur de coalescence nulle (Null Coalescing Operator)
    // c'est un raccouci pour dire : " si la valeur à gauche existe et n'est pas nul, utilise là, sinon, utilise la valeur par défaut à droite

    $name = trim($_POST['name'] ?? "");
    $description = trim($_POST['description'] ?? "");
    $price = trim($_POST['price'] ?? "");
    $categorie = trim($_POST['categorie'] ?? "");

    if (empty($name)){
        $err = 1;
    }elseif (empty($description)){
        $err = 2;
    }elseif (empty($price)){
        $err = 3;
    }elseif(!filter_var($price, FILTER_VALIDATE_FLOAT)){
        $err = 4;
    }elseif(!filter_var($categorie, FILTER_VALIDATE_INT)){
        $err = 5;
    }

if($err===0){

    $coverToSave = $product['cover']; // garde l'image existante par défaut

    // Traiter l'upload SEULEMENT si un nouveau fichier a été envoyé
    if(isset($_FILES['cover']) && $_FILES['cover']['error'] !== UPLOAD_ERR_NO_FILE){

        if($_FILES['cover']['error'] !== UPLOAD_ERR_OK){
            header("Location: updateProduct.php?id=".$product['id']."&error=6");
            exit();
        }

        $tmpPath = $_FILES['cover']['tmp_name'];
        $tailleMax = 2 * 1024 * 1024;

        if($_FILES['cover']['size'] > $tailleMax){
            header("Location: updateProduct.php?id=".$product['id']."&error=7");
            exit();
        }

        $extension = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
        $extensionAutorisees = ['jpg','jpeg','png','svg',"webp"];

        if(!in_array($extension,$extensionAutorisees, true)){
            header("Location: updateProduct.php?id=".$product['id']."&error=8");
            exit();
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeReel = $finfo->file($tmpPath);

        $mimesAutorises = [
            "jpg" => "image/jpeg",
            "jpeg" => "image/jpeg",
            "png" => "image/png",
            "svg" => "image/svg+xml",
            "webp" => "image/webp"
        ];

        if(!in_array($mimeReel, $mimesAutorises, true) || $mimesAutorises[$extension] !== $mimeReel){
            header("Location: updateProduct.php?id=".$product['id']."&error=9");
            exit();
        }

        $nomImage = basename($_FILES['cover']['name']);
        $nomImageLisible = strtr($nomImage, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
        $nomImageSafe = preg_replace('/([^.a-z0-9]+)/i', '-', $nomImageLisible);
        $uniqnomSafe = uniqid().'-'.$nomImageSafe;

        $dossierDestination = "../images/";

        if(!move_uploaded_file($tmpPath, $dossierDestination.$uniqnomSafe)){
            header("Location: updateProduct.php?id=".$product['id']."&error=10");
            exit();
        }

        $coverToSave = $uniqnomSafe;
    }

    try {
        $sql = "UPDATE products SET name = :name, description = :description, price = :price, id_category = :categorie, cover = :cover WHERE id = :id";

        execute($bdd, $sql, [
            "name"        => $name,
            "description" => $description,
            "price"       => $price,
            "categorie"   => $categorie,
            "cover"       => $coverToSave,
            "id"          => $product['id']
        ]);

        // Supprimer l'ancienne image seulement si elle a été remplacée
        if ($coverToSave !== $product['cover'] && !empty($product['cover']) && file_exists("../images/" . $product['cover'])) {
            unlink("../images/" . $product['cover']);
        }

        unset($_SESSION['csrf_token']);
        header("Location: products.php?update=success");
        exit();

    } catch(PDOException $e) {
        if ($coverToSave !== $product['cover'] && file_exists("../images/" . $coverToSave)) {
            unlink("../images/" . $coverToSave);
        }
        header("Location: products.php?error=500");
        exit();
    }

}else{
    header("Location: updateProduct.php?id=".$product['id']."&error=".$err);
    exit();
}