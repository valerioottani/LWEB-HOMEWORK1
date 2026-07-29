<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'connection.php';

$messaggio = "";

// 1. GESTIONE RIMOZIONE ALBUM DALLA PAGINA ARTISTA (Solo se Admin)
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rimuovi_album'])) {
    $id_album_da_rimuovere = intval($_POST['id_album_rimuovi']);
    $current_artista_id = intval($_POST['artista_id']);

    $sql_delete = "DELETE FROM " . TAB_ALBUMS . " WHERE id = $id_album_da_rimuovere AND artista_id = $current_artista_id";
    if ($conn->query($sql_delete) === TRUE) {
        $messaggio = "<div style='background-color: #e11d48; color: white; padding: 12px; border-radius: 4px; margin: 20px 32px 0 32px; font-weight: bold;'>🔴 Album rimosso con successo dal database!</div>";
    } else {
        $messaggio = "<div style='background-color: #e11d48; color: white; padding: 12px; border-radius: 4px; margin: 20px 32px 0 32px; font-weight: bold;'>❌ Errore durante la rimozione: " . $conn->error . "</div>";
    }
}

// 2. Recuperiamo l'ID dell'artista dall'URL (se non c'è, impostiamo 1)
$artista_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// 3. Query per recuperare i dati dell'artista corrente
$query_artista = "SELECT * FROM " . TAB_ARTISTS . " WHERE id = $artista_id";
$risultato_artista = $conn->query($query_artista);

if ($risultato_artista && $risultato_artista->num_rows > 0) {
    $artista = $risultato_artista->fetch_assoc();
} else {
    die("Artista non trovato nel database!");
}

// 4. GESTIONE DELL'INSERIMENTO DI UN NUOVO BRANO/ALBUM (Solo se Admin)
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aggiungi_album'])) {
    $titolo_album = $conn->real_escape_string(trim($_POST['titolo_album']));
    $anno_album = intval($_POST['anno_album']);
    $copertina_album = $conn->real_escape_string(trim($_POST['copertina_album']));
    $current_artista_id = intval($_POST['artista_id']); 

    if (empty($copertina_album)) {
        $copertina_album = "album.png";
    }

    if (!empty($titolo_album)) {
        $sql_insert_album = "INSERT INTO " . TAB_ALBUMS . " (artista_id, titolo, anno, copertina) 
                             VALUES ($current_artista_id, '$titolo_album', $anno_album, '$copertina_album')";
        if ($conn->query($sql_insert_album) === TRUE) {
            header("Location: artista.php?id=" . $current_artista_id);
            exit;
        }
    }
}

// 5. Query per recuperare gli album di questo specifico artista
$query_album = "SELECT * FROM " . TAB_ALBUMS . " WHERE artista_id = $artista_id ORDER BY anno DESC";
$risultato_album = $conn->query($query_album);

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo htmlspecialchars($artista['nome']); ?> - Spotify Style</title>
    <link rel="stylesheet" type="text/css" href="artista_dettaglio.css" />
    <link rel="stylesheet" type="text/css" href="_artisti.css" />
    
    <style type="text/css">
        /* Sfondo dinamico personalizzato per l'header dell'artista se presente nel DB */
        #header {
            <?php 
            $foto_header = !empty($artista['immagine']) ? str_replace('img/', '', $artista['immagine']) : 'primo_piano.png';
            if (file_exists("img/" . $foto_header)): 
            ?>
            background-image: url('img/<?php echo $foto_header; ?>') !important;
            <?php endif; ?>
        }
    </style>
