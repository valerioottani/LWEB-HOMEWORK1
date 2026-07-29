<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'connection.php';

$messaggio = "";

// 1. GESTIONE RIMOZIONE EVENTO (Solo se Admin)
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rimuovi_evento'])) {
    $id_evento = intval($_POST['id_evento_rimuovi']);
    
    // Ipotizzando che la tabella si chiami 'eventi' o tramite costante se definita
    $tabella_eventi = defined('TAB_EVENTS') ? TAB_EVENTS : 'eventi';
    $sql_delete = "DELETE FROM $tabella_eventi WHERE id = $id_evento";
    
    if ($conn->query($sql_delete) === TRUE) {
        $messaggio = "<div style='background-color: #e11d48; color: white; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;'>🔴 Evento rimosso con successo!</div>";
    } else {
        $messaggio = "<div style='background-color: #e11d48; color: white; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;'>❌ Errore durante la rimozione: " . $conn->error . "</div>";
    }
}

// 2. GESTIONE INSERIMENTO NUOVO EVENTO (Solo se Admin)
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aggiungi_evento'])) {
    $titolo = $conn->real_escape_string(trim($_POST['titolo_evento']));
    $data_evento = $conn->real_escape_string(trim($_POST['data_evento']));
    $luogo = $conn->real_escape_string(trim($_POST['luogo_evento']));

    if (!empty($titolo) && !empty($data_evento)) {
        $tabella_eventi = defined('TAB_EVENTS') ? TAB_EVENTS : 'eventi';
        $sql_insert = "INSERT INTO $tabella_eventi (titolo, data, luogo) VALUES ('$titolo', '$data_evento', '$luogo')";
        
        if ($conn->query($sql_insert) === TRUE) {
            $messaggio = "<div style='background-color: #1db954; color: black; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;'>🟢 Evento aggiunto con successo!</div>";
        } else {
            $messaggio = "<div style='background-color: #e11d48; color: white; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold;'>❌ Errore: " . $conn->error . "</div>";
        }
    }
}

// 3. RECUPERO EVENTI DAL DB
$tabella_eventi = defined('TAB_EVENTS') ? TAB_EVENTS : 'eventi';
$query = "SELECT * FROM $tabella_eventi ORDER BY data ASC";
$risultato = $conn->query($query);

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Eventi e Concerti - Spotify Style</title>
    <link rel="stylesheet" type="text/css" href="_homepage.css" />
    
    <style type="text/css">
        /* 🟢 FORZA IL LINK "EVENTI" DELLA SIDEBAR A DIVENTARE VERDE 🟢 */
        #menu-eventi,
        .sidebar a[href="eventi.php"],
        #sidebar a[href="eventi.php"],
        #nav a[href="eventi.php"],
        ul li a[href="eventi.php"] {
            color: #1db954 !important;
            font-weight: bold !important;
        }
    </style>
</head>
<body style="background-color: #121212; color: white; margin: 0; font-family: sans-serif;">

    <?php include 'menu.php'; ?>

    <div id="main" style="padding: 20px; margin-left: 240px; padding-bottom: 60px;">
        
        <div style="margin-bottom: 30px;">
            <h1 style="color: white; font-size: 28px; font-weight: bold;">Prossimi Eventi</h1>
            <p style="color: #b3b3b3; font-size: 14px;">Non perderti i concerti live dei tuoi artisti preferiti.</p>
        </div>

        <?php echo $messaggio; ?>

        <div style="background-color: #181818; padding: 20px; border-radius: 8px; max-width: 800px;">
            <?php if ($risultato && $risultato->num_rows > 0): ?>
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 1px solid #282828; color: #b3b3b3; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                            <th style="padding: 12px;">Data</th>
                            <th style="padding: 12px;">Evento / Tour</th>
                            <th style="padding: 12px;">Luogo</th>
                            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                                <th style="padding: 12px; text-align: right;">Azione</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $risultato->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #282828; font-size: 14px; color: #e5e5e5;" onmouseover="this.style.backgroundColor='#282828'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 16px; font-weight: bold; color: #1db954;">
                                    <?php echo date('d/m/Y', strtotime($row['data'])); ?>
                                </td>
                                <td style="padding: 16px; font-weight: bold; color: white;">
                                    <?php echo htmlspecialchars($row['titolo']); ?>
                                </td>
                                <td style="padding: 16px; color: #b3b3b3;">
                                    <?php echo htmlspecialchars($row['luogo']); ?>
                                </td>
                                <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                                    <td style="padding: 16px; text-align: right;">
                                        <form action="eventi.php" method="POST" style="margin: 0;" onsubmit="return confirm('Vuoi eliminare questo evento?');">
                                            <input type="hidden" name="id_evento_rimuovi" value="<?php echo $row['id']; ?>" />
                                            <button type="submit" name="rimuovi_evento" style="background-color: #e11d48; color: white; border: none; border-radius: 4px; padding: 6px 12px; cursor: pointer; font-weight: bold; font-size: 12px;">
                                                Elimina
                                            </button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #b3b3b3; margin: 0; padding: 10px 0;">Al momento non ci sono concerti o eventi in programma.</p>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
            <div style="margin-top: 40px; padding: 25px; border: 1px solid #1db954; background-color: #181818; border-radius: 8px; max-width: 600px; box-sizing: border-box;">
                <h3 style="color: #1db954; margin-top: 0; font-size: 18px; margin-bottom: 20px;">Pannello Admin: Aggiungi Nuovo Evento</h3>
                
                <form action="eventi.php" method="POST">
                    <div style="margin-bottom: 15px;">
                        <label for="titolo_evento" style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">Nome Evento / Tour:</label>
                        <input type="text" id="titolo_evento" name="titolo_evento" required="required" placeholder="Es. Geolier Live 2026" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; box-sizing: border-box;" />
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label for="data_evento" style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">Data Concerto:</label>
                        <input type="date" id="data_evento" name="data_evento" required="required" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; box-sizing: border-box;" />
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="luogo_evento" style="color: #b3b3b3; font-size: 14px; display: block; margin-bottom: 5px;">Luogo / Stadio / Città:</label>
                        <input type="text" id="luogo_evento" name="luogo_evento" required="required" placeholder="Es. Stadio Maradona, Napoli" style="width: 100%; padding: 10px; background: #2a2a2a; border: 1px solid #3e3e3e; color: white; border-radius: 4px; box-sizing: border-box;" />
                    </div>

                    <button type="submit" name="aggiungi_evento" style="background-color: #1db954; color: black; border: none; padding: 12px 24px; border-radius: 25px; font-weight: bold; cursor: pointer; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">
                        Inserisci Evento nel DB
                    </button>
                </form>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>