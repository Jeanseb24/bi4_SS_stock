<?php 
    require "../config/session.php";

    if(isset($_SESSION['email']) && isset($_SESSION['id'])){
        header("Location: dashboard.php");
        exit();
    }

    $erreurEmail = "";
    $erreurPassword = "";
    $erreurForm = "";
    $_SESSION['form-email']="";
    // vérification de la méthode donc si formulaire envoyé
     if($_SERVER['REQUEST_METHOD'] == "POST"){
        // vérification que le formulaire à envoyé la donnée CSRF_TOKEN + presésence de la session et comparaison
         if(isset($_SESSION['csrf_token'], $_POST['csrf_token']) AND hash_equals($_SESSION['csrf_token'],$_POST['csrf_token'])){
            // nettoyage des données
            $email = trim($_POST['email'] ?? "");
            $password = trim($_POST['password'] ?? "");

            // vérification des données
            if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
                $erreurEmail = "<div class='alert alert-danger'>Veuillez remplir correctement l'adresse e-mail</div>";
            }else{
                $_SESSION['form-email'] = $email;
            }

            if(empty($password)){
                $erreurPassword = "<div class='alert alert-danger'>Veuillez remplir le password</div>";
            }

            if(empty($erreurEmail) && empty($erreurPassword))
            {
                // vérification de la présence dans la bdd du login
                require "../config/connexion.php";
                $req = $bdd->prepare("SELECT email,password,id FROM users WHERE email=?");
                $req->execute([$email]);
                $data = $req->fetch(PDO::FETCH_ASSOC);

                if($data){
                    // vérification mon mot de passe
                    // comparaison pour le mot de passe
                    if(password_verify($password,$data['password'])){
                        $_SESSION['email'] = $email;
                        $_SESSION['id'] = $data['id'];
                        header("Location: dashboard.php");
                        unset($_SESSION['csrf_token']);
                        unset($_SESSION['form-email']);
                        exit();
                    }else{
                        $erreurForm="<div class='alert alert-danger'>Votre login ou votre mot de passe est incorrect</div>";
                    }
                }else{
                    $erreurForm="<div class='alert alert-danger'>Votre login ou votre mot de passe est incorrect</div>";
                }
            }
         }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<?php include("partials/head.php"); ?>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <h1>Connexion - Administration</h1>
                <form action="index.php" method="POST">
                    <?=  $erreurForm ?>
                    <?php 
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    ?>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="form-group my-3">
                        <label for="email">Login (adresse E-mail): </label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= $_SESSION['form-email'] ?>">
                        <?= $erreurEmail ?>
                    </div>
                    <div class="form-group my-3">
                        <label for="password">Mot de passe: </label>
                        <input type="password" name="password" id="password" class="form-control">
                        <?= $erreurPassword ?>
                    </div>
                    <div class="form-group my-3">
                        <input type="submit" value="Connexion" class="btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>