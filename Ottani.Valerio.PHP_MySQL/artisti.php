<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'connection.php';

$messaggio = "";

// 1. GESTIONE RIMOZIONE ARTISTA (Solo se Admin)
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rimuovi_artista'])) {
    $id_da_rimuovere = intval($_POST['id_artista_rimuovi']);
    
    $sql_delete = "DELETE FROM " . TAB_ARTISTS . " WHERE id = $id_da_rimuovere";
    if ($conn->query($sql_delete) === TRUE) {
        $messaggio = "<div style='background-color: #e11d48; color: white; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;'>🔴 Artista rimosso con successo dal database!</div>";
    } else {
        $messaggio = "<div style='background-color: #e11d48; color: white; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;'>❌ Errore durante la rimozione: " . $conn->error . "</div>";
    }
}

// 2. GESTIONE INSERIMENTO NUOVO ARTISTA (Solo se Admin)
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aggiungi_artista'])) {
    $nome = $conn->real_escape_string(trim($_POST['nome_artista']));
    $biografia = $conn->real_escape_string(trim($_POST['biografia_artista']));
    $immagine_db = $conn->real_escape_string(trim($_POST['immagine_artista']));

    if (empty($immagine_db)) {
        $immagine_db = "album.png";
    }

    if (!empty($nome)) {
        $sql_insert = "INSERT INTO " . TAB_ARTISTS . " (nome, biografia, immagine) VALUES ('$nome', '$biografia', '$immagine_db')";
        if ($conn->query($sql_insert) === TRUE) {
            $messaggio = "<div style='background-color: #1db954; color: black; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;'>🟢 Artista aggiunto con successo!</div>";
        } else {
            $messaggio = "<div style='background-color: #e11d48; color: white; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;'>❌ Errore durante l'inserimento: " . $conn->error . "</div>";
        }
    }
}

// 3. Recuperiamo tutti gli artisti dal database
$query = "SELECT * FROM " . TAB_ARTISTS . " ORDER BY nome ASC";
$risultato = $conn->query($query);

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Altri Artisti - Spotify Style</title>
    <link rel="stylesheet" type="text/css" href="_homepage.css" />
    <link rel="stylesheet" type="text/css" href="_artisti.css" />
    
    <style type="text/css">
        /* FORZA IL LINK "ALTRI ARTISTI" DELLA SIDEBAR A DIVENTARE VERDE */
        #menu-artisti,
        .sidebar a[href="artisti.php"],
        #sidebar a[href="artisti.php"],
        #nav a[href="artisti.php"],
        ul li a[href="artisti.php"],
        .sidebar div a[href="artisti.php"] {
            color: #1db954 !important;
            font-weight: bold !important;
        }

        /* EFFETTO RILIEVO E TRANSIZIONE FLUIDA IN STILE SPOTIFY */
        .artist-card {
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease !important;
            cursor: pointer;
        }
        
        /* Stato HOVER: quando passi sopra con il cursore */
        .artist-card:hover {
            background-color: #282828 !important; 
            transform: translateY(-8px);          
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.7) !important; 
        }
        
        .artist-card a {
            display: block;
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body style="background-color: #121212; color: white; margin: 0; font-family: sans-serif;">

    <?php include 'menu.php'; ?>

    <div id="main" style="padding: 20px; font-family: sans-serif; padding-bottom: 60px; margin-left: 240px;">
        
        <div style="margin-bottom: 30px;">
            <h1 style="color: white; font-size: 28px; font-weight: bold;">Artisti suggeriti</h1>
        </div>

        <?php echo $messaggio; ?>

        <div class="artist-grid" style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 5px;">
            <?php if ($risultato && $risultato->num_rows > 0): ?>
                <?php while ($row = $risultato->fetch_assoc()): ?>
                    
                    <div class="artist-card" style="background-color: #181818; padding: 16px; border-radius: 8px; width: 180px; text-align: center; box-sizing: border-box; position: relative;">
                        
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                            <form action="artisti.php" method="POST" style="position: absolute; top: 8px; right: 8px; z-index: 10;" onsubmit="return confirm('Sei sicuro di voler eliminare definitivamente questo artista?');">
                                <input type="hidden" name="id_artista_rimuovi" value="<?php echo $row['id']; ?>" />
                                <button type="submit" name="rimuovi_artista" style="background-color: #e11d48; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; font-weight: bold; font-size: 12px; display: flex; align-items: center; justify-content: center;" title="Elimina Artista">
                                    X
                                </button>
                            </form>
                        <?php endif; ?>

                        <a href="artista.php?id=<?php echo $row['id']; ?>">
                            <?php 
                            // Controllo speciale per forzare l'immagine di Luchè
                            if (trim($row['nome']) === 'Luchè') {
                                $foto_artista = "primo_piano.png";
                            } else {
                                $db_immagine = isset($row['immagine']) ? trim($row['immagine']) : '';
                                $db_immagine = str_replace('img/', '', $db_immagine);

                                if (!empty($db_immagine) && file_exists("img/" . $db_immagine)) {
                                    $foto_artista = $db_immagine;
                                } else {
                                    $nome_pulito = strtolower(str_replace(' ', '_', $row['nome']));
                                    if (file_exists("img/" . $nome_pulito . "_sfondo.png")) {
                                        $foto_artista = $nome_pulito . "_sfondo.png";
                                    } elseif (file_exists("img/" . $nome_pulito . ".jpg")) {
                                        $foto_artista = $nome_pulito . ".jpg";
                                    } else {
                                        $foto_artista = "geolier_sfondo.png"; 
                                    }
                                }
                            }
                            ?>
                            
                            <img src="img/<?php echo $foto_artista; ?>" alt="<?php echo htmlspecialchars($row['nome']); ?>" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; box-shadow: 0 8px 24px rgba(0,0,0,.5); margin: 0 auto 12px auto; display: block;" />
                            
                            <h3 style="color: white; font-size: 16px; margin: 0 0 4px 0; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($row['nome']); ?></h3>
                            <p style="color: #b3b3b3; font-size: 12px; margin: 0;">Artista</p>
                        </a>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #b3b3b3; padding: 20px;">Nessun artista presente nel database.</p>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
            <div style="margin-top: 50px; padding: 25px; border: 1px solid #1db954; background-color: #181818; border-radius: 8px; max-width: 600px; box-sizing: border-box;">
                <h3 style="color: #1db954; margin-top: 0; font-size: 18px; margin-bottom: 20px;">Pannello Admin: Aggiungi Nuovo Artista</h3>
                
                <form action="artisti.php" method="POST">
                    <div style="margin-bottom: 15px;">
                        <label for="nome_artista" style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">Nome Artista:</label>
                        <input type="text" id="nome_artista" name="nome_artista" required="required" placeholder="Es. Luchè" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; box-sizing: border-box;" />
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="immagine_artista" style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">Nome File Foto (es. gue.jpg o carletto_sfondo.png):</label>
                        <input type="text" id="immagine_artista" name="immagine_artista" placeholder="Es. luche.jpg" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; box-sizing: border-box;" />
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="biografia_artista" style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">Biografia:</label>
                        <textarea id="biografia_artista" name="biografia_artista" rows="5" required="required" placeholder="Scrivi la biografia dell'artista..." style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; font-family: sans-serif; box-sizing: border-box; resize: vertical;"></textarea>
                    </div>

                    <button type="submit" name="aggiungi_artista" style="background-color: #1db954; color: black; border: none; padding: 12px 24px; border-radius: 25px; font-weight: bold; cursor: pointer; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">
                        Inserisci Artista nel DB
                    </button>
                </form>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
<?php $conn->close(); ?>