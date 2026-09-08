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
        
        if(!isset($_FILES['cover']) || $_FILES['cover']['error'] !== UPLOAD_ERR_OK){
            header("Location: addProduct.php?error=6");
            exit();
        }

        // récup le fichier
        $tmpPath = $_FILES['cover']['tmp_name'];
        $tailleMax = 2 * 1024 * 1024; 
     
        // vérification de taille
        if($_FILES['cover']['size'] > $tailleMax)
        {
            header("Location: addProduct.php?error=7");
            exit();
        }

        // extension
        // image.php.jpg
        // image.JPG
        $extension = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
        $extensionAutorisees = ['jpg','jpeg','png','svg',"webp"];

        if(!in_array($extension,$extensionAutorisees, true)){
            header("Location: addProduct.php?error=8");
            exit();
        }

        // vérification du Mime Type => utilisation contenu binaire(fileinfo)
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
            header("Location: addProduct.php?error=9");
            exit();
        }

        // gestion du nom du fichier
        // dossier/ico/fichier.jpg
        // fichier.jpg
        $nomImage =  basename($_FILES['cover']['name']);
        $nomImageLisible = strtr($nomImage, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
        $nomImageSafe = preg_replace('/([^.a-z0-9]+)/i', '-', $nomImageLisible);
        $uniqnomSafe = uniqid().'-'.$nomImageSafe;


        // $uniqnomSafe = bin2hex(random_bytes(16)).'.'.$extension;

        // dfkqsjfkldfj-fichier.jpg
        // imagesdfkqsjfkldfj-fichier.jpg
        $dossierDestination = "../images/";
        
        if(move_uploaded_file($tmpPath, $dossierDestination.$uniqnomSafe)){
            require "../config/connexion.php";
            require "functions.php";
            try{
                insert($bdd, "INSERT INTO products(name,description,price,id_category,cover) VALUES(:name,:description,:price,:categorie,:cover)",[
                    "name" => $name,
                    "description" => $description,
                    "price" => $price,
                    "categorie" => $categorie,
                    "cover" => $uniqnomSafe
                ]);
                unset($_SESSION['csrf_token']);
                header("Location: products.php?add=success");
                exit();
            }catch(PDOException $e){
                if(file_exists($dossierDestination.$uniqnomSafe)){
                    unlink($dossierDestination.$uniqnomSafe);
                }
                header("Location: products.php?error=500");
                exit();
            }

        }else{
            header("Location: addProduct.php?error=10");
            exit();
        }

     

    }else{
        header("Location: addProduct.php?error=".$err);
        exit();
    }

    