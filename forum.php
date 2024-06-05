<?php
    session_start();

    // Connessione al database
    $conn_string = "host=localhost port=5432 dbname=WikiSapienza user=postgres password=WikiSapienza";
    $conn = @pg_connect($conn_string);
    if (!$conn) {
        echo '<script> alert("Errore di connessione al database principale"); </script>';
    }

    $materia = $_SESSION['materia'];
    // Query per selezionare i messaggi e le relative risposte
    $query = "SELECT id, utente, messaggio, mipiace FROM forum WHERE materia = $1 AND risposta_a IS NULL ORDER BY mipiace DESC";
    $result = pg_query_params($conn, $query, array($materia));
    if (!$result) {
        echo '<script> alert("Errore nell\'esecuzione della query sui messaggi"); </script>';
    }

    // Connessione al database
    $conn2_string = "host=localhost port=5432 dbname=WikiSapienza user=postgres password=WikiSapienza";
    $conn2 = @pg_connect($conn2_string);
    if (!$conn2) {
        echo '<script> alert("Errore di connessione al secondo database"); </script>';
    }

    // Query per selezionare i dati della materia
    $query2 = "SELECT descrizione, professore, email, dipartimento FROM subject WHERE materia = $1";
    $result2 = pg_query_params($conn2, $query2, array($materia));
    if (!$result2) {
        echo '<script> alert("Errore nell\'esecuzione della query sulle informazioni della materia"); </script>';
    }

    $row = pg_fetch_assoc($result2);
    $descrizione = $row['descrizione'];
    $professore = $row['professore'];
    $email = $row['email'];
    $dipartimento = $row['dipartimento'];

    // Funzione ricorsiva per stampare le risposte di un messaggio (chiamata dopo)
    function stampaRisposte($conn, $parentMessageId, $indentation) {
        // Query per selezionare le risposte
        $query = "SELECT id, utente, messaggio, mipiace FROM forum WHERE risposta_a = $1 ORDER BY mipiace DESC";
        $result = pg_query_params($conn, $query, array($parentMessageId));

        if (!$result) {
            echo '<script> Errore nell\'esecuzione della query sulle risposte. </script>';
            return;
        }

        // Evita che l'indentazione superi un limite prestabilito (100px)
        if ($indentation >= 100) {
            $indentation = 100;
        }
        
        while ($row = pg_fetch_assoc($result)) {
            echo "<div class='list-group-item' style='opacity:0.8; margin-left: {$indentation}px; margin-top: 5px;'>";
            echo "<h5 class='mb-1'><img src='./immagini/risposta.png' class='mr-2' width='25' height='25' style='margin-right:5px; margin-bottom:6px;'>" . $row['utente'] . " </h5>";
            echo "<br>";
            echo "<p class='mb-1' style='font-size: 1.1em;'>" . $row['messaggio'] . "</p>";
            echo "<br>";
            echo "<small>Mi Piace: <span id='likes_" . $row['id'] . "'>" . $row['mipiace'] . "</span></small>";
            echo "<button class='btn btn-sm like-btn' style='margin-left: 5px;' data-message-id='" . $row['id'] . "'><img src='./immagini/like.png' alt='Mi Piace' style='width: 50px; height: 30px;'></button>";
            if ($_SESSION['tipo'] !== "ospite") {
                echo "<button class='btn btn-sm reply-btn' style='margin-left: 5px;' data-message-id='" . $row['id'] . "'>Rispondi</button>";
            }
            echo "</div>";

            // Area di testo per rispondere al messaggio
            echo "<div id='reply-area-" . $row['id'] . "' style='display: none; margin-left: {$indentation}px; margin-top: 5px;'>";
            echo "<textarea id='reply-text-" . $row['id'] . "' class='form-control mb-2' rows='3' placeholder='Rispondi al messaggio...' style='height: 100px;'></textarea>";
            echo "<button style='margin-bottom: 15px;' class='btn margin_button btn-red send-reply-btn' data-message-id='" . $row['id'] . "'>Invia risposta</button>";
            echo "</div>";
            
            // Stampa le risposte del messaggio di risposta (chiamata ricorsiva)
            stampaRisposte($conn, $row['id'], $indentation + 20);
        } 
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./dist/css/bootstrap.css" rel="stylesheet">
        <title>Forum</title>
        <link rel="icon" href="./immagini/LogoFinale.png" type="image/x-icon">
        <link rel="shortcut icon" href="./immagini/LogoFinale.png" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="./my_css/forum.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- Libreria (toastr) per notifiche varie -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <!-- Script per gestione pulsante per mostrare link nella navbar nella versione da telefono -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <body>
        <!-- Div per sfondo -->
        <div class="background-overlay"></div>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark color_bar">
            <a class="navbar-brand" href="./main_page.html">
            <img src="../immagini/LogoFinale.png" alt="Logo" class="mr-2 mrg_logo" width="25" height="25">WikiSapienza
        </a>

        <!-- Pulsante che contiene i link nella navbar da telefono -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto" style="margin-left: 10px;">
                <li class="nav-item">
                    <a class="nav-link" href="my_area.php">My Area</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about_us.html">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://github.com/danielezitoo/WikiSapienza">Source Code</a>
                </li>
            </ul>
        </div>
    </nav>
     <div class="container mt-5">
        <!-- Div per descrizione materia  -->
        <div class="container">
            <div class="col">
                <h1 class="mb-4"><?php echo $materia?></h1>
            </div> 
            <div class="row">
            <div class="col-md-9" style="font-weight: 500; font-family: 'Georgia'; font-size: 17px;">
                <p>
                    <?php echo $descrizione ?><br>
                </p>
            </div>
            <div class="col-md-3 text-center">
                <img src="./immagini/utente.png" class="img-fluid mb-3" style="width: 110px; height:110px;">
                <div  style="font-weight: 500; font-family: 'Georgia'; font-size: 16px;">
                    <p>Professore: <?php echo $professore ?></p>
                    <p>Email: <a href="mailto: <?php $email ?>" style="color: #ad1515e0;"> <?php echo $email ?> </a></p>
                    <p>Dipartimento: <?php echo $dipartimento ?></p>
                    <br>
                </div>
            </div>          
            <div class="row">
                <div class="col">
                    <h2 class="mb-4">Forum di <?php echo $materia?></h2>
                </div>
            <div class="col-auto">
                <?php if ($_SESSION['tipo'] !== "ospite"): ?>
                    <button id="new-message-btn" class="btn" onclick="toggleMedia()"><img src="./immagini/messaggio.png" alt="Nuovo Messaggio" style="width: 50px; height: 50px;"></button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Area di inserimento di un nuovo messaggio (inizialmente nascosta) -->
        <div id="new-message-area" style="display: none;">
            <textarea id="new-message-text" class="form-control mb-2" rows="3" placeholder="Inserisci il tuo messaggio..." style="height: 180px;"></textarea>
            <button id="send-message-btn" class="btn margin_button btn-red">Invia</button>
        </div>
        
        <br>

        <!-- Stampa lista dei messaggi senza risposte (stampate dopo) -->
        <div class="list-group">
            <?php 
                // Se forum vuoto stampa messaggio "Non c'è nessuno qui"
                if (pg_num_rows($result) == 0) {
                    echo "<br>";
                    echo '<h3 class="text-center" style="display: inline-block; background-color: white; color: black; padding: 10px; border: 1px solid black; border-radius: 10px; opacity: 0.75">';
                    echo 'Sembra non ci sia nessuno qui, inizia tu la conversazione!';
                    echo '</h3>';
                }
                
                // Stampa i messaggi e le relative risposte (le quali hanno la stessa formattazione dei messaggi, solo indentati maggiormente)
                while ($row = pg_fetch_assoc($result)) {
                    echo "<div class='list-group-item'>";
                    // Utente
                    echo "<h5 class='mb-1'>" . $row['utente'] . "</h5>";
                    echo "<br>";
                    // Messaggio
                    echo "<p class='mb-1'>" . $row['messaggio'] . "</p>";
                    echo "<br>";
                    // MiPiace con id pari all'id del messaggio nel database e numero di like nel database
                    echo "<small>Mi Piace: <span id='likes_" . $row['id'] . "'>" . $row['mipiace'] . "</span></small>";
                    echo "<button class='btn btn-sm like-btn' style='margin-left: 5px;' data-message-id='" . $row['id'] . "'><img src='./immagini/like.png' alt='Mi Piace' style='width: 50px; height: 30px;'></button>";
                    // Pulsante di risposta disponibile solo se non si è ospiti
                    if ($_SESSION['tipo']!=="ospite") {
                        echo "<button class='btn btn-sm reply-btn' style='margin-left: 5px;' data-message-id='" . $row['id'] . "'>Rispondi</button>";
                    }
                    echo "</div>";  
                    
                    // Area di testo per rispondere al messaggio (con suo id per aprire la text area subito sotto al messaggio a cui si vuole rispondere)
                    echo "<div id='reply-area-" . $row['id'] . "' style='display: none; margin-top: 5px;'>";
                    echo "<textarea id='reply-text-" . $row['id'] . "' class='form-control mb-2' rows='3' placeholder='Rispondi al messaggio...' style='height: 120px;'></textarea>";
                    echo "<button style='margin-bottom: 15px;' class='btn margin_button btn-red send-reply-btn' data-message-id='" . $row['id'] . "'>Invia risposta</button>";
                    echo "</div>";     
                    
                    // Stampa le risposte del messaggio
                    stampaRisposte($conn, $row['id'], 20);
                    
                    echo "<br>";
                } 
            ?>
        </div>

    </div>
    
    <script>
        function toggleMedia() {
            const mediaDiv = $("#new-message-area");
                
            if (mediaDiv.is(":hidden")) {
                    // Il div è nascosto, quindi lo facciamo scorrere verso il basso per mostrarlo
                mediaDiv.slideDown(500);
            } else {
                    // Il div è visibile, quindi lo facciamo scorrere verso l'alto per nasconderlo
                mediaDiv.slideUp(500);
            }
        }
    </script>
    
    <script>
        $(document).ready(function() {
            // Gestione del click sul pulsante "Mi piace"
            $(".like-btn").click(function() {
                var messageId = $(this).data("message-id");

                // Invia una richiesta AJAX per aggiornare i like nel database
                $.ajax({
                    type: "POST",
                    url: "update_likes.php",
                    data: { 
                        message_id: messageId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            // Aggiorna il conteggio dei like nel documento
                            $("#likes_" + messageId).text(response.likes);
                        } else {
                            alert(response.error);
                        }
                    }
                });
            });

            // Gestione del click sul pulsante per aggiungere un nuovo messaggio
            $("#new-message-btn").click(function() {
                // Mostra l'area per inserire un nuovo messaggio
                $("#new-message-area").show();
            });

            // Gestione del click sul pulsante per inviare un nuovo messaggio
            $("#send-message-btn").click(function() {
                // Testo del nuovo messaggio
                var newMessage = $("#new-message-text").val();

                // Controlla se il messaggio non è vuoto
                if (newMessage.trim() !== "") {
                    // Prende i dati del nuovo messaggio
                    var data = {
                        utente: "<?php echo $_SESSION['nome'] . ' ' . $_SESSION['cognome']; ?>",
                        messaggio: newMessage,
                        materia: "<?php echo $_SESSION['materia']; ?>",
                        mipiace: 0
                    };

                    // Invia una richiesta AJAX per aggiungere il nuovo messaggio al database
                    $.ajax({
                        type: "POST",
                        url: "add_message.php",
                        dataType: "json",
                        data: data,
                        success: function(response) {
                            if (response.success) {
                                // Se l'aggiunta del messaggio è riuscita, ricarica la pagina per visualizzare il messaggio inserito
                                location.reload();
                            } else {
                                // Se si verifica un errore, mostra il messaggio di errore
                                alert(response.error);
                            }
                        },
                    });
                } else {
                    // Se il messaggio è vuoto, mostra un messaggio di avviso
                    toastr.error("Il messaggio non può essere vuoto.");
                }
            });

        });

        // Gestione del click sul pulsante "Rispondi"
        $(".reply-btn").click(function() {
            var messageId = $(this).data("message-id");
            
            // Mostra l'area di testo per rispondere
            $("#reply-area-" + messageId).slideToggle(500);
        });
        
        // Gestione del click sul pulsante per inviare una risposta
        $(".send-reply-btn").click(function() {
            var messageId = $(this).data("message-id");
            var replyMessage = $("#reply-text-" + messageId).val();
            // Controlla se la risposta non è vuota
            if (replyMessage.trim() !== "") {
                // Invia la risposta al server
                $.ajax({
                    type: "POST",
                    url: "add_reply.php",
                    dataType: "json",
                    data: { message_id: messageId, reply_message: replyMessage },
                    success: function(response) {
                        if (response.success) {
                            // Se l'aggiunta della risposta è riuscita, ricarica la pagina per visualizzare la risposta inserita
                            location.reload();
                        } else {
                            // Se si verifica un errore, mostra il messaggio di errore
                            alert(response.error);
                        }
                    },
                });
            } else {
                // Se la risposta è vuota, mostra un messaggio di avviso
                toastr.error("Il messaggio non può essere vuoto.");
            }
        });
    </script>
</body>
</html>