<!-- VERSION_2_EMAIL+PASSWORD -->

<?php 
    // Charge le fichier de gestion de session (session.php).
    require "../config/session.php";

    //SI un utilisateur a déjà une session ouverte ($_SESSION['login'] et $_SESSION['id'] existent) :
    if(isset($_SESSION['login']) && isset($_SESSION['id'])){
        // Redirige vers dashboard.php et stoppe le script.
        header("Location: dashboard.php");
        exit();
    }

    // Initialise les variables d'erreur ($erreurEmail, $erreurPassword, $erreurForm) et vide la mémoire temporaire du champ email ($_SESSION['form-email']).
    $erreurEmail = "";
    $erreurPassword = "";
    $erreurForm = "";
    $_SESSION['form-email'] = "";
    
    // SI le formulaire est soumis avec la méthode POST :
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        // SI le token CSRF est présent dans la session et dans le POST, ET que leurs valeurs sont identiques (hash_equals) :
        if(isset($_SESSION['csrf_token'], $_POST['csrf_token']) AND hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
            // 1. Récupère et nettoie les données (trim pour retirer les espaces inutiles).
            $email = trim($_POST['email'] ?? "");
            $password = trim($_POST['password'] ?? "");

            // 2. SI le champ email est vide :
            if(empty($email)){
                // Génère un message d'erreur dans $erreurEmail.
                $erreurEmail = "<div class='alert alert-danger'>Veuillez remplir l'adresse email</div>";
            // SINON SI l'email a un format invalide (!filter_var) :    
            } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                // Génère un message d'erreur dans $erreurEmail.
                $erreurEmail = "<div class='alert alert-danger'>Adresse email invalide</div>";
            // SINON (l'email est valide) :
            } else {
                // Sauvegarde l'email dans $_SESSION['form-email'] pour le réafficher dans le formulaire.
                $_SESSION['form-email'] = $email;
            }
            // 3. SI le champ mot de passe est vide :
            if(empty($password)){
                // Génère un message d'erreur dans $erreurPassword.
                $erreurPassword = "<div class='alert alert-danger'>Veuillez remplir le mot de passe</div>";
            }
            // 4. SI il n'y a aucune erreur sur l'email et le mot de passe ($erreurEmail et $erreurPassword sont vides) :
            if(empty($erreurEmail) && empty($erreurPassword))
            {
                // Inclut la connexion à la base de données (connexion.php).
                require "../config/connexion.php";
                
                // Prépare et exécute la requête SQL pour chercher l'utilisateur via son adresse email (WHERE email = ?).
                $req = $bdd->prepare("SELECT id, login, email, password FROM users WHERE email = ?");
                // Récupère les données de l'utilisateur sous forme de tableau associatif.
                $req->execute([$email]);
                $data = $req->fetch(PDO::FETCH_ASSOC);
                
                // SI un utilisateur a été trouvé ET que le mot de passe est valide (password_verify) :
                if($data && password_verify($password, $data['password'])){
                    // Stocke les informations de l'utilisateur dans la session (login, email, id).
                    $_SESSION['login'] = $data['login']; 
                    $_SESSION['email'] = $data['email'];
                    $_SESSION['id'] = $data['id'];
                    // Supprime les variables temporaires de la session (csrf_token, form-email).
                    unset($_SESSION['csrf_token']);
                    unset($_SESSION['form-email']);
                    // Redirige vers dashboard.php et stoppe le script (exit()).
                    header("Location: dashboard.php");
                    exit();

                // SINON (utilisateur introuvable ou mauvais mot de passe) :
                } else {
                    // Génère un message d'erreur d'identifiants incorrects dans $erreurForm.
                    $erreurForm = "<div class='alert alert-danger'>Votre email ou votre mot de passe est incorrect</div>";
                }
            }
        }
    }
?>

<!-- Affiche la page HTML :
├── Inclut les balises d'en-tête HTML (partials/head.php).
├── Affiche l'éventuelle erreur globale ($erreurForm).
├── Génère un nouveau token CSRF à chaque affichage et l'injecte dans un champ caché <input type="hidden">.
├── Affiche le champ email avec la valeur précédemment saisie (sécurisée par htmlspecialchars) et son éventuelle erreur ($erreurEmail).
├── Affiche le champ password et son éventuelle erreur ($erreurPassword).
└── Affiche le bouton de soumission "Connexion". -->

<!DOCTYPE html>
<html lang="fr">
<?php include("partials/head.php"); ?>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <h1>Connexion - Administration</h1>
                <form action="index.php" method="POST">
                    <?= $erreurForm ?>
                    
                    <?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <div class="form-group my-3">
                        <label for="email">Adresse Email :</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($_SESSION['form-email'] ?? '') ?>">
                        <?= $erreurEmail ?>
                    </div>

                    <div class="form-group my-3">
                        <label for="password">Mot de passe :</label>
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