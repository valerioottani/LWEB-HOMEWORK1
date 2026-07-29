<?php
// Credenziali di accesso al server MySQL/MariaDB
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');       // Cambiare se l'utente MySQL ha un altro nome
define('DB_PASS', '');           // Cambiare se hai impostato una password su MySQL

// Nome del database (richiesto esplicitamente dal docente nel formato nome.cognome)
define('DB_NAME', 'valerio.ottani.PHP-MySQL'); 

// Nomi delle tabelle per una gestione centralizzata
define('TAB_ARTISTS', 'artisti');
define('TAB_ALBUMS', 'album');
define('TAB_USERS', 'utenti');
?>