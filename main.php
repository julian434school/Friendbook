<?php

//Datenbankverbindung
include('dbconnector.inc.php');

// Initialisierung
$error = $message =  '';
$fname = $name = $gender = $address = $plz = $city = $canton = $email = $tele = $pfp = '';

/*
TODO WICHTIG!!! Nicht alle Felder sind Pflichtfelder!! Vorname und Nachname nur!!!!!
*/

// Wurden Daten mit "POST" gesendet?
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($firstname)) {
    // Ausgabe des gesamten $_POST Arrays
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";



    // vorname ausgefüllt?
    if (isset($_POST['fname'])) {
        //trim and sanitize
        $firstname = trim(htmlspecialchars($_POST['fname']));
        //mindestens 1 Zeichen und maximal 30 Zeichen lang
        if (empty($fname) || strlen($fname) > 30) {
            $error .= "Geben Sie bitte einen korrekten Vornamen ein.<br />";
        }
    } else {
        $error .= "Geben Sie bitte einen Vornamen ein.<br />";
    }

    // nachname ausgefüllt?
    if (isset($_POST['name'])) {
        //trim and sanitize
        $name = trim(htmlspecialchars($_POST['name']));
        //mindestens 1 Zeichen und maximal 30 Zeichen lang
        if (empty($name) || strlen($name) > 30) {
            $error .= "Geben Sie bitte einen korrekten Nachname ein.<br />";
        }
    } else {
        $error .= "Geben Sie bitte einen Nachname ein.<br />";
    }


    // geschlecht ausgefüllt?
    if (isset($_POST['gender'])) {
        //trim and sanitize
        $gender = trim(htmlspecialchars($_POST['gender']));
        //maximal 1 Zeichen
        if (empty($gender) || strlen($gender) > 1) {
            $error .= "Geben Sie bitte ein korrektes Geschlecht ein.<br />";
        }
    } else {
        $error .= "Geben Sie bitte einen Geschlecht ein.<br />";
    }

    // adresse ausgefüllt?
    if (isset($_POST['address'])) {
        //trim and sanitize
        $plz = trim(htmlspecialchars($_POST['address']));
        //mindestens 1 Zeichen und maximal 50 Zeichen lang
        if (empty($address) || strlen($address) > 50) {
            $error .= "Geben Sie bitte eine korrekte Adresse ein.<br />";
        }
    } else {
        $error .= "Geben Sie bitte einen Adresse ein.<br />";
    }

    // plz ausgefüllt? +++braucht noch !preg_match+++
    if (isset($_POST['plz'])) {
        //trim and sanitize
        $firstname = trim(htmlspecialchars($_POST['plz']));
        //mindestens 1 Zeichen und maximal 30 Zeichen lang
        if (empty($plz) || strlen($plz) > 4) {
            $error .= "Geben Sie bitte eine korrekte Postleitzahl ein.<br />";
        }
    } else {
        $error .= "Geben Sie bitte einen Postleitzahl ein.<br />";
    }

    // city ausgefüllt?
    if (isset($_POST['city'])) {
        //trim and sanitize
        $city = trim(htmlspecialchars($_POST['city']));
        //mindestens 1 Zeichen und maximal 30 Zeichen lang
        if (empty($city) || strlen($city) > 30) {
            $error .= "Geben Sie bitte eine korrekte Stadt an.<br />";
        }
    } else {
        $error .= "Geben Sie bitte eine Stadt an.<br />";
    }

    // canton ausgefüllt +++braucht noch !preg_match+++
    if (isset($_POST['canton'])) {
        //trim and sanitize
        $password = trim($_POST['canton']);
        //immer 2 Zeichen
        if (empty($canton) || strlen($canton) > 2) {
            $error .= "Geben Sie bitte einen korrekten Kanton an.<br />";
        }
    } else {
        $error .= "Geben Sie bitte einen Kanton an.<br />";
    }

    // email ausgefüllt?
    if (isset($_POST['email'])) {
        //trim
        $email = trim($_POST['email']);
        //mindestens 1 Zeichen und maximal 100 Zeichen lang, gültige Emailadresse
        if (empty($email) || strlen($email) > 100 || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $error .= "Geben Sie bitte eine korrekten Emailadresse ein.<br />";
        }
    } else {
        $error .= "Geben Sie bitte eine Emailadresse ein.<br />";
    }

    // telefon ausgefüllt?
    if (isset($_POST['tele'])) {
        //trim and sanitize
        $tele = trim($_POST['tele']);
        //mindestens 1 Zeichen , entsprich RegEX
        if (empty($tele) || strlen($tele) > 10) {
            $error .= "Geben Sie bitte eine korrekte Telefonnummer eine.<br />";
        }
    } else {
        $error .= "Geben Sie bitte eine Telefonnummer ein.<br />";
    }

    // TODO, steht noch unter Bearbeitung
    // wenn kein Fehler vorhanden ist, schreiben der Daten in die Datenbank
    if (empty($error)) {
        // INPUT Query erstellen, welches firstname, lastname, password, email in die Datenbank schreibt
        $insertStatement = "INSERT into users(fname, name, pword, email) VALUES (?, ?, ?, ?)";
        // Query vorbereiten mit prepare();
        $stmt = $mysqli->prepare($insertStatement);
        // Parameter an Query binden mit bind_param();
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $firstname, $lastname, $password, $email);
        // query ausführen mit execute();
        $stmt->execute();
        // Verbindung schliessen
        $stmt->close();
        // Weiterleitung auf login.php
        header("Location: main.php");
    }
}

