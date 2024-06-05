<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verifica se sono stati ricevuti i dati del messaggio
        if (isset($_POST['message_id'])) {
            $messageId = $_POST['message_id'];
            $userEmail = $_SESSION['email'];

            $conn_string = "host=localhost port=5432 dbname=WikiSapienza user=postgres password=WikiSapienza";
            $conn = pg_connect($conn_string);

            // Verifica la connessione
            if ($conn) {
                // Recupera l'attuale lista_mipiace
                $query = "SELECT lista_mipiace, mipiace FROM forum WHERE id = $1";
                $result = pg_query_params($conn, $query, array($messageId));
                $row = pg_fetch_assoc($result);
                $currentLikes = json_decode($row['lista_mipiace']);
                $likeCount = $row['mipiace'];

                if ($currentLikes === null) {
                    $currentLikes = [];
                }

                // Determina se l'utente ha già messo "mi piace"
                if (in_array($userEmail, $currentLikes)) {
                    // Rimuovi l'utente dalla lista
                    $currentLikes = array_diff($currentLikes, [$userEmail]);
                    $likeCount--;  // Diminuisci il conteggio dei "mi piace"
                } else {
                    // Aggiungi l'utente alla lista
                    $currentLikes[] = $userEmail;
                    $likeCount++;  // Aumenta il conteggio dei "mi piace"
                }

                // Aggiorna il database con la nuova lista_mipiace e il nuovo conteggio dei Mi Piace
                $updateQuery = "UPDATE forum SET mipiace = $1, lista_mipiace = $2 WHERE id = $3";
                $updateResult = pg_query_params($conn, $updateQuery, array($likeCount, json_encode(array_values($currentLikes)), $messageId));

                if ($updateResult) {
                    // Invia il nuovo conteggio dei Mi Piace
                    echo json_encode(['success' => true, 'likes' => $likeCount]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Errore durante l\'aggiornamento dei Mi Piace nel database']);
                }

                pg_close($conn);
            } else {
                echo json_encode(['success' => false, 'error' => 'Connessione al database non riuscita']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Dati del messaggio non ricevuti']);
        }
    }
?>
