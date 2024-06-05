<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/css/bootstrap.css" rel="stylesheet">
    <title>Ingegneria informatica</title>
    <link rel="stylesheet" type="text/css" href="../my_css/facolta.css">
    <style>
        /* Sfondo personalizzato */
        body {
            background-image: url('../immagini/sfondo_ing_info.jpg');
        }
    </style>
    <link rel="icon" href="../immagini/LogoFinale.png" type="image/x-icon">
    <link rel="shortcut icon" href="../immagini/LogoFinale.png" type="image/x-icon">
    <!-- Script per gestione pulsante per mostrare link nella navbar nella versione da telefono -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <!-- Div per sondo -->
    <div class="background-overlay"></div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark color_bar">
    <a class="navbar-brand" href="../main_page.html">
            <img src="../immagini/LogoFinale.png" alt="Logo" class="mr-2 mrg_logo" width="25" height="25">WikiSapienza
        </a>
        
        <!-- Pulsante che contiene i link nella navbar da telefono -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto" style="margin-left: 10px;">
                <li class="nav-item">
                    <a class="nav-link" href="../my_area.php">My Area</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../about_us.html">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://github.com/danielezitoo/WikiSapienza">Source Code</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Div per titilo, descrizione e varie materie del corso -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1 class="size_text_titolo"><img src="../immagini/logo_ing.png" alt="Logo Personalizzabile" width="40" height="40">Ingegneria Informatica</h1>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6" style="margin-top: 15px;">
                <p class="lead size_text_descr" style="font-family: 'Georgia';">
                    Benvenuti nel corso di ingegneria informatica, un percorso accademico che si distingue per la sua profonda fusione di
                    teoria e pratica nel campo dell'informatica e dell'ingegneria. Questo corso si propone di fornire agli studenti una comprensione
                    esaustiva dei principi fondamentali e delle metodologie avanzate che guidano lo sviluppo, la progettazione e l'implementazione di
                    sistemi informatici. 
                    <br><br>
                    Il programma si distingue per il suo approccio che incoraggia la collaborazione e lo scambio di conoscenze tra 
                    diverse materie. Gli studenti avranno l'opportunità di lavorare su progetti di gruppo, affrontando problemi reali e sviluppando 
                    soluzioni innovative con l'ausilio di tecnologie all'avanguardia e metodologie di sviluppo del software.
                </p>
            </div>
            <div class="col-md-6 text-center">
                <!-- Div per la tabella con le materie (Scelte sulla base del nostro percorso di studi) che se cliccate renderizzano al forum e pagina dell materia-->
                <h3 class="text-center">Le materie di questo corso:</h3>
                <div style="max-height: 430px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;" class="text-center">
                    <p class="lead size_text_mat">
                        <h4>Primo anno</h4>
                        <table style="margin: auto; ">
                            <thead>
                                <tr>
                                    <th>Codice corso</th>
                                    <th>Materia</th>
                                    <th style="padding-right: 10px;">Semestre</th>
                                    <th>CFU</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1017218</td>
                                    <td class="table-link"><a href="../materia.php?materia=Analisi%20I">ANALISI I</td>
                                    <td>1°</td>
                                    <td>12</td>
                                </tr>
                                <tr>
                                    <td>1056024</td>
                                    <td class="table-link"><a href="../materia.php?materia=Fondamenti%20di%20Informatica%20I">FOND. DI INFORMATICA I</td>
                                    <td>1°</td>
                                    <td>9</td>
                                </tr>
                                <tr>
                                    <td>101204</td>
                                    <td class="table-link"><a href="../materia.php?materia=Geometria">GEOMETRIA</td>
                                    <td>1°</td>
                                    <td>6</td>
                                </tr>
                                <tr>
                                    <td>1017400</td>
                                    <td class="table-link"><a href="../materia.php?materia=Fisica">FISICA</td>
                                    <td>2°</td>
                                    <td>12</td>
                                </tr>
                                <tr>
                                    <td>1056025</td>
                                    <td class="table-link"><a href="../materia.php?materia=Tecniche%20di%20Programmazione">TECNICHE DI PROGRAMMAZIONE</td>
                                    <td>2°</td>
                                    <td>9</td>
                                </tr>
                                <tr>
                                    <td>1018733</td>
                                    <td class="table-link"><a href="../materia.php?materia=Probabilità%20e%20Statistica">PROBABILITA' E STATISTICA</td>
                                    <td>2°</td>
                                    <td>6</td>
                                </tr>
                            </tbody>
                    </table>
                    <br>
                    <h4>Secondo anno</h4>
                    <table style="margin: auto;">
                        <thead>
                            <tr>
                                <th>Codice corso</th>
                                <th>Materia</th>
                                <th style="padding-right: 10px;">Semestre</th>
                                <th>CFU</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1018706</td>
                                <td class="table-link"><a href="../materia.php?materia=Progettazione%20del%20Software">PROGETTAZIONE DEL SOFTWARE</td>
                                <td>1°</td>
                                <td>9</td>
                            </tr>
                            <tr>
                                <td>1056028</td>
                                <td class="table-link"><a href="../materia.php?materia=Teoria%20dei%20Sistemi">TEORIA DEI SISTEMI</td>
                                <td>1°</td>
                                <td>9</td>
                            </tr>
                            <tr>
                                <td>1002027</td>
                                <td class="table-link"><a href="../materia.php?materia=Ricerca%20Operativa">RICERCA OPERATIVA</td>
                                <td>1°</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <td>1017400</td>
                                <td class="table-link"><a href="../materia.php?materia=Analisi%20Matematica%20II">ANALISI MATEMATICA II</td>
                                <td>1°</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <td>1018704</td>
                                <td class="table-link"><a href="../materia.php?materia=Fondamenti%20di%20Informatica%20II">FOND. DI INFORMATICA II</td>
                                <td>2°</td>
                                <td>12</td>
                            </tr>
                            <tr>
                                <td>1056029</td>
                                <td class="table-link"><a href="../materia.php?materia=Sistemi%20di%20Calcolo%20I">SISTEMI DI CALCOLO I</td>
                                <td>2°</td>
                                <td>9</td>
                            </tr>
                            <tr>
                                <td>1021946</td>
                                <td class="table-link"><a href="../materia.php?materia=Controlli%20Automatici">CONTROLLI AUTOMATICI</td>
                                <td>2°</td>
                                <td>6</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <h4>Terzo anno:</h4>
                    <table style="margin: auto;">
                        <thead>
                            <tr>
                                <th>Codice corso</th>
                                <th>Materia</th>
                                <th style="padding-right: 10px;">Semestre</th>
                                <th>CFU</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1017398</td>
                                <td class="table-link"><a href="../materia.php?materia=Economia">ECONOMIA</td>
                                <td>1°</td>
                                <td>9</td>
                            </tr>
                            <tr>
                                <td>1015392</td>
                                <td class="table-link"><a href="../materia.php?materia=Telecomunicazioni">TELECOMUNICAZIONI</td>
                                <td>1°</td>
                                <td>9</td>
                            </tr>
                            <tr>
                                <td>1041466</td>
                                <td class="table-link"><a href="../materia.php?materia=Basi%20di%20Dati">BASI DI DATI</td>
                                <td>1°</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <td>1056030</td>
                                <td class="table-link"><a href="../materia.php?materia=Sistemi%20di%20Calcolo%20II">SISTEMI DI CALCOLO II</td>
                                <td>1°</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <td>1016596</td>
                                <td class="table-link"><a href="../materia.php?materia=Elettronica">ELETTRONICA</td>
                                <td>2°</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <td>10599901</td>
                                <td class="table-link"><a href="../materia.php?materia=Fondamenti%20di%20IA">FONDAMENTI DI IA</td>
                                <td>2°</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <td>1018745</td>
                                <td class="table-link"><a href="../materia.php?materia=Reti%20di%20Calcolatori">RETI DI CALCOLATORI</td>
                                <td>2°</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <td>1041467</td>
                                <td class="table-link"><a href="../materia.php?materia=Linguaggi%20e%20Tecnologie%20per%20il%20Web">LING. E TECNO. PER IL WEB</a></td>
                                <td>2°</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <td>AAF1523</td>
                                <td class="table-link"><a href="../materia.php?materia=Laboratorio%20di%20IA">LABORATORIO DI IA</td>
                                <td>2°</td>
                                <td>6</td>
                            </tr>
                        </tbody>
                    </table>
                </p>
            </div>                    
        </div>
    </div>
</body>
</html>