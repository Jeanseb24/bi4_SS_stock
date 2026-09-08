<?php 
    require "../config/session.php";
    
    if(!isset($_SESSION['email']) || !isset($_SESSION['id'])){
        header("Location: ../403.php");
        exit();
    }

    if(isset($_GET['deco'])){
        session_destroy();
        unset($_SESSION['email']);
        unset($_SESSION['id']);
        header("Location: index.php");
        exit();
    }

?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<?php include("partials/head.php"); ?>
<body>
    <?php include("partials/nav.php"); ?>
    <h1>Tableau de bord</h1>
</body>
</html>

