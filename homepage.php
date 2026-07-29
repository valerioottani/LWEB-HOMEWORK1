<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'connection.php';

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Spotify - Home</title>
    <link rel="stylesheet" type="text/css" href="_homepage.css" />
    
    <style type="text/css">
        /* Layout di base in stile Spotify */
        body {
            background-color: #121212;
            color: white;
            margin: 0;
            font-family: 'Circular Sp', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* 🟢 FORZA IL LINK "HOME" DELLA SIDEBAR IN INCLUSIONE A DIVENTARE VERDE 🟢 */
        #menu-home, 
        .sidebar a[href="homepage.php"], 
        #sidebar a[href="homepage.php"],
        #nav a[href="homepage.php"],
        ul li a[href="homepage.php"] {
            color: #1db954 !important;
            font-weight: bold !important;
        }

        /* EFFETTO HOVER IN STILE SPOTIFY (FRECCIA STANDARD, NO MANINA) */
        .shortcut-card, .spotify-card {
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease !important;
            cursor: default !important; 
            user-select: none;
        }

        .shortcut-card:hover, .spotify-card:hover {
            background-color: #282828 !important; 
            transform: translateY(-6px);          
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6) !important;
        }

        /* Griglie responsive */
        .shortcuts-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 40px;
        }

        .main-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>

    <?php include 'menu.php'; ?>

    <div id="main" style="padding: 24px; padding-bottom: 80px; margin-left: 240px; background: linear-gradient(to bottom, #222222 0%, #121212 40%); min-height: 100vh; box-sizing: border-box;">
        
        <div style="margin-bottom: 24px;">
            <h1 style="color: white; font-size: 32px; font-weight: bold; letter-spacing: -1px; margin: 0;">
                <?php 
                $ora = intval(date('H'));
                if ($ora >= 5 && $ora < 12) echo "Buongiorno";
                elseif ($ora >= 12 && $ora < 18) echo "Buon pomeriggio";
                else echo "Buonasera";
                ?>, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Ospite'; ?>
            </h1>
        </div>

        <div class="shortcuts-grid">
            
            <div class="shortcut-card" style="background-color: rgba(255,255,255,0.1); width: calc(33.333% - 11px); min-width: 250px; height: 80px; border-radius: 4px; display: flex; align-items: center; overflow: hidden;">
                <img src="img/album.png" alt="Copertina" style="width: 80px; height: 80px; object-fit: cover;" />
                <span style="font-weight: bold; font-size: 16px; padding-left: 16px; color: white;">I tuoi brani preferiti</span>
            </div>

            <div class="shortcut-card" style="background-color: rgba(255,255,255,0.1); width: calc(33.333% - 11px); min-width: 250px; height: 80px; border-radius: 4px; display: flex; align-items: center; overflow: hidden;">
                <img src="img/marra.jpg" alt="Copertina" style="width: 80px; height: 80px; object-fit: cover;" />
                <span style="font-weight: bold; font-size: 16px; padding-left: 16px; color: white;">Mix Marracash</span>
            </div>

            <div class="shortcut-card" style="background-color: rgba(255,255,255,0.1); width: calc(33.333% - 11px); min-width: 250px; height: 80px; border-radius: 4px; display: flex; align-items: center; overflow: hidden;">
                <img src="img/gue.jpg" alt="Copertina" style="width: 80px; height: 80px; object-fit: cover;" />
                <span style="font-weight: bold; font-size: 16px; padding-left: 16px; color: white;">Guè Pequeno Classic</span>
            </div>

            <div class="shortcut-card" style="background-color: rgba(255,255,255,0.1); width: calc(33.333% - 11px); min-width: 250px; height: 80px; border-radius: 4px; display: flex; align-items: center; overflow: hidden;">
                <img src="img/lazza.jpg" alt="Copertina" style="width: 80px; height: 80px; object-fit: cover;" />
                <span style="font-weight: bold; font-size: 16px; padding-left: 16px; color: white;">Sirio - Lazza</span>
            </div>

            <div class="shortcut-card" style="background-color: rgba(255,255,255,0.1); width: calc(33.333% - 11px); min-width: 250px; height: 80px; border-radius: 4px; display: flex; align-items: center; overflow: hidden;">
                <img src="img/famoso.png" alt="Copertina" style="width: 80px; height: 80px; object-fit: cover;" />
                <span style="font-weight: bold; font-size: 16px; padding-left: 16px; color: white;">Sfera Ebbasta Mix</span>
            </div>

            <div class="shortcut-card" style="background-color: rgba(255,255,255,0.1); width: calc(33.333% - 11px); min-width: 250px; height: 80px; border-radius: 4px; display: flex; align-items: center; overflow: hidden;">
                <img src="img/geolier.jpg" alt="Copertina" style="width: 80px; height: 80px; object-fit: cover;" />
                <span style="font-weight: bold; font-size: 16px; padding-left: 16px; color: white;">Geolier Collection</span>
            </div>

        </div>

        <div style="margin-bottom: 16px;">
            <h2 style="color: white; font-size: 24px; font-weight: bold; letter-spacing: -0.5px; margin: 0;">Ascoltati di recente</h2>
        </div>

        <div class="main-grid">
            
            <div class="spotify-card" style="background-color: #181818; padding: 16px; border-radius: 8px; width: 180px; box-sizing: border-box;">
                <img src="img/Dove_volano_le_aquile.png" alt="Copertina" style="width: 148px; height: 148px; object-fit: cover; border-radius: 6px; box-shadow: 0 8px 24px rgba(0,0,0,.5); margin-bottom: 16px; display: block;" />
                <h3 style="color: white; font-size: 14px; margin: 0 0 8px 0; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Dove volano le aquile</h3>
                <p style="color: #b3b3b3; font-size: 12px; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">Album • Luchè</p>
            </div>

            <div class="spotify-card" style="background-color: #181818; padding: 16px; border-radius: 8px; width: 180px; box-sizing: border-box;">
                <img src="img/fini.jpg" alt="Copertina" style="width: 148px; height: 148px; object-fit: cover; border-radius: 6px; box-shadow: 0 8px 24px rgba(0,0,0,.5); margin-bottom: 16px; display: block;" />
                <h3 style="color: white; font-size: 14px; margin: 0 0 8px 0; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Mr. Fini</h3>
                <p style="color: #b3b3b3; font-size: 12px; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">Album • Guè</p>
            </div>

            <div class="spotify-card" style="background-color: #181818; padding: 16px; border-radius: 8px; width: 180px; box-sizing: border-box;">
                <img src="img/noi_loro.jpg" alt="Copertina" style="width: 148px; height: 148px; object-fit: cover; border-radius: 6px; box-shadow: 0 8px 24px rgba(0,0,0,.5); margin-bottom: 16px; display: block;" />
                <h3 style="color: white; font-size: 14px; margin: 0 0 8px 0; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Noi, Loro, Gli Altri</h3>
                <p style="color: #b3b3b3; font-size: 12px; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">Album • Marracash</p>
            </div>

            <div class="spotify-card" style="background-color: #181818; padding: 16px; border-radius: 8px; width: 180px; box-sizing: border-box;">
                <img src="img/re_mida.png" alt="Copertina" style="width: 148px; height: 148px; object-fit: cover; border-radius: 6px; box-shadow: 0 8px 24px rgba(0,0,0,.5); margin-bottom: 16px; display: block;" />
                <h3 style="color: white; font-size: 14px; margin: 0 0 8px 0; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Re Mida</h3>
                <p style="color: #b3b3b3; font-size: 12px; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">Album • Lazza</p>
            </div>

        </div>

        <div style="margin-bottom: 16px;">
            <h2 style="color: white; font-size: 24px; font-weight: bold; letter-spacing: -0.5px; margin: 0;">Creato per te</h2>
        </div>

        <div class="main-grid">
            
            <div class="spotify-card" style="background-color: #181818; padding: 16px; border-radius: 8px; width: 180px; box-sizing: border-box;">
                <img src="img/album.png" alt="Copertina" style="width: 148px; height: 148px; object-fit: cover; border-radius: 6px; box-shadow: 0 8px 24px rgba(0,0,0,.5); margin-bottom: 16px; display: block;" />
                <h3 style="color: white; font-size: 14px; margin: 0 0 8px 0; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Daily Mix 1</h3>
                <p style="color: #b3b3b3; font-size: 12px; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">Luchè, Guè, Marracash e altri artisti simili.</p>
            </div>

            <div class="spotify-card" style="background-color: #181818; padding: 16px; border-radius: 8px; width: 180px; box-sizing: border-box;">
                <img src="img/calmo.png" alt="Copertina" style="width: 148px; height: 148px; object-fit: cover; border-radius: 6px; box-shadow: 0 8px 24px rgba(0,0,0,.5); margin-bottom: 16px; display: block;" />
                <h3 style="color: white; font-size: 14px; margin: 0 0 8px 0; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Daily Mix 2</h3>
                <p style="color: #b3b3b3; font-size: 12px; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">Lazza, Sfera Ebbasta e nuove uscite trap.</p>
            </div>

            <div class="spotify-card" style="background-color: #181818; padding: 16px; border-radius: 8px; width: 180px; box-sizing: border-box;">
                <img src="img/non_abbiamo_eta.png" alt="Copertina" style="width: 148px; height: 148px; object-fit: cover; border-radius: 6px; box-shadow: 0 8px 24px rgba(0,0,0,.5); margin-bottom: 16px; display: block;" />
                <h3 style="color: white; font-size: 14px; margin: 0 0 8px 0; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Radar Novità</h3>
                <p style="color: #b3b3b3; font-size: 12px; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">I nuovi singoli dei cantanti che segui, aggiornati ogni venerdì.</p>
            </div>

            <div class="spotify-card" style="background-color: #181818; padding: 16px; border-radius: 8px; width: 180px; box-sizing: border-box;">
                <img src="img/botox.png" alt="Copertina" style="width: 148px; height: 148px; object-fit: cover; border-radius: 6px; box-shadow: 0 8px 24px rgba(0,0,0,.5); margin-bottom: 16px; display: block;" />
                <h3 style="color: white; font-size: 14px; margin: 0 0 8px 0; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Mix Anni 2010</h3>
                <p style="color: #b3b3b3; font-size: 12px; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">I grandi classici memorabili del rap italiano che hanno fatto la storia.</p>
            </div>

        </div>

    </div>

</body>
</html>