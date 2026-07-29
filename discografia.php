<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'connection.php';

$messaggio = "";

// 1. GESTIONE RIMOZIONE ALBUM (Solo se Admin)
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rimuovi_album'])) {
    $id_da_rimuovere = intval($_POST['id_album_rimuovi']);
    
    $sql_delete = "DELETE FROM album WHERE id = $id_da_rimuovere";
    if ($conn->query($sql_delete) === TRUE) {
        $messaggio = "<div style='background-color: #e11d48; color: white; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;'>🔴 Album rimosso con successo dal database!</div>";
    } else {
        $messaggio = "<div style='background-color: #e11d48; color: white; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;'>❌ Errore durante la rimozione: " . $conn->error . "</div>";
    }
}

// 2. GESTIONE INSERIMENTO NUOVO ALBUM (Solo se Admin)
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aggiungi_album'])) {
    $titolo = $conn->real_escape_string(trim($_POST['titolo_album']));
    $anno = intval($_POST['anno_album']);
    $artista_id = intval($_POST['artista_id']);
    $copertina = $conn->real_escape_string(trim($_POST['copertina_album']));

    if (empty($copertina)) {
        $copertina = "album.png";
    }

    if (!empty($titolo) && $artista_id > 0) {
        $sql_insert = "INSERT INTO album (artista_id, titolo, anno, copertina) VALUES ($artista_id, '$titolo', $anno, '$copertina')";
        if ($conn->query($sql_insert) === TRUE) {
            $messaggio = "<div style='background-color: #1db954; color: black; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;'>🟢 Album aggiunto con successo!</div>";
        } else {
            $messaggio = "<div style='background-color: #e11d48; color: white; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;'>❌ Errore durante l'inserimento: " . $conn->error . "</div>";
        }
    }
}

// 3. Recuperiamo tutti gli album uniti agli artisti per mostrare i dettagli completi
$query = "SELECT album.*, artisti.nome AS artista_nome FROM album JOIN artisti ON album.artista_id = artisti.id ORDER BY album.anno DESC";
$risultato = $conn->query($query);

// Recuperiamo la lista degli artisti per il menu a tendina del pannello inserimento
$query_artisti = "SELECT id, nome FROM artisti ORDER BY nome ASC";
$risultato_artisti = $conn->query($query_artisti);

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Discografia - Spotify Style</title>
    <link rel="stylesheet" type="text/css" href="_homepage.css" />
    
    <style type="text/css">
        /* 🟢 FORZA IL LINK "DISCOGRAFIA" DELLA SIDEBAR A DIVENTARE VERDE 🟢 */
        #menu-discografia,
        .sidebar a[href="discografia.php"],
        #sidebar a[href="discografia.php"],
        #nav a[href="discografia.php"],
        ul li a[href="discografia.php"] {
            color: #1db954 !important;
            font-weight: bold !important;
        }

        .album-card, .card {
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease !important;
            cursor: default !important; /* Mantiene la freccia standard, nessuna manina */
            user-select: none;          /* Evita la selezione accidentale del testo */
        }
        
        /* Mantiene l'effetto rilievo estetico senza l'interazione del link */
        .album-card:hover, .card:hover {
            background-color: #282828 !important; 
            transform: translateY(-8px);          
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.7) !important; 
        }
    </style>
</head>
<body style="background-color: #121212; color: white; margin: 0; font-family: sans-serif;">

    <?php include 'menu.php'; ?>

    <div id="main" style="padding: 20px; padding-bottom: 60px;">
        
        <div style="margin-bottom: 30px;">
            <h1 style="color: white; font-size: 28px; font-weight: bold;">Album ed EP</h1>
            <p style="color: #b3b3b3; font-size: 14px;">Esplora le ultime uscite dei tuoi artisti preferiti.</p>
        </div>

        <?php echo $messaggio; ?>

        <div class="album-grid" style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 5px;">
            <?php if ($risultato && $risultato->num_rows > 0): ?>
                <?php while ($row = $risultato->fetch_assoc()): ?>
                    
                    <div class="album-card" style="background-color: #181818; padding: 16px; border-radius: 8px; width: 180px; text-align: left; box-sizing: border-box; position: relative;">
                        
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                            <form action="discografia.php" method="POST" style="position: absolute; top: 8px; right: 8px; z-index: 10;" onsubmit="return confirm('Sei sicuro di voler eliminare definitivamente questo album?');">
                                <input type="hidden" name="id_album_rimuovi" value="<?php echo $row['id']; ?>" />
                                <button type="submit" name="rimuovi_album" style="background-color: #e11d48; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; font-weight: bold; font-size: 12px; display: flex; align-items: center; justify-content: center;" title="Elimina Album">
                                    X
                                </button>
                            </form>
                        <?php endif; ?>

                        <?php 
                        $copertina_album = !empty($row['copertina']) ? trim($row['copertina']) : 'album.png';
                        $copertina_album = str_replace('img/', '', $copertina_album);
                        ?>
                        
                        <img src="img/<?php echo $copertina_album; ?>" alt="<?php echo htmlspecialchars($row['titolo']); ?>" style="width: 148px; height: 148px; object-fit: cover; border-radius: 4px; box-shadow: 0 8px 24px rgba(0,0,0,.5); margin-bottom: 16px; display: block;" />
                        
                        <h3 style="color: white; font-size: 14px; margin: 0 0 4px 0; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($row['titolo']); ?></h3>
                        <p style="color: #b3b3b3; font-size: 12px; margin: 0 0 4px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo htmlspecialchars($row['artista_nome']); ?></p>
                        <p style="color: #6a6a6a; font-size: 11px; margin: 0; font-weight: bold;"><?php echo $row['anno']; ?></p>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #b3b3b3; padding: 20px;">Nessun album presente nel database.</p>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
            <div style="margin-top: 50px; padding: 25px; border: 1px solid #1db954; background-color: #181818; border-radius: 8px; max-width: 600px; box-sizing: border-box;">
                <h3 style="color: #1db954; margin-top: 0; font-size: 18px; margin-bottom: 20px;">Pannello Admin: Aggiungi Nuovo Album</h3>
                
                <form action="discografia.php" method="POST">
                    <div style="margin-bottom: 15px;">
                        <label for="titolo_album" style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">Titolo Album:</label>
                        <input type="text" id="titolo_album" name="titolo_album" required="required" placeholder="Es. Madreperla" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; box-sizing: border-box;" />
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="artista_id" style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">Associa ad Artista:</label>
                        <select id="artista_id" name="artista_id" required="required" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; box-sizing: border-box;">
                            <option value="">-- Seleziona l'artista --</option>
                            <?php if ($risultato_artisti && $risultato_artisti->num_rows > 0): ?>
                                <?php while ($art = $risultato_artisti->fetch_assoc()): ?>
                                    <option value="<?php echo $art['id']; ?>"><?php echo htmlspecialchars($art['nome']); ?></option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="anno_album" style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">Anno di Pubblicazione:</label>
                        <input type="number" id="anno_album" name="anno_album" required="required" min="1900" max="2030" placeholder="Es. 2023" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; box-sizing: border-box;" />
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="copertina_album" style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">Nome File Copertina (es. brivido.png o lazza.jpg):</label>
                        <input type="text" id="copertina_album" name="copertina_album" placeholder="Es. copertina.png" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; box-sizing: border-box;" />
                    </div>

                    <button type="submit" name="aggiungi_album" style="background-color: #1db954; color: black; border: none; padding: 12px 24px; border-radius: 25px; font-weight: bold; cursor: default; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">
                        Inserisci Album nel DB
                    </button>
                </form>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
<?php $conn->close(); ?>