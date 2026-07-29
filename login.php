<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'dati_generali.php';

// Se l'utente è già loggato (come admin o come utente), lo rimandiamo alla home
if (isset($_SESSION['loggato']) && $_SESSION['loggato'] === true) {
    header("Location: homepage.php");
    exit;
}

$errore = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === "admin" && $password === "adminpassword") {
        // LOGIN AMMINISTRATORE
        $_SESSION['loggato'] = true;
        $_SESSION['admin'] = true;
        $_SESSION['username'] = "Amministratore";
        header("Location: homepage.php");
        exit;
    } elseif ($username === "utente" && $password === "utentepassword") {
        // LOGIN UTENTE STANDARD
        $_SESSION['loggato'] = true;
        $_SESSION['admin'] = false; // Non è admin!
        $_SESSION['username'] = "Valerio"; // Il tuo nome utente
        header("Location: homepage.php");
        exit;
    } else {
        $errore = "Credenziali non valide! Usa admin/adminpassword oppure utente/utentepassword.";
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Spotify - Accedi</title>
    <link rel="stylesheet" type="text/css" href="_homepage.css" />
    <link rel="stylesheet" type="text/css" href="_artisti.css" />
</head>
<body>

    <?php include 'menu.php'; ?>

    <div id="main" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh; font-family: sans-serif;">
        <div style="width: 100%; max-width: 450px; background-color: #121212; border-radius: 8px; padding: 40px; box-sizing: border-box; border: 1px solid #282828;">
            
            <div style="text-align: center; margin-bottom: 30px;">
                <h1 style="color: white; font-size: 28px; font-weight: bold; margin: 0 0 10px 0;">Accedi a Spotify</h1>
                <p style="color: #b3b3b3; font-size: 14px; margin: 0;">Inserisci i dati per accedere come Utente o come Admin.</p>
            </div>

            <?php if (!empty($errore)): ?>
                <div style="background-color: #e11d48; color: white; padding: 12px; border-radius: 4px; margin-bottom: 20px; text-align: center; font-size: 14px; font-weight: bold;">
                    ⚠️ <?php echo $errore; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post">
                <div style="margin-bottom: 20px;">
                    <label for="username" style="color: white; font-size: 14px; font-weight: bold; display: block; margin-bottom: 8px;">Nome utente</label>
                    <input type="text" id="username" name="username" placeholder="admin o utente" required="required" 
                           style="width: 100%; padding: 12px; background-color: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; font-size: 14px; box-sizing: border-box;" />
                </div>

                <div style="margin-bottom: 30px;">
                    <label for="password" style="color: white; font-size: 14px; font-weight: bold; display: block; margin-bottom: 8px;">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required="required" 
                           style="width: 100%; padding: 12px; background-color: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; font-size: 14px; box-sizing: border-box;" />
                </div>

                <button type="submit" style="width: 100%; background-color: #1db954; color: black; border: none; padding: 14px; border-radius: 25px; font-size: 15px; font-weight: bold; cursor: pointer; text-transform: uppercase; letter-spacing: 1px;">
                    Accedi
                </button>
            </form>
        </div>
    </div>
</body>
</html>