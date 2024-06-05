<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Ottieni i dati dalla richiesta ajax
        $oldPassword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];

        // Controlla se la vecchia password e la nuova password sono state inviate
        if (isset($oldPassword) && isset($newPassword)) {
            $conn_string = "host=localhost port=5432 dbname=WikiSapienza user=postgres password=WikiSapienza";
            $conn = @pg_connect($conn_string); //la @ è necessaria per sopprimere i warning

            // Verifica della connessione
            if (!$conn) {
                echo json_encode(["message" => "Errore nella connessione al database"]);
                exit;
            }

            // Prepara e esegue la query
            $email = $_SESSION['email'];
            $query = "SELECT password_ut FROM utenti WHERE email = $1";
            $result = pg_query_params($conn, $query, array($email));

            if ($result) {
                $row = pg_fetch_assoc($result);
                $storedPassword = $row["password_ut"];

                // Verifica se la vecchia password corrisponde
                if ($oldPassword === $storedPassword) {
                    // La vecchia password è corretta, aggiorna la password nel database
                    $query = "UPDATE utenti SET password_ut = $1 WHERE email = $2";
                    $result = pg_query_params($conn, $query, array($newPassword, $email));

                    if ($result) {
                        echo json_encode(['success' => "Password aggiornata con successo"]);                        
                    } else {
                        echo json_encode(['error' => "Errore durante l'aggiornamento della password."]);                       
                    }
                } else {
                    echo json_encode(['error' => "La vecchia password non è corretta."]);  
                }
            } else {
                echo json_encode(['error' => "Utente non trovato."]);                
            }
            if ($conn) {
                pg_close($conn);
            }
        } else {
            echo json_encode(['error' => "Dati mancanti."]);          
        }
    }
?>
