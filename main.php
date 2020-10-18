<?php

//Datenbankverbindung
include('dbconnector.inc.php');

// Initialisierung
$error = $message =  '';
$fname = $name = $sex = $street = $plz = $city = $canton = $email = $tel = $profilepic = '';

// Sessionhandling
session_start();
session_regenerate_id();

// Have this block to verify if the user is logged in the session in every file, where the user must be logged in
if (isset($_SESSION['loggedin']) && isset($_SESSION['email'])) {
} else {
    header("Location: index.php");
    die();
}

$selectStatement = "SELECT * FROM users WHERE email=?";

$stmt = $mysqli->prepare($selectStatement);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$sessionData = $result->fetch_assoc(); // fetch data   

foreach ($sessionData as $key => $value) {
    if ($key == "uid") {
        $session_user_id = $value;
        break;
    }
}


// Wurden Daten mit "POST" gesendet?
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Ausgabe des gesamten $_POST Arrays
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

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


    // vorname ausgefüllt?
    if (isset($_POST['fname'])) {
        //trim and sanitize
        $fname = trim(htmlspecialchars($_POST['fname']));
        //mindestens 1 Zeichen und maximal 30 Zeichen lang
        if (empty($fname) || strlen($fname) > 30) {
            $error .= "Geben Sie bitte einen korrekten Vornamen ein.<br />";
        }
    } else {
        $error .= "Geben Sie bitte einen Vornamen ein.<br />";
    }


    // geschlecht ausgefüllt?
    if (isset($_POST['sex'])) {
        //trim and sanitize
        $sex = trim(htmlspecialchars($_POST['sex']));
        //maximal 1 Zeichen
        if (empty($sex) || strlen($sex) > 1) {
            $error .= "Geben Sie bitte ein korrektes Geschlecht ein.<br />";
        }
    } else {
        $error .= "Geben Sie bitte einen Geschlecht ein.<br />";
    }

    // adresse ausgefüllt?
    if (isset($_POST['street'])) {
        //trim and sanitize
        $street = trim(htmlspecialchars($_POST['street']));
        //mindestens 1 Zeichen und maximal 50 Zeichen lang
        if (empty($street) || strlen($street) > 50) {
            $error .= "Geben Sie bitte eine korrekte Adresse ein.<br />";
        }
    } else {
        $error .= "Geben Sie bitte einen Adresse ein.<br />";
    }

    // plz ausgefüllt? +++braucht noch !preg_match+++
    if (isset($_POST['plz'])) {
        //trim and sanitize
        $plz = trim(htmlspecialchars($_POST['plz']));
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

    $canton = "BS";
    // canton ausgefüllt +++braucht noch !preg_match+++
    //if (isset($_POST['canton'])) {
    //trim and sanitize
    //$canton = trim($_POST['canton']);
    //immer 2 Zeichen
    //if (empty($canton) || strlen($canton) > 2) {
    // $error .= "Geben Sie bitte einen korrekten Kanton an.<br />";
    //}
    //} else {
    //$error .= "Geben Sie bitte einen Kanton an.<br />";
    // }

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
    if (isset($_POST['tel'])) {
        //trim and sanitize
        $tel = trim($_POST['tel']);
        //mindestens 1 Zeichen , entsprich RegEX
        if (empty($tel) || strlen($tel) > 10) {
            $error .= "Geben Sie bitte eine korrekte Telefonnummer eine.<br />";
        }
    } else {
        $error .= "Geben Sie bitte eine Telefonnummer ein.<br />";
    }

    if (isset($_POST['profilepic'])) {
        $filename = 'validatedCustomFile';
        $input = fopen('php://input', 'rb');
        $file = fopen($filename, 'wb');
        stream_copy_to_stream($input, $file);
        fclose($input);
        fclose($file);
    }

    // wenn kein Fehler vorhanden ist, schreiben der Daten in die Datenbank
    if (empty($error)) {

        // INPUT Query erstellen, welches firstname, lastname, password, email in die Datenbank schreibt
        $insertStatement = "INSERT into friend(name, fname, sex, street, city, plz, canton, tel, email, profilepic, friend_of_user) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        // Query vorbereiten mit prepare();
        $stmt = $mysqli->prepare($insertStatement);
        // Parameter an Query binden mit bind_param();
        $stmt->bind_param("ssssissssbi", $name, $fname, $sex, $street, $city, $plz, $canton, $tel, $email, $profilepic, $session_user_id);
        // query ausführen mit execute();
        $stmt->execute();
        // Verbindung schliessen
        $stmt->close();
        // Weiterleitung auf login.php
        header("Location: main.php");
    }
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

                <a class="btn btn-danger ml-auto mr-0" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>



    <header class="masthead text-white pt-4" style="background-color: #baddff;">
        <div class="container">
            <h1>Freunde</h1>

            <?php
            $selectStatement = "SELECT * FROM friend WHERE friend_of_user=?";

            $stmt = $mysqli->prepare($selectStatement);
            $stmt->bind_param("i", $session_user_id);
            $stmt->execute();
            $result = $stmt->get_result(); // get the mysqli result
            $resultArray = $result->fetch_assoc(); // fetch data   
            $stmt->close();
            ?>

            <div class="row mt-5 mb-5">

                <?php
                foreach ($result as $row) : ?>

                    <!-- Column -->
                    <div class="col-lg-4">
                        <!-- Profile Pic -->
                        <div class="text-center">
                            <img class="m-3" src="assets\img\friend_profile_picture.png" alt="Friendbook Logo" width="150px" height="150px">
                        </div>
                        <div>
                            <?php foreach ($row as $key => $col) : ?>
                                <h5>
                                    <?php
                                    // Skip attributes like f_id 
                                    if ($key == "f_id" || $key == "profilepic" || $key == "friend_of_user") {
                                        continue;
                                    }
                                    switch ($key) {
                                        case "name":
                                            $key = "Name: ";
                                            break;
                                        case "fname":
                                            $key = "Vorname: ";
                                            break;
                                        case "sex":
                                            $key = "Geschlecht: ";
                                            break;
                                        case "city":
                                            $key = "Stadt: ";
                                            break;
                                        case "street":
                                            $key = "Strasse: ";
                                            break;
                                        case "plz":
                                            $key = "PLZ: ";
                                            break;
                                        case "canton":
                                            $key = "Kanton: ";
                                            break;
                                        case "tel":
                                            $key = "Telefon: ";
                                            break;
                                        case "email":
                                            $key = "Email: ";
                                            break;
                                    }
                                    // Bsp. $key => Name: $col => Mathis
                                    echo $key;
                                    echo $col;
                                    if ($key == "f_id") {
                                    }
                                    ?>
                                </h5>
                            <?php endforeach; ?>
                            <br>
                            <div class="mt-2 mb-5">
                                <br>
                                <a class="btn btn-warning ml-auto mr-0" role="button" href="#">Bearbeiten</a>
                                <?php
                                echo $globalCol;
                                echo "F_ID: " . $resultArray['f_id'];
                                ?>

                                <form action='delete.php?f_id="<?php echo $resultArray['f_id'] ?>"' method="post">
                                    <button type="submit" class="btn btn-danger ml-auto mr-0" role="button" name="submit" value="Delete">
                                        Freund loeschen
                                    </button>
                                </form>


                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
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
                            <form action="main.php" method="post">
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
                                    <p>Wähle Geschlecht:</p>
                                    <input type="radio" id="m" name="sex" value="m">
                                    <label for="m">Male</label>
                                    <input type="radio" id="f" name="sex" value="f">
                                    <label for="f">Female</label>
                                    <input type="radio" id="o" name="sex" value="o">
                                    <label for="o">Other</label>
                                </div>

                                <!-- Adresse -->
                                <div class="form-group">
                                    <label for="street">Strasse, Hausnr.</label>
                                    <input type="text" name="street" class="form-control" id="street" value="<?php echo $street ?>" placeholder="Adresse" maxlength="50" required="false">
                                </div>

                                <!-- PLZ -->
                                <div class="form-group">
                                    <label for="plz">PLZ</label>
                                    <input type="number" name="plz" class="form-control" id="plz" value="<?php echo $plz ?>" placeholder="Postleitzahl" maxlength="4" required="false" pattern="([1-468][0-9]|[57][0-7]|9[0-6])[0-9]{2}">
                                </div>

                                <!-- Stadt -->
                                <div class="form-group">
                                    <label for="city">Stadt</label>
                                    <input type="text" name="city" class="form-control" id="city" value="<?php echo $city ?>" placeholder="Stadt, Gemeinde, Dorf" maxlength="30" required="false">
                                </div>

                                <!-- Kanton -->
                                <div class="form-group">
                                    <label for="canton">Kanton</label>
                                    <select class="browser-default custom-select">
                                        <option value="AG">AG</option>
                                        <option value="AI">AI</option>
                                        <option value="AR">AR</option>
                                        <option value="BE">BE</option>
                                        <option value="BL">BL</option>
                                        <option value="BS">BS</option>
                                        <option value="FR">FR</option>
                                        <option value="GE">GE</option>
                                        <option value="GL">GL</option>
                                        <option value="GR">GR</option>
                                        <option value="JU">JU</option>
                                        <option value="LU">LU</option>
                                        <option value="NE">NE</option>
                                        <option value="NW">NW</option>
                                        <option value="OW">OW</option>
                                        <option value="SG">SG</option>
                                        <option value="SH">SH</option>
                                        <option value="SO">SO</option>
                                        <option value="SZ">SZ</option>
                                        <option value="TG">TG</option>
                                        <option value="TI">TI</option>
                                        <option value="UR">UR</option>
                                        <option value="VD">VD</option>
                                        <option value="VS">VS</option>
                                        <option value="ZG">ZG</option>
                                        <option value="ZH">ZH</option>
                                    </select>
                                </div>

                                <!-- E-Mail -->
                                <div class="form-group">
                                    <label for="email">E-Mail</label>
                                    <input type="email" name="email" class="form-control" id="email" value="<?php echo $email ?>" placeholder="E-Mail" maxlength="100" required="false">
                                </div>

                                <!-- Telefonnummer -->
                                <div class="form-group">
                                    <label for="tel">Telefon</label>
                                    <input type="text" name="tel" class="form-control" id="tel" value="<?php echo $tel ?>" placeholder="Telefon-Format: '0612345678'" minlength="9" maxlength="13" required="false" pattern="0(2[1-246-7]|3[1-4]|4[13-4]|5[25-6]|6[1-2]|7[15-68-9]|8[17]|91)[0-9]{7}">
                                </div>

                                <!-- Profile Picture Upload -->
                                <label for="profilepic">Profilfoto</label>
                                <div class="custom-file">
                                    <input type="file" name="profilepic" class="custom-file-input" id="validatedCustomFile" value="<?php echo $profilepic ?>">
                                    <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                                    <div class="invalid-feedback">Example invalid custom file feedback</div>
                                </div>

                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Freund hinzufügen</button>
                                </div>
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