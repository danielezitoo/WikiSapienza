<!-- Abbiamo differenziato la logica del login da quella delle registrazioni utilizzando AJAX+JSON per il login
     (per la comunicazione tra client e server) e usando solo PHP sullo stesso file per i sign_up -->
<?php
    session_start();

    $conn_string = "host=localhost port=5432 dbname=WikiSapienza user=postgres password=WikiSapienza";
    $conn = @pg_connect($conn_string); //la @ serve per sopprimere i warning

    // Verifica della connessione
    if (!$conn) {
        echo '<script> alert("Errore nella connessione al database"); </script>';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recupera i dati inviati dalla form
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $cognome = isset($_POST['cognome']) ? $_POST['cognome'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $tipo="ospite";

        // Query per verificare se l'email esiste già nel database
        $check_query = "SELECT email FROM utenti WHERE email = $1";
        $check_result = pg_query_params($conn, $check_query, array($email));

        if (pg_num_rows($check_result) > 0) {
            // Se l'email esiste già, imposta la variabile JavaScript per mostrare il messaggio di errore
            echo '<script>';
            echo 'var showMessageBox = true;';
            echo '</script>';
        } else {
            // Se l'email non esiste, esegui l'inserimento dei dati
            // Query per l'inserimento dei dati con parametri
            $query = "INSERT INTO utenti (nome, cognome, email, password_ut, tipo) VALUES ($1, $2, $3, $4, $5)";
            $result = pg_query_params($conn, $query, array($nome, $cognome, $email, $password, $tipo));

            if ($result) {
                // Se l'inserimento è riuscito, inizializza variabili della sessione e reindirizza l'utente alla pagina principale
                $_SESSION['tipo'] = $tipo;
                $_SESSION['nome'] = $nome;
                $_SESSION['cognome'] = $cognome;
                $_SESSION['email'] = $email;
                header("Location: main_page.html");
                exit;
            } else {
                echo '<script> alert("Errore nell\'inserimento dei dati"); </script>';
            }
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
    <title>Sign Up</title>
    <link href="./dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./my_css/sign_up.css">
    <link rel="icon" href="./immagini/LogoFinale.png" type="image/x-icon">
    <link rel="shortcut icon" href="./immagini/LogoFinale.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Carta che contiene la form con un suo header -->
                <div class="card mt-5">
                    <div class="card-header">
                        <h3 class="text-center">Registrati come ospite</h3>
                    </div>
                    <div class="card-body color_card">
                        <!-- form di sign_up -->
                        <form name="loginForm" action="sign_up_ospite.php" method="POST" onsubmit="return validateForm()">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input type="text" name="nome" class="form-control" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="cognome">Cognome</label>
                                <input type="text" name="cognome" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Conferma Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                            </div>
                            <br>
                            <div class="container">
                                <div class="col-md-4 mx-auto d-flex justify-content-center">
                                    <p>
                                        <button type="submit" class="btn btn-red btn-block color_bar border_button">Registrati</button>
                                    </p>
                                </div>
                            </div>
                            <br>
                            <!-- Notifica nel caso si voglia essere renderizzati ai sign_up -->
                            <div class="alert alert-danger text-center" style="font-size: 13px">
                                Sei già registrato? Fai il <a class="alert-link" href="login.php">login</a>!
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- div per errore in caso di email già presente nel database -->
    <div id="messageBox" class="text-center">
        <p>
            Attenzione! Email già utilizzata, forse volevi fare il <a class="color_link" href="login.php">login</a>?
        </p>
    </div>

    <script>
        // Funzione per mostrare il messaggio di avvertimento
        function showMessage() {
            document.getElementById('messageBox').style.display = 'block';
            setTimeout(hideMessage, 3500);
        }

        // Funzione per nascondere il messaggio di avvertimento
        function hideMessage() {
            document.getElementById('messageBox').style.display = 'none';
        }

        // Mostra o nasconde il messaggio di avvertimento in base alla variabile JavaScript (impostata da php)
        if (showMessageBox) {
            showMessage();
        } else {
            hideMessage();
        }
    </script>  

    <!-- div per errore in caso di email istituzionale valida -->
    <div id="messageBox2" class="text-center">
        <p>
            Attenzione! Sembra tu stia usando e-mail istituzionale valida. Registrati come studente <a class="color_link" href="sign_up.php">QUI</a>
        </p>
    </div>

    <script>
        // Funzione per validare la form
        function validateForm() {
            // Recupero dei valori dai campi del modulo
            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("confirm_password").value;
            var email = document.getElementById("email").value;

            // Espressione regolare per validare l'email
            var email_regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Espressioni regolari per controllare il dominio dell'email
            const email_regex_ctrl1 = /^[a-z]+\.[0-9]+@studenti\.uniroma1\.it$/; 
            const email_regex_ctrl2 = /^[a-z]+\.[a-z]@uniroma1\.it$/;

            // Controllo se le password corrispondono
            if (password != confirm_password) {
                alert("Le password non corrispondono.");
                return false;
            }

            // Controllo se l'email è valida
            if (!email_regex.test(email)) {
                alert("Email non valida");
                return false;
            }

            // Controllo se l'email è valida per l'altra sign_up
            if (email_regex.test(email) && (email_regex_ctrl1.test(email) || email_regex_ctrl2.test(email))) {
                // Visualizzazione di un messaggio di errore
                document.getElementById('messageBox2').style.display = 'block';
                setTimeout(hideMessage, 3500);
                function hideMessage() {
                    document.getElementById('messageBox2').style.display = 'none';
                }
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
