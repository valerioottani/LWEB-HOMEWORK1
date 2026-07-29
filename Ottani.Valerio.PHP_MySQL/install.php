<?php
// Includiamo il file con le costanti di configurazione
require_once 'dati_generali.php';

// 1. Connessione iniziale al DBMS (senza specificare il DB, dato che dobbiamo ancora crearlo)
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS);

// Controllo se la connessione iniziale fallisce
if ($conn->connect_error) {
    die("<h3 style='color:red;'>Connessione al DBMS fallita: " . $conn->connect_error . "</h3>");
}

echo "Connessione al server MySQL riuscita...<br />";

// 2. Creazione del Database richiesto dal docente se non esiste
$sql_create_db = "CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";

if ($conn->query($sql_create_db) === TRUE) {
    echo "Database '<strong>" . DB_NAME . "</strong>' creato o già esistente.<br />";
} else {
    die("<h3 style='color:red;'>Errore nella creazione del database: " . $conn->error . "</h3>");
}

// 3. Selezioniamo il database appena creato per lavorarci dentro
$conn->select_db(DB_NAME);

// --- 4. CREAZIONE TABELLA ARTISTI ---
$sql_tabella_artisti = "CREATE TABLE IF NOT EXISTS `" . TAB_ARTISTS . "` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL UNIQUE,
    `biografia` TEXT NOT NULL,
    `immagine` VARCHAR(100) NOT NULL
)";

if ($conn->query($sql_tabella_artisti) === TRUE) {
    echo "Tabella '" . TAB_ARTISTS . "' verificata/creata con successo.<br />";
} else {
    die("Errore creazione tabella artisti: " . $conn->error);
}

// --- 5. CREAZIONE TABELLA UTENTI (Per il Login dell'Area Admin) ---
$sql_tabella_utenti = "CREATE TABLE IF NOT EXISTS `" . TAB_USERS . "` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
)";

if ($conn->query($sql_tabella_utenti) === TRUE) {
    echo "Tabella '" . TAB_USERS . "' verificata/creata con successo.<br />";
} else {
    die("Errore creazione tabella utenti: " . $conn->error);
}

// --- 6. CREAZIONE TABELLA ALBUM ---
$sql_tabella_album = "CREATE TABLE IF NOT EXISTS `" . TAB_ALBUMS . "` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `artista_id` INT NOT NULL,
    `titolo` VARCHAR(150) NOT NULL,
    `anno` INT NOT NULL,
    `copertina` VARCHAR(100) NOT NULL,
    FOREIGN KEY (`artista_id`) REFERENCES `" . TAB_ARTISTS . "`(`id`) ON DELETE CASCADE
)";

if ($conn->query($sql_tabella_album) === TRUE) {
    echo "Tabella '" . TAB_ALBUMS . "' verificata/creata con successo.<br />";
} else {
    die("Errore creazione tabella album: " . $conn->error);
}

// --- 7. CREAZIONE TABELLA EVENTI (Mancava!) ---
// Assicurati che nel tuo file 'dati_generali.php' ci sia la costante TAB_EVENTS definita (es. 'eventi')
$tabella_eventi = defined('TAB_EVENTS') ? TAB_EVENTS : 'eventi';
$sql_tabella_eventi = "CREATE TABLE IF NOT EXISTS `" . $tabella_eventi . "` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `giorno` INT NOT NULL,
    `mese` VARCHAR(10) NOT NULL,
    `titolo` VARCHAR(150) NOT NULL,
    `luogo` VARCHAR(150) NOT NULL,
    `link_biglietti` VARCHAR(255) NOT NULL
)";

if ($conn->query($sql_tabella_eventi) === TRUE) {
    echo "Tabella '" . $tabella_eventi . "' verificata/creata con successo.<br />";
} else {
    die("Errore creazione tabella eventi: " . $conn->error);
}


// --- 8. POPOLAMENTO INIZIALE ARTISTI ---
echo "Inserimento dati iniziali degli artisti...<br />";

