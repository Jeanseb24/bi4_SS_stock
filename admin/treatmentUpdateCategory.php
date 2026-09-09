<?php
    require "../config/session.php";

    if($_SERVER['REQUEST_METHOD'] !== "POST"){
        http_response_code(405);
        header("Allow: POST");
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

    $category = fetchOne($bdd,"SELECT * FROM categories WHERE id=?",[$_GET['id']]);
    if(!$category){
        header("Location: ../404.php");
        exit();
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
        
            execute($bdd, "UPDATE categories SET name = :name, description = :description WHERE id = :id", [
                "name" => $name,
                "description" => $description,
                "id" => $_GET['id']
            ]);
            unset($_SESSION['csrf_token']);
            header("Location: categories.php?update=success&upid=".$category['id']);
            exit();

        } else {
        
        header('Location: updateCategory.php?id='.$category['id']."&error=".$err);
        exit();
    }