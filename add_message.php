<?php
    session_start();

    // Verifica se i dati del nuovo messaggio sono stati scritti
    if (isset($_POST['utente']) && isset($_POST['messaggio']) && isset($_POST['materia']) && isset($_POST['mipiace'])) {
        // Recupera i dati del nuovo messaggio dalla richiesta ajax
        $utente = $_POST['utente'];
        $messaggio = $_POST['messaggio'];
        $materia = $_POST['materia'];
        $mipiace = $_POST['mipiace'];

        // Connessione al database
        $conn_string = "host=localhost port=5432 dbname=WikiSapienza user=postgres password=WikiSapienza";
        $conn = @pg_connect($conn_string); // La @ è necessaria per sopprimere i warning

        // Verifica della connessione
        if (!$conn) {
            echo json_encode(['error' => "Errore nella connessione al database"]);
            exit;
        }

        // Query per l'inserimento del nuovo messaggio
        $query = "INSERT INTO forum (utente, messaggio, materia, mipiace) VALUES ($1, $2, $3, $4)";
        $result = pg_query_params($conn, $query, array($utente, $messaggio, $materia, $mipiace));

        if ($result) {
            echo json_encode(['success' => "Messaggio aggiunto con successo"]);
        } else {
            echo json_encode(['error' => "Errore durante l'aggiunta del messaggio."]);
        }

        pg_close($conn);
    } else {
        echo json_encode(['error' => "Dati mancanti."]);
    }
?>
