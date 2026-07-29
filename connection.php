<?php
// Includiamo prima di tutto il file con i dati di configurazione
require_once 'dati_generali.php';

// Creiamo l'oggetto di connessione sfruttando l'estensione MySQLi nativa di PHP
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

// Verifichiamo se si sono verificati errori durante la connessione
if ($conn->connect_error) {
    // Se c'è un errore, blocchiamo l'esecuzione del sito e mostriamo il problema
    die("<h3 style='color:red;'>Errore irreversibile di connessione al database: " . $conn->connect_error . "</h3>");
}

// Opzionale: impostiamo la codifica caratteri corretta per evitare problemi con accenti o caratteri speciali
$conn->set_charset("utf8mb4");
?>