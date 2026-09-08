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

    $name = trim($_POST['name'] ?? "");
    $description = trim($_POST['description'] ?? "");

    if (empty($name)){
        $err = 1;
    }elseif (empty($description)){
        $err = 2;
    }

        if($err===0){
            require "../config/connexion.php";
            require "functions.php";
            try{
                insert($bdd, "INSERT INTO categories(name,description) VALUES(:name,:description)",[
                    "name" => $name,
                    "description" => $description
                ]);
                unset($_SESSION['csrf_token']);
                header("Location: categories.php?add=success");
                exit();
            }catch (PDOException $e) {
                $_SESSION['error'] = "Cette catégorie existe déjà.";

                header('Location: addCategory.php');
                exit;
            }
}