// Login POST verify
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($firstname) == false) {
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Home - Friendbook</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/simple-line-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
</head>

<body>
    <nav class="navbar navbar-light navbar-expand bg-light navigation-clean">
        <div class="container"><a class="navbar-brand" href="index.php">
                <img src="assets\img\friendbook_logo.png" alt="Friendbook Logo">
            </a>
            <div class="collapse navbar-collapse" id="navcol-1">

                <!-- Search Friend -->
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search a friend" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>

                <!-- Nav Add Friend -->
                <a class="btn btn-success ml-auto mr-0" role="button" href="#" data-toggle="modal" data-target="#addFModal">Freund hinzufügen</a>
            </div>
        </div>
    </nav>



    <header class="masthead text-white" style="background-color: #baddff;">
        <div class="container">
            <h1>Freunde</h1>

            <div class="row mt-5 mb-5">
                <div class="col-lg-4">

                    <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
                        <!-- Profile Pic -->
                        <div class="text-center">
                            <img class="m-3" src="assets\img\friend_profile_picture.png" alt="Friendbook Logo" width="150px" height="150px">
                            <!-- <h1>[PROFILE PIC]</h1> -->
                            <h3 class="m-3">Julian Mathis</h3>
                        </div>
                        <h5>Land: Schweiz</h5>
                        <h5>Canton: Basel</h5>
                        <h5>Gender: Chad</h5>
                        <h5>Adresse: Adresse [+housenum], PLZ, Ort</h5>
                        <h5>Email: [Email]</h5>
                        <h5>Handy: 076 528 21 82</h5>
                        <div class="mt-4 mb-4">
                            <a class="btn btn-warning ml-auto mr-0" role="button" href="#">Bearbeiten</a>
                            <a class="btn btn-danger ml-auto mr-0" role="button" href="#">Freund löschen</a>
                        </div>
                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
                        <!-- Profile Pic -->
                        <div>
                            <!-- <img src="assets\img\friendbook_logo.png" alt="Friendbook Logo"> -->
                            <h1>[PROFILE PIC]</h1>
                        </div>
                        <h3>Julian Mathis</h3>
                        <h5>Land: Schweiz</h5>
                        <h5>Canton: Basel</h5>
                        <h5>Gender: Chad</h5>
                        <h5>Adresse: Adresse [+housenum], PLZ, Ort</h5>
                        <h5>Email: [Email]</h5>
                        <h5>Handy: 076 528 21 82</h5>
                        <a class="btn btn-warning ml-auto mr-0" role="button" href="#">Bearbeiten</a>
                        <a class="btn btn-danger ml-auto mr-0" role="button" href="#">Freund löschen</a>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
                        <!-- Profile Pic -->
                        <div>
                            <!-- <img src="assets\img\friendbook_logo.png" alt="Friendbook Logo"> -->
                            <h1>[PROFILE PIC]</h1>
                        </div>
                        <h3>Julian Mathis</h3>
                        <h5>Land: Schweiz</h5>
                        <h5>Canton: Basel</h5>
                        <h5>Gender: Chad</h5>
                        <h5>Adresse: Adresse [+housenum], PLZ, Ort</h5>
                        <h5>Email: [Email]</h5>
                        <h5>Handy: 076 528 21 82</h5>
                        <a class="btn btn-warning ml-auto mr-0" role="button" href="#">Bearbeiten</a>
                        <a class="btn btn-danger ml-auto mr-0" role="button" href="#">Freund löschen</a>

                    </div>

                </div>

            </div>


            <div class="row mt-5 mb-5">
                <div class="col-lg-4">

                    <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
                        <!-- Profile Pic -->
                        <div>
                            <!-- <img src="assets\img\friendbook_logo.png" alt="Friendbook Logo"> -->
                            <h1>[PROFILE PIC]</h1>
                        </div>
                        <h3>Julian Mathis</h3>
                        <h5>Land: Schweiz</h5>
                        <h5>Canton: Basel</h5>
                        <h5>Gender: Chad</h5>
                        <h5>Adresse: Adresse [+housenum], PLZ, Ort</h5>
                        <h5>Email: [Email]</h5>
                        <h5>Handy: 076 528 21 82</h5>
                        <a class="btn btn-warning ml-auto mr-0" role="button" href="#">Bearbeiten</a>

                        <a class="btn btn-danger ml-auto mr-0" role="button" href="#">Freund löschen</a>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
                        <!-- Profile Pic -->
                        <div>
                            <!-- <img src="assets\img\friendbook_logo.png" alt="Friendbook Logo"> -->
                            <h1>[PROFILE PIC]</h1>
                        </div>
                        <h3>Julian Mathis</h3>
                        <h5>Land: Schweiz</h5>
                        <h5>Canton: Basel</h5>
                        <h5>Gender: Chad</h5>
                        <h5>Adresse: Adresse [+housenum], PLZ, Ort</h5>
                        <h5>Email: [Email]</h5>
                        <h5>Handy: 076 528 21 82</h5>
                        <a class="btn btn-warning ml-auto mr-0" role="button" href="#">Bearbeiten</a>
                        <a class="btn btn-danger ml-auto mr-0" role="button" href="#">Freund löschen</a>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
                        <!-- Profile Pic -->
                        <div>
                            <!-- <img src="assets\img\friendbook_logo.png" alt="Friendbook Logo"> -->
                            <h1>[PROFILE PIC]</h1>
                        </div>
                        <h3>Julian Mathis</h3>
                        <h5>Land: Schweiz</h5>
                        <h5>Canton: Basel</h5>
                        <h5>Gender: Chad</h5>
                        <h5>Adresse: Adresse [+housenum], PLZ, Ort</h5>
                        <h5>Email: [Email]</h5>
                        <h5>Handy: 076 528 21 82</h5>
                        <a class="btn btn-warning ml-auto mr-0" role="button" href="#">Bearbeiten</a>
                        <a class="btn btn-danger ml-auto mr-0" role="button" href="#">Freund löschen</a>
                    </div>
                </div>
            </div>

            <!-- Add Friend Modal -->
            <div class="modal fade" id=addFModal tabindex="-1" role="dialog" aria-labelledby="addFModalLabel" aria-hidden="true" style="color: #000">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addFModalLabel" style="color:#2e2e2e">Füge Freund hinzu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Freund Hinzufügen -->
                            <form action="" method="post">
                                <!-- Vorname -->
                                <div class="form-group">
                                    <label for="fname">Vorname *</label>
                                    <input type="text" name="fname" class="form-control" id="fname" value="<?php echo $fname ?>" placeholder="Vorname" maxlength="30" required="true">
                                </div>
                                <!-- Nachname -->
                                <div class="form-group">
                                    <label for="name">Nachname *</label>
                                    <input type="text" name="name" class="form-control" id="name" value="<?php echo $name ?>" placeholder="Name" maxlength="30" required="true">
                                </div>
                                <!-- Geschlecht -->
                                <div class="form-group">
                                    <label for="gender">Geschlecht</label>
                                    <input type="text" name="gender" class="form-control" id="gender" value="<?php echo $gender ?>" placeholder="Geschlecht (M/F/O)" maxlength="1" required="false" pattern="M|F|O">
                                </div>
                                <!-- Adresse -->
                                <div class="form-group">
                                    <label for="address">Strasse, Hausnr.</label>
                                    <input type="text" name="address" class="form-control" id="address" value="<?php echo $address ?>" placeholder="Adresse" maxlength="50" required="false">
                                </div>
                                <!-- PLZ -->
                                <div class="form-group">
                                    <label for="plz">PLZ</label>
                                    <input type="number" name="plz" class="form-control" id="plz" value="<?php echo $plz ?>" placeholder="Postleitzahl" maxlength="4" required="false" pattern="([1-468][0-9]|[57][0-7]|9[0-6])[0-9]{2}">
                                </div>
                                <!-- Ort -->
                                <div class="form-group">
                                    <label for="city">Ort</label>
                                    <input type="text" name="city" class="form-control" id="city" value="<?php echo $city ?>" placeholder="Stadt, Gemeinde, Dorf" maxlength="30" required="false">
                                </div>
                                <!-- Kanton -->
                                <div class="form-group">
                                    <label for="canton">Kanton</label>
                                    <input type="text" name="canton" class="form-control" id="canton" value="<?php echo $canton ?>" placeholder="Kantons-Abk." maxlength="2" required="false" pattern="[A-Z]{2}">
                                </div>
                                <!-- E-Mail -->
                                <div class="form-group">
                                    <label for="email">E-Mail</label>
                                    <input type="email" name="email" class="form-control" id="email" value="<?php echo $email ?>" placeholder="E-Mail" maxlength="100" required="false">
                                </div>
                                <!-- Telefonnummer -->
                                <div class="form-group">
                                    <label for="tele">Telefon</label>
                                    <input type="text" name="tele" class="form-control" id="tele" value="<?php echo $tele ?>" placeholder="Telefon-Format: '0612345678'" minlength="9" maxlength="13" required="false" pattern="0(2[1-246-7]|3[1-4]|4[13-4]|5[25-6]|6[1-2]|7[15-68-9]|8[17]|91)[0-9]{7}">
                                </div>

                                <!-- TODO DOESNT WORK YET - Profile Picture Upload -->
                                <label for="pfpUpload">Profilfoto</label>
                                <div class="custom-file">
                                    <input type="file" name="pfpUpload" class="custom-file-input" id="validatedCustomFile" required>
                                    <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                                    <div class="invalid-feedback">Example invalid custom file feedback</div>
                                </div>

                                <!-- TODO - Button to submit info -->

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </header>
    <footer class="footer bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 my-auto h-100 text-center text-lg-left">
                    <ul class="list-inline mb-2">
                        <li class="list-inline-item"><a href="#">About</a></li>
                        <li class="list-inline-item"><span>⋅</span></li>
                        <li class="list-inline-item"><a href="#">Contact</a></li>
                        <li class="list-inline-item"><span>⋅</span></li>
                        <li class="list-inline-item"><a href="#">Terms of &nbsp;Use</a></li>
                        <li class="list-inline-item"><span>⋅</span></li>
                        <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                    </ul>
                    <p class="text-muted small mb-4 mb-lg-0">© Friendbook 2020. All Rights Reserved.</p>
                </div>
                <div class="col-lg-6 my-auto h-100 text-center text-lg-right">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="#"><i class="fa fa-facebook fa-2x fa-fw"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fa fa-twitter fa-2x fa-fw"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fa fa-instagram fa-2x fa-fw"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>

    </footer>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
</body>

</html>