$artisti_iniziali = [
    [
        'nome' => 'Luchè',
        'biografia' => 'Luca Imprudente, noto come Luchè, è un rapper e produttore discografico italiano, componente dello storico gruppo Co\'Sang prima di intraprendere una carriera solista di enorme successo che ha ridefinito gli standard del rap italiano.',
        'immagine' => 'primo_piano.png'
    ],
    [
        'nome' => 'Geolier',
        'biografia' => 'Emanuele Palumbo, noto come Geolier, è un rapper italiano nato a Napoli nel 2000. Cresciuto nel quartiere di Secondigliano, ha conquistato la scena urban italiana unendo la tradizione della lingua napoletana con sonorità trap e hip-hop moderne.',
        'immagine' => 'geolier.jpg'
    ],
    [
        'nome' => 'Lazza',
        'biografia' => 'Jacopo Lazzarini, in arte Lazza, è un rapper, pianista e produttore discografico milanese. È celebre per la sua abilità tecnica nel freestyle e nell uso del riocontra, oltre che per aver unito la musica classica al rap d avanguardia.',
        'immagine' => 'lazza.jpg'
    ],
    [
        'nome' => 'Marracash',
        'biografia' => 'Fabio Bartolo Rizzo, noto come Marracash, è considerato il King del Rap italiano. Con una penna tra le più colte, crude e introspettive del genere, ha firmato pietre miliari della musica italiana contemporanea come Persona.',
        'immagine' => 'marracash.jpg'
    ],
    [
        'nome' => 'Sfera Ebbasta',
        'biografia' => 'Gionata Boschetti, alias Sfera Ebbasta, è il pioniere della trap in Italia. Salito alla ribalta con l album XDVR, è diventato uno degli artisti italiani più ascoltati di sempre e il primo a raggiungere importanti traguardi internazionali.',
        'immagine' => 'sfera.jpg'
    ],
    [
        'nome' => 'Gue',
        'biografia' => 'Cosimo Fini, precedentemente noto come Guè Pequeno, è una colonna portante dell hip hop italiano. Membro dello storico gruppo Club Dogo, vanta una carriera solista prolifica e una grandissima influenza sulla cultura street italiana.',
        'immagine' => 'gue.jpg'
    ]
];

foreach ($artisti_iniziali as $art) {
    $nome = $conn->real_escape_string($art['nome']);
    $biografia = $conn->real_escape_string($art['biografia']);
    $immagine = $conn->real_escape_string($art['immagine']);
    
    $conn->query("INSERT IGNORE INTO `" . TAB_ARTISTS . "` (nome, biografia, immagine) VALUES ('$nome', '$biografia', '$immagine')");
}


// --- 9. POPOLAMENTO INIZIALE ALBUM DINAMICO ---
echo "Inserimento album di test...<br />";

// Recuperiamo l'ID esatto di Luchè dal DB per evitare disallineamenti referenziali
$res_luche = $conn->query("SELECT id FROM `" . TAB_ARTISTS . "` WHERE nome = 'Luchè'");
if ($res_luche && $res_luche->num_rows > 0) {
    $row_luche = $res_luche->fetch_assoc();
    $id_luche = intval($row_luche['id']);

    $conn->query("INSERT IGNORE INTO `" . TAB_ALBUMS . "` (artista_id, titolo, anno, copertina) VALUES 
        ($id_luche, 'Malammore', 2016, 'malammore.png'),
        ($id_luche, 'Potere', 2018, 'potere.png'),
        ($id_luche, 'L1', 2012, 'l1.png')");
}

// Recuperiamo l'ID esatto di Geolier
$res_geo = $conn->query("SELECT id FROM `" . TAB_ARTISTS . "` WHERE nome = 'Geolier'");
if ($res_geo && $res_geo->num_rows > 0) {
    $row_geo = $res_geo->fetch_assoc();
    $id_geolier = intval($row_geo['id']);

    $conn->query("INSERT IGNORE INTO `" . TAB_ALBUMS . "` (artista_id, titolo, anno, copertina) VALUES 
        ($id_geolier, 'Il coraggio dei bambini', 2023, 'geolier_coraggio.jpg'),
        ($id_geolier, 'Dio lo sa', 2024, 'geolier_dio_lo_sa.jpg')");
}


// --- 10. POPOLAMENTO INIZIALE EVENTI ---
echo "Inserimento eventi live di test...<br />";
$conn->query("INSERT IGNORE INTO `" . $tabella_eventi . "` (giorno, mese, titolo, luogo, link_biglietti) VALUES 
    (18, 'LUG', 'Luchè Summer Tour', 'Piazza del Plebiscito, Napoli', 'https://www.ticketone.it'),
    (24, 'AGO', 'Palidoro Summer Festival', 'Stadio Comunale, Roma', 'https://www.ticketone.it'),
    (05, 'SET', 'LWB Special Showcase', 'Mediolanum Forum, Milano', '#')");


// --- 11. INSERIMENTO UTENTI DI TEST (Admin e Utente Standard) ---
echo "Inserimento profili utente di test...<br />";
// Profilo Amministratore
$user_admin = 'admin';
$pass_admin = password_hash('adminpassword', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO `" . TAB_USERS . "` (username, password) VALUES ('$user_admin', '$pass_admin')");

// Profilo Utente Comune (Richiesto per il README completo)
$user_standard = 'utente';
$pass_standard = password_hash('utentepassword', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO `" . TAB_USERS . "` (username, password) VALUES ('$user_standard', '$pass_standard')");


echo "<br /><h2 style='color:green;'>INSTALLAZIONE COMPLETATA CON SUCCESSO!</h2>";
echo "<p>Il database è pronto. Puoi iniziare a navigare partendo dalla <a href='homepage.php'>Homepage</a>.</p>";

// Chiudiamo la connessione
$conn->close();
?>