</head>
<body style="background-color: #121212; color: white; margin: 0; font-family: sans-serif;">

    <?php include 'menu.php'; ?>

    <div id="main" style="margin-left: 240px; padding-bottom: 90px;">
        <div id="header" style="padding: 150px 32px 60px 32px; background-repeat: no-repeat; background-size: cover; background-position: center; position: relative;">
            <p style="font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">ARTISTA VERIFICATO</p>
            <h1 style="font-size: 72px; margin: 10px 0; font-weight: 900; text-shadow: 0 2px 10px rgba(0,0,0,0.5);"><?php echo htmlspecialchars($artista['nome']); ?></h1>
            <p class="ascoltatori-mensili" style="font-size: 16px; font-weight: 500; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">5,1 Mln ascoltatori mensili</p>
        </div>

        <?php echo $messaggio; ?>

        <div id="Popolari" style="padding: 30px 32px;">
            <h2 style="font-size: 24px; margin-bottom: 20px;">Album</h2>
            <ol style="padding-left: 20px; color: #b3b3b3;">
                <?php if ($risultato_album && $risultato_album->num_rows > 0): ?>
                    <?php while($album = $risultato_album->fetch_assoc()): ?>
                        <li style="margin-bottom: 15px; position: relative; list-style-position: inside;">
                            <?php 
                            $copertina = !empty($album['copertina']) ? str_replace('img/', '', $album['copertina']) : 'album.png';
                            ?>
                            <img src="img/<?php echo $copertina; ?>" alt="Album" style="width:40px; height:40px; object-fit:cover; border-radius:4px; vertical-align: middle; margin-right: 15px;" />
                            <span class="nome-brano" style="color: white; font-size: 14px; font-weight: 500; vertical-align: middle; margin-right: 20px;"><?php echo htmlspecialchars($album['titolo']); ?> (<?php echo $album['anno']; ?>)</span>
                            
                            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                                <form action="artista.php?id=<?php echo $artista_id; ?>" method="POST" style="display: inline; vertical-align: middle;" onsubmit="return confirm('Vuoi eliminare definitivamente questo album da questo artista?');">
                                    <input type="hidden" name="id_album_rimuovi" value="<?php echo $album['id']; ?>" />
                                    <input type="hidden" name="artista_id" value="<?php echo $artista_id; ?>" />
                                    <button type="submit" name="rimuovi_album" style="background-color: #e11d48; color: white; border: none; border-radius: 4px; padding: 4px 8px; font-weight: bold; font-size: 11px; cursor: pointer;" title="Elimina Album">
                                        Elimina
                                    </button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li style="list-style-type: none; color: #b3b3b3;">Nessun album o brano caricato per questo artista.</li>
                <?php endif; ?>
            </ol>
        </div>
        
        <div class="content" style="padding: 10px 32px 30px 32px;">
            <h3 style="font-size: 20px; margin-bottom: 10px;">Biografia</h3>
            <p style="color: #b3b3b3; max-width: 800px; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($artista['biografia'])); ?></p>     
        </div>

        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
            <div class="content" style="margin: 0 32px 40px 32px; padding: 25px; border: 1px solid #1db954; background-color: #181818; border-radius: 8px; max-width: 600px;">
                <h3 style="color: #1db954; margin-top: 0; font-size: 18px; margin-bottom: 20px;">Pannello Admin: Aggiungi Album per questo artista</h3>
                <form action="artista.php?id=<?php echo $artista_id; ?>" method="POST">
                    <input type="hidden" name="artista_id" value="<?php echo $artista_id; ?>" />
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">Titolo Album/Traccia:</label>
                        <input type="text" name="titolo_album" required="required" placeholder="Es. Santana Season" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; box-sizing: border-box;" />
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">Anno di Uscita:</label>
                        <input type="number" name="anno_album" value="2026" required="required" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; box-sizing: border-box;" />
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">File Copertina (es. geolier.jpg):</label>
                        <input type="text" name="copertina_album" placeholder="Es. madreperla.png" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; box-sizing: border-box;" />
                    </div>
                    
                    <button type="submit" name="aggiungi_album" style="background-color: #1db954; color: black; border: none; padding: 12px 24px; border-radius: 25px; font-weight: bold; cursor: pointer; text-transform: uppercase; font-size: 12px;">
                        Salva nel Database
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
<?php $conn->close(); ?>