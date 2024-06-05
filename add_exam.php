<?php
    session_start();

    $conn_string = "host=localhost port=5432 dbname=WikiSapienza user=postgres password=WikiSapienza";
    $conn = @pg_connect($conn_string); // La @ è necessaria per sopprimere i warning

    // Controllo se la connessione è stata stabilita correttamente
    if (!$conn) {
        echo json_encode(array('success' => false, 'message' => 'Errore nella connessione al database'));
        exit;
    }

    // Recupero l'email dell'utente dalla sessione
    $email = $_SESSION["email"];
    
    // Recupero i dati dell'esame dalla richiesta ajax
    $esame = $_POST['name'];
    $voto = $_POST['voto'];
    $cfu = $_POST['cfu'];

    // Query per inserire i dati dell'esame nel database
    $query = "INSERT INTO esami (email, esame, voto, cfu) VALUES ($1, $2, $3, $4)";
    $result = pg_query_params($conn, $query, array($email, $esame, $voto, $cfu));

    if ($result) {
        echo json_encode(array('success' => true, 'message' => 'Esame aggiunto con successo'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Errore durante l\'aggiunta dell\'esame'));
    }

    pg_close($conn);
?>
