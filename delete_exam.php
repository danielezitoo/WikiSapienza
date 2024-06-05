<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn_string = "host=localhost port=5432 dbname=WikiSapienza user=postgres password=WikiSapienza";
        $conn = @pg_connect($conn_string); // La @ è necessaria per sopprimere i warning

        // Controllo la connessione
        if (!$conn) {
            echo json_encode(['success' => false, 'message' => 'Errore nella connessione al database']);
            exit;
        }

        // Recupero l'email dell'utente dalla sessione
        $email = $_SESSION["email"];
        
        // Recupero il nome dell'esame dall'array POST
        $esame = $_POST['name'];

        // Query per eliminare l'esame dal database
        $query = "DELETE FROM esami WHERE email = $1 AND esame = $2";
        $result = pg_query_params($conn, $query, array($email, $esame));

        if (!$result) {
            echo json_encode(['success' => false, 'message' => 'Errore nell\'eliminazione dell\'esame']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Esame eliminato con successo']);
        }

        pg_close($conn);
    } else {
        echo json_encode(['success' => false, 'message' => 'Richiesta non valida']);
    }
?>
