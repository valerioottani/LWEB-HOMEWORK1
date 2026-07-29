<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<div id="sidebar">
    <div class="logo">
        <a href="homepage.php">
            <img src="img/spotify_newLogo.png" alt="Logo Progetto" />
        </a>
    </div>
  
    <ul>
        <li><a href="homepage.php">Home</a></li>
        <li><a href="discografia.php">Discografia</a></li>
        <li><a href="eventi.php">Eventi</a></li>
        <li><a href="artisti.php">Altri Artisti</a></li>
        
        <?php if (isset($_SESSION['loggato']) && $_SESSION['loggato'] === true): ?>
            <li style="margin-top: 20px;">
                <span style="color: #1db954; font-size: 12px; padding: 0 15px; font-weight: bold;">
                    Ciao, <?php echo $_SESSION['username']; ?> 
                    <?php echo ($_SESSION['admin'] ? '(Admin)' : '(User)'); ?>
                </span>
            </li>
            <li><a href="logout.php" style="color: #e11d48;">Logout</a></li>
        <?php else: ?>
            <li style="margin-top: 20px;"><a href="login.php">Accedi</a></li>
        <?php endif; ?>
    </ul>
</div>