<?php
    // Script per reindirizzare l'utente al forum relativo alla materia cliccata (fa da ponte)
    session_start();
    $_SESSION['materia'] = $_GET['materia'];
    header('Location: forum.php');
    exit;
?>