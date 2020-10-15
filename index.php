<?php

//Datenbankverbindung
include('dbconnector.inc.php');

// Initialisierung
$error = $message =  '';
$firstname = $lastname = $email = '';

// Wurden Daten mit "POST" gesendet?
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($firstname)) {
  // Ausgabe des gesamten $_POST Arrays
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";

  // vorname ausgefüllt?
  if (isset($_POST['firstname'])) {
    //trim and sanitize
    $firstname = trim(htmlspecialchars($_POST['firstname']));

    //mindestens 1 Zeichen und maximal 30 Zeichen lang
    if (empty($firstname) || strlen($firstname) > 30) {
      $error .= "Geben Sie bitte einen korrekten Vornamen ein.<br />";
    }
  } else {
    $error .= "Geben Sie bitte einen Vornamen ein.<br />";
  }

  // nachname ausgefüllt?
  if (isset($_POST['lastname'])) {
    //trim and sanitize
    $lastname = trim(htmlspecialchars($_POST['lastname']));

    //mindestens 1 Zeichen und maximal 30 Zeichen lang
    if (empty($lastname) || strlen($lastname) > 30) {
      $error .= "Geben Sie bitte einen korrekten Nachname ein.<br />";
    }
  } else {
    $error .= "Geben Sie bitte einen Nachname ein.<br />";
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

  // passwort ausgefüllt
  if (isset($_POST['password'])) {
    //trim and sanitize
    $password = trim($_POST['password']);

    //mindestens 1 Zeichen , entsprich RegEX
    if (empty($password) || !preg_match("/(?=^.{8,255}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)) {
      $error .= "Geben Sie bitte einen korrektes Password ein.<br />";
    }
  } else {
    $error .= "Geben Sie bitte ein Password ein.<br />";
  }

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

//
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
        <!-- Nav Register Button -->
        <a class="btn btn-success ml-auto mr-0" role="button" href="#" data-toggle="modal" data-target="#registerModal">Registrieren</a>
        <!-- Nav Login Button -->
        <a class="btn btn-primary ml-3 mr-0" role="button" href="#" data-toggle="modal" data-target="#loginModal">Einloggen</a>
      </div>
    </div>
  </nav>
  <header class="masthead text-white text-center" style="background:url('assets/img/bg-masthead.jpg')no-repeat center center;background-size:cover;">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-xl-9 mx-auto">
          <h1 class="mb-5">Willkommen zu Friendbook! PHP<br>Melde dich zuerst bitte an.</h1>
        </div>
        <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">

          <!-- Hero Register Button -->
          <div class="form-row justify-content-center align-items-center">
            <div class="col-12 col-md-3">
              <button type="button" class="btn btn-primary btn-block btn-lg" style="text-align: center;" data-toggle="modal" data-target="#registerModal">Registrieren
              </button>
            </div>

            <!-- Hero Login Button -->
            <div class="col-12 col-md-3" style="text-align: center;">
              <button type="button" class="btn btn-primary btn-block btn-lg" style="text-align: center;" data-toggle="modal" data-target="#loginModal">Einloggen
              </button>
            </div>

            <!-- Register Modal -->
            <div class="modal fade" id=registerModal tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">

                  <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel" style="color:#2e2e2e">Registrieren</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <!-- Registrierung füer Friendbook Modal -->
                    <form action="" method="post">
                      <!-- Vorname -->
                      <div class="form-group">
                        <label for="firstname">Vorname *</label>
                        <input type="text" name="firstname" class="form-control" id="firstname" value="<?php echo $firstname ?>" placeholder="Vorname" maxlength="30" required="true">
                      </div>
                      <!-- Nachname -->
                      <div class="form-group">
                        <label for="lastname">Nachname *</label>
                        <input type="text" name="lastname" class="form-control" id="lastname" value="<?php echo $lastname ?>" placeholder="Nachnamen" maxlength="30" required="true">
                      </div>
                      <!-- Email -->
                      <div class="form-group">
                        <label for="lastname">Email *</label>
                        <input type="email" name="email" class="form-control" id="email" value="<?php echo $email ?>" placeholder="Email" maxlength="80" required="true">
                      </div>
                      <!-- Password -->
                      <div class="form-group">
                        <label for="lastname">Passwort *</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Passwort" pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="mindestens einen Gross-, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen, mindestens 8 Zeichen lang,keine Umlaute." maxlength="255" required="true">
                        <p style="color:darkgray">
                          *Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute
                        </p>
                      </div>
                      <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">Schliessen</button>
                        <button type="submit" class="btn btn-primary">Registrieren</button>
                      </div>
                    </form>

                  </div>

                </div>
              </div>
            </div>

            <!-- Login Modal -->
            <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel" style="color:#2e2e2e">Anmelden</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">

                    <!-- Login -->
                    <form action="" method="post">
                      <!-- benutzername -->
                      <div class="form-group">
                        <input type="text" name="email" class="form-control" id="email" value="<?php echo $email ?>" placeholder="Email" title="Email" maxlength="30" required="true">
                      </div>
                      <!-- password -->
                      <div class="form-group">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Passwort" title="Passwort" maxlength="255" required="true">
                      </div>

                  </div>
                  <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Anmelden</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <section class="features-icons bg-light text-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
            <div class="d-flex features-icons-icon"><i class="icon-screen-desktop m-auto text-primary" data-bs-hover-animate="pulse"></i></div>
            <h3>Freunde hinzufügen</h3>
            <p class="lead mb-0">Füge Freunde zu deiner Liste mit Name, Adresse und Bildern hinzu!</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
            <div class="d-flex features-icons-icon"><i class="icon-layers m-auto text-primary" data-bs-hover-animate="pulse"></i></div>
            <h3>Freunde Suchen</h3>
            <p class="lead mb-0">Suche deine bereits erfassten Freunde und schon hast du Daten zu deinem Freund!</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="mx-auto features-icons-item mb-5 mb-lg-0 mb-lg-3">
            <div class="d-flex features-icons-icon"><i class="icon-check m-auto text-primary" data-bs-hover-animate="pulse"></i></div>
            <h3>Persönliches Freundebuch</h3>
            <p class="lead mb-0">Ready to use with your own content, or customize the source files!</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="showcase">
    <div class="container-fluid p-0">
      <div class="row no-gutters">
        <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image:url(&quot;assets/img/bg-showcase-1.jpg&quot;);"><span></span></div>
        <div class="col-lg-6 my-auto order-lg-1 showcase-text">
          <h2>Fully Responsive Design</h2>
          <p class="lead mb-0">When you use a theme created with Bootstrap, you know that the theme will look great on any device, whether it's a phone, tablet, or desktop the page will behave responsively!</p>
        </div>
      </div>
      <div class="row no-gutters">
        <div class="col-lg-6 text-white showcase-img" style="background-image:url(&quot;assets/img/bg-showcase-2.jpg&quot;);"><span></span></div>
        <div class="col-lg-6 my-auto order-lg-1 showcase-text">
          <h2>Updated For Bootstrap 4</h2>
          <p class="lead mb-0">Newly improved, and full of great utility classes, Bootstrap 4 is leading the way in mobile responsive web development! All of the themes are now using Bootstrap 4!</p>
        </div>
      </div>
      <div class="row no-gutters">
        <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image:url(&quot;assets/img/bg-showcase-3.jpg&quot;);"><span></span></div>
        <div class="col-lg-6 my-auto order-lg-1 showcase-text">
          <h2>Easy to Use &amp;&nbsp;Customize</h2>
          <p class="lead mb-0">Landing Page is just HTML and CSS with a splash of SCSS for users who demand some deeper customization options. Out of the box, just add your content and images, and your new landing page will be ready to go!</p>
        </div>
      </div>
    </div>
  </section>

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