<?php
    // Riprende la sessione
    session_start();

    // Termina la sessione corrente e la distrugge
    session_unset();
    session_destroy();

    // Reindirizza l'utente alla home_page
    header("Location: home_page.html");
    exit;
?>