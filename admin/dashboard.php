<?php 
    require "../config/session.php";
    
    if(!isset($_SESSION['login']) || !isset($_SESSION['id'])){
        header("Location: ../403.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<?php include("partials/head.php"); ?>
<body>
    <h1>Tableau de bord</h1>
</body>
</html>