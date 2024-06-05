<?php
    session_start();

    // Contorolla se i dati sono stati scritti
    if (isset($_POST['message_id']) && isset($_POST['reply_message'])) {
        // Recupera i dati inviati dalla richiesta ajax
        $messageId = $_POST['message_id'];
        $replyMessage = $_POST['reply_message'];

        // Connessione al database
        $conn_string = "host=localhost port=5432 dbname=WikiSapienza user=postgres password=WikiSapienza";
        $conn = @pg_connect($conn_string); // La @ è necessaria per sopprimere i warning

        // Verifica della connessione
        if (!$conn) {
            echo json_encode(['error' => "Errore nella connessione al database"]);
            exit();
        }

        // Esegui l'inserimento della risposta nel database
        $query = "INSERT INTO forum (utente, messaggio, materia, mipiace, risposta_a) VALUES ($1, $2, $3, $4, $5)";
        $result = pg_query_params($conn, $query, array($_SESSION['nome'] . " " . $_SESSION['cognome'], $replyMessage, $_SESSION['materia'], 0, $messageId));

        if ($result) {
            echo json_encode(['success' => "Risposta aggiunta con successo"]);
        } else {
            echo json_encode(['error' => "Errore durante l'aggiunta della risposta."]);
        }

        // Chiude la connessione al database
        pg_close($conn);
    } else {
        echo json_encode(['error' => "Dati mancanti."]);
    }
?>
