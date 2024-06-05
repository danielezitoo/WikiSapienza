<?php
    session_start();

    $conn_string = "host=localhost port=5432 dbname=WikiSapienza user=postgres password=WikiSapienza";
    $conn = @pg_connect($conn_string); //la @ è necessaria per sopprimere i warning

    // Verifica della connessione
    if (!$conn) {
        echo '<script> alert("Errore nella connessione al database"); </script>';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Prendi i valori inseriti nel form
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        // Query per verificare se le credenziali sono corrette
        $query = "SELECT * FROM utenti WHERE email=$1 AND password_ut=$2";
        $result = pg_query_params($conn, $query, array($email, $password));

        if ($result) {
            // Verifica se ci sono righe restituite
            if (pg_num_rows($result) > 0) {
                // Login valido
                $row = pg_fetch_assoc($result);

                // Inizializza variabili della sessione
                $_SESSION['tipo'] = ($row['tipo'] == 'studente') ? "studente" : "ospite";
                $_SESSION['nome'] = $row['nome'];
                $_SESSION['cognome'] = $row['cognome'];
                $_SESSION['email'] = $row['email'];
                
                $response = array(
                    'success' => true,
                    'redirect' => 'main_page.html'
                );
                echo json_encode($response);
                exit;
            } else {
                // Nessun utente trovato con le credenziali fornite
                $response = array(
                    'error' => 'Email o password errate'
                );
                echo json_encode($response);
                exit;
            }
        } else {
            echo '<script> alert("Errore nella query"); </script>';
        }
    }

    if ($conn) {
        pg_close($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="./dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./my_css/login.css">
    <link rel="icon" href="./immagini/LogoFinale.png" type="image/x-icon">
    <link rel="shortcut icon" href="./immagini/LogoFinale.png" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Carta che contiene la form con un suo header -->
                <div class="card">
                    <div class="card-header">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body color_card">
                        <!-- form di login -->
                        <form id="loginForm" action="login.php" method="post">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" class="form-control" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <br>
                            <div class="container">
                                <div class="col-md-4 mx-auto d-flex justify-content-center">
                                    <button type="submit" class="btn btn-red">Accedi</button>
                                </div>
                            </div>
                        </form>
                        <br> 
                        <!-- Notifica nel caso si voglia essere renderizzati ai sign_up -->
                        <div class="alert alert-danger text-center" style="font-size: 13px">
                            Non sei ancora registrato? Registrati come <a class="alert-link" href="sign_up_ospite.php">ospite</a> o come <a class="alert-link" href="sign_up.php">studente</a>.
                        </div>
                        <!-- div per errore in caso di credenziali errate -->
                        <div id="messageBox" class="text-center" style="display: none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#loginForm').submit(function(event) {
                // Blocca il comportamento predefinito di invio del modulo in caso di errore  
                event.preventDefault(); 
                // Esegue una richiesta AJAX per inviare i dati del modulo al server
                $.ajax({               
                    url: 'login.php',
                    method: 'POST',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Renderizzo alla main_page se l'autenticazione ha avuto successo
                            window.location.href = response.redirect;
                        } else {
                            // Mostro errore se credenziali errate o altri errori
                            $('#messageBox').text(response.error).css('display', 'block');
                            setTimeout(function() {
                                $('#messageBox').css('display', 'none');
                            }, 2000); // Nasconde il div di errore dopo 2 secondi
                        }
                    },
                });
            });
        });
    </script>
</body>
</html>