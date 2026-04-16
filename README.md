🧑🏻‍💻 Autore:
   Valerio Ottani
    Giovanni La Penna

📘 Descrizione:
Questo progetto sono pagine dedicate all'artista Luchè, sviluppata simulando l'interfaccia grafica di Spotify. Il sito è stato realizzato utilizzando XHTML 1.0 Strict per garantire una struttura semantica rigorosa e CSS3 per una presentazione moderna e accattivante.

L'obiettivo dell'homework è dimostrare la capacità di separare nettamente il contenuto dalla formattazione, rispettando gli standard internazionali del W3C. Il sito include sezioni dedicate alla biografia, alla discografia completa, agli eventi live e agli artisti correlati.

📂 Struttura della cartella LWEB-Homework1
    img/: cartella contenente le immagini dell'artista, delle copertine degli album e le icone social.
    Validazione_W3C_Files/: cartelle o file PDF che attestano la validazione XHTML delle pagine.
    homepage.html: introduzione e biografia dell'artista.
    discografia.html: griglia degli album in stile "Spotify Tiles".
    eventi.html: tabella riassuntiva dei concerti e tour.
    artisti.html: sezione dedicata agli artisti suggeriti e collaboratori.
    _homepage.css: file di stile specifico per la home.
    _discografia.css: gestione del layout a griglia della discografia.
    _eventi.css: stile della tabella e degli appuntamenti.
    _artisti.css: stile per le card degli artisti correlati.

🧱 Struttura XHTML
Ogni pagina adotta rigorosamente:
    DOCTYPE XHTML 1.0 Strict
    Tag chiusi obbligatoriamente (es: <br />, <img />).
    Attributi obbligatori come alt per le immagini e type per i fogli di stile.
    Sintassi interamente in minuscolo.

Elementi XHTML utilizzati:
    <div>: per la gestione dei contenitori logici (sidebar, player, main content).
    <table>, <tr>, <td>: utilizzati nella pagina eventi per la visualizzazione dei dati.
    <ul>, <li>: per il menu di navigazione laterale.
    <h1>, <h2>, <p>: per la gerarchia dei testi e la biografia.
    <img>: per le cover degli album e i loghi.

🎨 Componenti CSS utilizzati:
    Layout Flexbox: utilizzato per creare la struttura a colonne (sidebar a sinistra e contenuto a destra) e la griglia fluida della discografia.
    Posizionamento Fisso: applicato alla sidebar laterale e al player musicale inferiore per simulare l'app di Spotify.
    Effetti Hover: animazioni sulle card degli album (transform: scale) e sui link di navigazione.
    Design Responsive: utilizzo di unità di misura relative e media query per garantire la leggibilità su diversi schermi.
    Stilizzazione Tabelle: bordi arrotondati, contrasti cromatici (nero/verde Spotify) e padding ottimizzati.

✅ Validazione
Tutti i file XHTML presenti nel progetto sono stati verificati tramite il W3C Markup Validator e risultano conformi allo standard XHTML 1.0 Strict
