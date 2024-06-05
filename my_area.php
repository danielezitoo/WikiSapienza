<?php
    session_start();

    $conn_string = "host=localhost port=5432 dbname=WikiSapienza user=postgres password=WikiSapienza";
    $conn = @pg_connect($conn_string); // La @ è necessaria per sopprimere i warning

    // Verifica della connessione
    if (!$conn) {
        echo '<script> alert("Errore nella connessione al database"); </script>';
    }

    // Recupero dell'email dell'utente dalla sessione
    $email = $_SESSION["email"];

    // Esecuzione della query per recuperare gli esami dell'utente
    $query = "SELECT esame, voto, cfu FROM esami WHERE email = $1";
    $result = pg_query_params($conn, $query, array($email));

    if (!$result) {
        echo '<script> alert("Errore nella query"); </script>';
    }

    // Creazione di un array contenente gli esami dell'utente
    $exams = array();
    while ($row = pg_fetch_assoc($result)) {
        $exams[] = array(
            'name' => $row['esame'],
            'voto' => (int)$row['voto'],
            'cfu' => (int)$row['cfu'],
            'isSimulated' => false
        );
    }

    pg_close($conn);

    // Inserimento degli esami recuperati in una variabile JavaScript
    echo "<script>
        let exams = " . json_encode($exams) . ";
        let totalCfu = exams.reduce((acc, exam) => acc + exam.cfu, 0);
      </script>";
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.85">
    <title>My Area</title>
    <link href="./dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./my_css/my_area.css">
    <link rel="icon" href="./immagini/LogoFinale.png" type="image/x-icon">
    <link rel="shortcut icon" href="./immagini/LogoFinale.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Libreria (toastr) per notifiche varie -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Script per gestione pulsante per mostrare link nella navbar nella versione da telefono -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <!-- Div per lo sfondo -->
    <div class="background-overlay"></div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark color_bar">
        <a class="navbar-brand" href="./main_page.html">
            <img src="./immagini/LogoFinale.png" alt="Logo" class="mr-2 mrg_logo" width="25" height="25">WikiSapienza
        </a>
        
        <!-- Pulsante che contiene i link nella navbar da telefono -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto" style="margin-left: 10px;">
                <li class="nav-item">
                    <a class="nav-link" href="about_us.html">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://github.com/danielezitoo/WikiSapienza">Source Code</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-8">
                <div class="card mt-5">
                    <div class="card-header">
                        <h3 class="text-center">My Area</h3>
                    </div>
                    <div class="card-body color_card">
                        <br>
                        <!-- Div per tutte le info dell'utente e della sessione prese dalla sessione di php -->
                        <div class="user-info">
                            <img src="./immagini/utente.png" height="110px" width="110px">
                            <div>
                                <p>Nome: <?php echo $_SESSION['nome']; ?></p>
                                <p>Cognome: <?php echo $_SESSION['cognome']; ?></p>
                                <p>Email: <?php echo $_SESSION['email']; ?></p>
                                <p>Tipo sessione: <?php echo $_SESSION['tipo']; ?></p>
                            </div>
                        </div>
                        <br>
                        <!-- Div per 3 pulsanti -->
                        <div class="container">
                            <div class="mx-auto d-flex justify-content-center">
                                <div class="col-md-4 col-sm-12">
                                    <p class="text-center">
                                        <button type="button" class="btn margin_button btn-red" onclick="toggleMedia()">Media Esami</button>
                                    </p>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <p class="text-center">
                                        <button type="button" class="btn margin_button btn-red" onclick="toggleMedia2()">Cambia Password</button>
                                    </p>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <p class="text-center">
                                        <button type="button" class="btn margin_button btn-red" onclick="logout()">Logout</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Div per form per cambiare password -->
                    <div class="container color_card" id="changePasswordForm" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <form id="passwordForm" onsubmit="changePassword(); return false;">
                                    <div class="form-group">
                                        <label for="oldPassword" style="font-family: 'Times New Roman', Times, serif;">Vecchia Password:</label>
                                        <input type="password" class="form-control" id="oldPassword" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="newPassword" style="font-family: 'Times New Roman', Times, serif;">Nuova Password:</label>
                                        <input type="password" class="form-control" id="newPassword" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirmPassword" style="font-family: 'Times New Roman', Times, serif;">Conferma Nuova Password:</label>
                                        <input type="password" class="form-control" id="confirmPassword" required>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-red">Cambia Password</button>
                                </form>
                                <br>
                            </div>
                        </div>
                    </div>
                    <!-- Div per form per inserire esami, graficarli e visualizzare la media -->
                    <div class="container color_card" id="div_media" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Calcola la tua media!</h3>
                                <form id="examForm">
                                    <div class="form-group">
                                        <label for="name" style="font-family: 'Times New Roman', Times, serif;">Nome Esame:</label>
                                        <input type="text" class="form-control" id="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="voto" style="font-family: 'Times New Roman', Times, serif;">Voto:</label>
                                        <input type="number" class="form-control" id="voto" min="18" max="30" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cfu" style="font-family: 'Times New Roman', Times, serif;">CFU:</label>
                                        <input type="number" class="form-control" id="cfu" min="1" required>
                                    </div>
                                    <br>
                                    <button type="button" class="btn btn-red" onclick="addPassedExam()">Aggiungi Esame Superato</button>
                                    <button type="button" class="btn btn-red" onclick="simulatevoto()">Simula Voto Esame</button>
                                </form>
                                <br>
                            </div>
                            <div class="col-md-6">
                                <br><br>
                                <!-- Media -->
                                <p class="text-center" id="average" style="font-size: 22px; font-family: Times New Roman, Times, serif;"></p>
                                <div style="width: 100%;">
                                <!-- Grafico -->
                                    <canvas id="myChart"></canvas>
                                </div>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
    
    <script>
        // Definizione della variabile per il grafico
        let chart;

        document.addEventListener("DOMContentLoaded", function() {
            // Controllo se ci sono esami inseriti prima da php
            if (exams.length > 0) {
                // Calcola la media dei voti
                calculateAverage();
                // Aggiorna il grafico
                updateChart();
            }
            
            // Event listener per i click sul grafico
            document.getElementById('myChart').onclick = function(evt) {
                // Rileva il punto attivo nel grafico più vicino al clic dell'utente
                const activePoints = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);

                if (activePoints.length > 0) {
                    const index = activePoints[0].index;
                    const exam = exams[index];

                    // Mostra un avviso di conferma con bottoni yes e no utilizzando la libreria toastr
                    toastr.warning(
                        `<br /><button type='button' id='confirmYes' class='btn btn-primary'>Yes</button>
                        <button type='button' id='confirmNo' class='btn btn-secondary'>No</button>`,
                        `Vuoi eliminare l'esame ${exam.name}?`,
                        {
                            closeButton: true,
                            allowHtml: true,
                            // Gestione degli eventi dei bottoni nella notifica toastr
                            onShown: function (toast) {
                                document.getElementById('confirmYes').onclick = function () {
                                    deleteExam(index);
                                    toastr.clear(toast);
                                };
                                document.getElementById('confirmNo').onclick = function () {
                                    toastr.clear(toast);
                                };
                            }
                        }
                    );
                }
            };
        });

        // Funzione per eliminare un esame
        function deleteExam(index) {
            const exam = exams[index];

            // Chiamata ajax per eliminare l'esame dal database
            $.ajax({
                url: 'delete_exam.php',
                type: 'POST',
                dataType: 'json',
                data: { name: exam.name },
                success: function(response) {
                    if (response.success) {
                        // Rimuove l'esame dall'array locale
                        exams.splice(index, 1);
                        // Aggiorna il totale dei CFU
                        totalCfu -= exam.cfu;
                        // Ricalcola la media dei voti
                        calculateAverage();
                        // Aggiorna il grafico
                        updateChart();
                        // Mostra un messaggio di successo con toastr
                        toastr.success(response.message);
                    } else {
                        // Mostra un messaggio di errore con toastr
                        toastr.error(response.message);
                    }
                },
            });
        }

        // Funzione per aggiungere un nuovo esame
        function addPassedExam() {
            // Recupera i dati dall'input dell'utente
            const name = document.getElementById("name").value;
            const voto = parseFloat(document.getElementById("voto").value);
            const cfu = parseInt(document.getElementById("cfu").value);

            // Validazione dei dati inseriti
            if (name.trim() === '' || isNaN(voto) || isNaN(cfu) || voto < 18 || voto > 30 || cfu <= 0) {
                toastr.error("Dati inseriti non validi o mancanti.");
                return;
            }

            const exam = { name, voto, cfu, isSimulated: false };
            // Invia i dati al server tramite ajax
            $.ajax({
                url: 'add_exam.php',
                type: 'POST',
                dataType: 'json',
                data: { name: name, voto: voto, cfu: cfu },
                success: function(response) {
                    if (response.success) {
                        // Aggiunge l'esame all'array locale
                        exams.push(exam);
                        // Aggiorna il totale dei CFU
                        totalCfu += cfu;
                        // Ricalcola la media dei voti
                        calculateAverage();
                        // Aggiorna il grafico
                        updateChart();
                        // Mostra un messaggio di successo con toastr
                        toastr.success(response.message);
                    } else {
                        // Mostra un messaggio di errore con toastr
                        toastr.error(response.message);
                    }
                },
            });
        }

        // Funzione per simulare un voto
        function simulatevoto() {
            // Recupera i dati dall'input dell'utente
            const name = document.getElementById("name").value;
            const voto = parseFloat(document.getElementById("voto").value);
            const cfu = parseInt(document.getElementById("cfu").value);

            // Validazione dei dati inseriti
            if (name.trim() === '' || isNaN(voto) || isNaN(cfu) || voto < 18 || voto > 30 || cfu <= 0) {
                toastr.error("Dati inseriti non validi o mancanti.");
                return;
            }

            const exam = { name: name + " (simulato)", voto, cfu, isSimulated: true };
            exams.push(exam);
            totalCfu += cfu;

            // Ricalcola la media dei voti e aggiorna il grafico
            calculateAverage();
            updateChart();
        }

        // Funzione per calcolare la media (ponderata) dei voti
        function calculateAverage() {
            if (exams.length === 0) {
                document.getElementById("average").textContent = "Media: 0.00";
                return;
            }

            // reduce fa un ciclo for
            const weightedvotosSum = exams.reduce((acc, exam) => acc + (exam.voto * exam.cfu), 0);
            const totalCfu = exams.reduce((acc, exam) => acc + exam.cfu, 0);
            const average = weightedvotosSum / totalCfu;

            // Aggiorna la media nella pagina
            document.getElementById("average").textContent = `Media: ${average.toFixed(2)}`;
        }

        // Funzione per aggiornare il grafico
        function updateChart() {
            if (!chart) {
                const ctx = document.getElementById('myChart').getContext('2d');
                chart = new Chart(ctx, {
                    // Vari parametri da passare alla libreria che si occuperà di creare il grafico
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Voto',
                            data: [],
                            backgroundColor: [],
                            border: null
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMin: 18,
                                suggestedMax: 30
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            // Aggiornamento dei dati del grafico
            const examNames = exams.map(exam => exam => exam.name);
            const examvotos = exams.map(exam => exam.voto !== null ? exam.voto : NaN);
            const examColors = exams.map(exam => exam.isSimulated ? 'rgba(0, 0, 255, 0.5)' : 'rgba(175, 5, 5, 0.5)');

            chart.data.labels = examNames;
            chart.data.datasets[0].data = examvotos;
            chart.data.datasets[0].backgroundColor = examColors;

            // Chiamata alla funzione della libreria che crea o aggiorna visivamente il grafico
            chart.update();
        }

        // Funzione per mostrare/nascondere la sezione della media, grafico e form per aggiungere esami
        function toggleMedia() {
            const mediaDiv = $("#div_media");
            
            if (mediaDiv.is(":hidden")) {
                mediaDiv.slideDown(500);
            } else {
                mediaDiv.slideUp(500);
            }
        }

        // Funzione per mostrare/nascondere la sezione del cambio password
        function toggleMedia2() {
            const mediaDiv = $("#changePasswordForm");
            
            if (mediaDiv.is(":hidden")) {
                mediaDiv.slideDown(500);
            } else {
                mediaDiv.slideUp(500);
            }
        }

        // Funzione per effettuare il logout
        function logout() {
            window.location.href = "logout.php";
        }

        // Funzione per cambiare la password dell'utente
        function changePassword() {
            const oldPassword1 = document.getElementById("oldPassword").value;
            const newPassword1 = document.getElementById("newPassword").value;
            const confirmPassword = document.getElementById("confirmPassword").value;

            // Verifica che la nuova password coincida con la conferma della password
            if (newPassword1 !== confirmPassword) {
                toastr.error("La nuova password e la conferma della password non corrispondono.");
                return;
            }

            // Chiamata AJAX per cambiare la password
            $.ajax({
                url: 'change_password.php',
                type: 'POST',
                dataType: 'json',
                data: { oldPassword: oldPassword1, newPassword: newPassword1 },
                success: function(response) {
                    if (response.success) {
                        // Mostra un messaggio di successo con Toastr e nasconde il modulo di cambio password
                        toastr.success(response.success);
                        toggleMedia2();
                    } else {
                        // Mostra un messaggio di errore con Toastr
                        toastr.error(response.error);
                    }
                }
            });
        }
    </script>
</body>
</html>
