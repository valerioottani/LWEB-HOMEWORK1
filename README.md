# Link delle repository:
- https://github.com/valerioottani/LWEB-HOMEWORK1.git
- https://github.com/Giogiogabbana/LWEB-HOMEWORK1.git
# Autori:
- Ottani Valerio (nickname: valerioottani)
- La Penna Giovanni


# LWEB-Homework 1: Fan Page Luchè (Spotify Style)

Questo homework consiste nella realizzazione di una piattaforma web dedicata all'artista **Luchè**, progettata seguendo l'interfaccia utente e l'esperienza d'uso di **Spotify**. Il focus principale dell'elaborato è la corretta implementazione degli standard **XHTML 1.0 Strict** e la separazione dei contenuti dalla presentazione tramite **CSS**.


##  Architettura Tecnologica
### Struttura XHTML (Contenuto)
Il sito è costruito utilizzando esclusivamente **XHTML 1.0 Strict**. Le caratteristiche principali includono:
* **Dichiarazione XML**: Ogni file inizia con `<?xml version="1.0" encoding="UTF-8"?>`.
* **Sintassi Rigorosa**: Tutti i tag sono scritti in minuscolo e correttamente chiusi (es. `<img />`, `<br />`).
* **Validazione**: Tutte le pagine hanno superato il controllo del **W3C Markup Validator**.

### Design e CSS (Presentazione)
La veste grafica è gestita tramite fogli di stile modulari per garantire pulizia e manutenibilità:
* **_homepage.css**: Gestisce il layout principale, la sidebar fissa e il player inferiore.
* **_discografia.css**: Implementa una griglia fluida per le copertine degli album.
* **_artisti.css**: Crea il tipico effetto circolare per le foto degli artisti suggeriti.
* **_eventi.css**: Stilizza la tabella dei concerti rendendola interattiva al passaggio del mouse.

## Organizzazione del Sito
l'Homework è suddiviso nelle seguenti sezioni navigabili:
1. **Home**: Biografia dettagliata e traccia in primo piano con header a tutta larghezza.
2. **Discografia**: Elenco completo degli album (es. *Malammore*, *Potere*, *L1*) visualizzati tramite card interattive.
3. **Eventi**: Tabella degli appuntamenti live con data, location e link per i biglietti.
4. **Altri Artisti**: Suggerimenti di profili correlati (Geolier, Guè, Marracash) con avatar circolari.

## Componenti Visuali Avanzati
* **Sidebar Fissa**: Menu laterale sempre visibile per una navigazione rapida.
* **Effetti Hover**: Animazioni di zoom sulle immagini e transizioni di colore per migliorare l'interattività.

---
*Homework realizzato per l'esame di Linguaggi per il Web - Homework 1.*
