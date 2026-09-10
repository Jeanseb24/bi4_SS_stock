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
                $erreurEmail = "<div class='text-danger small my-1'>Veuillez remplir correctement l'adresse e-mail</div>";
            }else{
                $_SESSION['form-email'] = $email;
            }

            if(empty($password)){
                $erreurPassword = "<div class='text-danger small my-1'>Veuillez remplir le password</div>";
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
                        $erreurForm="<div class='text-danger small my-1'>Votre login ou votre mot de passe est incorrect</div>";
                    }
                }else{
                    $erreurForm="<div class='text-danger small my-1'>Votre login ou votre mot de passe est incorrect</div>";
                }
            }
         }
    }
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<?php include("partials/head.php"); ?>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row w-100">
        <div class="col-md-6 col-lg-4 mx-auto">
            
            <!-- Début de la Carte -->
            <div class="card shadow border-0 rounded-3">
                <div class="card-body p-4">
                    
                    <div class="text-center mb-4">
                        <h2 class="fw-bold h4 text-dark mb-1">Connexion</h2>
                        <p class="text-muted small">Espace Administration</p>
                    </div>

                    <form action="index.php" method="POST">
                        <?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse Email :</label>
                            <div class="input-group">
                                <span class="input-group-text text-muted">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="nom@exemple.com" value="<?= htmlspecialchars($_SESSION['form-email'] ?? '') ?>">
                            </div>
                            <?= $erreurEmail ?>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe :</label>
                            <div class="input-group">
                                <span class="input-group-text text-muted">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe">
                            </div>
                            <?= $erreurPassword ?>
                        </div>

                        <?= $erreurForm ?>

                        <div class="d-grid gap-2 mt-4">
                            <input type="submit" value="Connexion" class="btn btn-success">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    </div>
</body>
</html>