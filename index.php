<?php

//Datenbankverbindung
include('dbconnector.inc.php');

// Initialisierung
$error = $message =  '';
$firstname = $lastname = $email = $username = '';

// Wurden Daten mit "POST" gesendet?
if($_SERVER['REQUEST_METHOD'] == "POST"){
  // Ausgabe des gesamten $_POST Arrays
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";

  // vorname ausgefüllt?
  if(isset($_POST['firstname'])){
    //trim and sanitize
    $firstname = trim(htmlspecialchars($_POST['firstname']));
    
    //mindestens 1 Zeichen und maximal 30 Zeichen lang
    if(empty($firstname) || strlen($firstname) > 30){
      $error .= "Geben Sie bitte einen korrekten Vornamen ein.<br />";
    }
  } else {
    $error.= "Geben Sie bitte einen Vornamen ein.<br />";
  }

  // nachname ausgefüllt?
  if(isset($_POST['lastname'])){
    //trim and sanitize
    $lastname = trim(htmlspecialchars($_POST['lastname']));
    
    //mindestens 1 Zeichen und maximal 30 Zeichen lang
    if(empty($lastname) || strlen($lastname) > 30){
      $error .= "Geben Sie bitte einen korrekten Nachname ein.<br />";
    }
  } else {
    $error.= "Geben Sie bitte einen Nachname ein.<br />";
  }
  
  // email ausgefüllt?
  if(isset($_POST['email'])){
    //trim
    $email = trim($_POST['email']);
    
    //mindestens 1 Zeichen und maximal 100 Zeichen lang, gültige Emailadresse
    if(empty($email) || strlen($email) > 100 || filter_var($email, FILTER_VALIDATE_EMAIL) === false){
      $error .= "Geben Sie bitte eine korrekten Emailadresse ein.<br />";
    }
  } else {
    $error.= "Geben Sie bitte eine Emailadresse ein.<br />";
  }

  // username ausgefüllt?
  if(isset($_POST['username'])){
    //trim and sanitize
    $username = trim($_POST['username']);
    
    //mindestens 1 Zeichen , entsprich RegEX
    if(empty($username) || !preg_match("/(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,30}/", $username)){
      $error .= "Geben Sie bitte einen korrekten Usernamen ein.<br />";
    }
  } else {
    $error.= "Geben Sie bitte einen Username ein.<br />";
  }

  // passwort ausgefüllt
  if(isset($_POST['password'])){
    //trim and sanitize
    $password = trim($_POST['password']);
    
    //mindestens 1 Zeichen , entsprich RegEX
    if(empty($password) || !preg_match("/(?=^.{8,255}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/", $password)){
      $error .= "Geben Sie bitte einen korrektes Password ein.<br />";
    }
  } else {
    $error.= "Geben Sie bitte ein Password ein.<br />";
  }

  // wenn kein Fehler vorhanden ist, schreiben der Daten in die Datenbank
  if(empty($error)){
    // INPUT Query erstellen, welches firstname, lastname, username, password, email in die Datenbank schreibt
    $insertStatement = "INSERT into users(firstname, lastname, username, password, email) VALUES (?, ?, ?, ?, ?)";
    // Query vorbereiten mit prepare();
    $stmt = $mysqli->prepare($insertStatement);
    // Parameter an Query binden mit bind_param();
    $password = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("sssss", $firstname, $lastname, $username, $password, $email);
    // query ausführen mit execute();
    $stmt->execute();
    // Verbindung schliessen
    $stmt->close();
    // Weiterleitung auf login.php
    header("Location: login.php");
  }
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
        <div class="container"><a class="navbar-brand" href="#" style="font-size: 40px;color: rgba(30,202,239,0.9);font-family: Lato, sans-serif;height: 70px;border-width: 5px;border-style: none;box-shadow: 7px 7px 0px 0px rgba(53,156,179,0.9);width: 208px;padding: 0px;margin: 15px;">Friendbook</a>
            <button
                data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"></button>
                <div class="collapse navbar-collapse" id="navcol-1"><a class="btn btn-primary ml-auto" role="button" href="#">Einloggen</a></div>
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
                    <form>
                        <div class="form-row justify-content-center align-items-center">
                            <div class="col-12 col-md-3" style="text-align: center;"><button class="btn btn-primary btn-block btn-lg" type="submit" style="text-align: center;">Anmelden</button></div>
                            <div class="col-12 col-md-3" style="text-align: center;"><button class="btn btn-primary btn-block btn-lg" type="submit" style="text-align: center;">Einloggen</button></div>
                            <div class="clearfix"></div>
                        </div>
                    </form>
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
    <section class="testimonials text-center bg-light">
        <div class="container">
            <h2 class="mb-5">What people are saying...</h2>
            <div class="row">
                <div class="col-lg-4">
                    <div class="mx-auto testimonial-item mb-5 mb-lg-0"><img class="rounded-circle img-fluid mb-3" src="assets/img/testimonials-1.jpg">
                        <h5>Margaret E.</h5>
                        <p class="font-weight-light mb-0">"This is fantastic! Thanks so much guys!"</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mx-auto testimonial-item mb-5 mb-lg-0"><img class="rounded-circle img-fluid mb-3" src="assets/img/testimonials-2.jpg">
                        <h5>Fred S.</h5>
                        <p class="font-weight-light mb-0">"Bootstrap is amazing. I've been using it to create lots of super nice landing pages."</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mx-auto testimonial-item mb-5 mb-lg-0"><img class="rounded-circle img-fluid mb-3" src="assets/img/testimonials-3.jpg">
                        <h5>Sarah W.</h5>
                        <p class="font-weight-light mb-0">"Thanks so much for making these free resources available to us!"</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="call-to-action text-white text-center" style="background:url(&quot;assets/img/bg-masthead.jpg&quot;) no-repeat center center;background-size:cover;">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <h2 class="mb-4">Ready to get started? Sign up now!</h2>
                </div>
                <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                    <form>
                        <div class="form-row">
                            <div class="col-12 col-md-9 mb-2 mb-md-0"><input class="form-control form-control-lg" type="email" placeholder="Enter your email..."></div>
                            <div class="col-12 col-md-3"><button class="btn btn-primary btn-block btn-lg" type="submit">Sign up!</button></div>
                        </div>
                    </form>
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

    <!-- Registration -->
        <form action="" method="post">
        <!-- vorname -->
        <div class="form-group">
          <label for="firstname">Vorname *</label>
          <input type="text" name="firstname" class="form-control" id="firstname"
            value="<?php echo $firstname ?>"
            placeholder="Geben Sie Ihren Vornamen an."
            maxlength="30"
            required="true">
        </div>
        <!-- nachname -->
        <div class="form-group">
          <label for="lastname">Nachname *</label>
          <input type="text" name="lastname" class="form-control" id="lastname"
            value="<?php echo $lastname ?>"
            placeholder="Geben Sie Ihren Nachnamen an"
            maxlength="30"
            required="true">
        </div>
        <!-- email -->
        <div class="form-group">
          <label for="email">Email *</label>
          <input type="email" name="email" class="form-control" id="email"
            value="<?php echo $email ?>"
            placeholder="Geben Sie Ihre Email-Adresse an."
            maxlength="100"
            required="true">
        </div>
        <!-- benutzername -->
        <div class="form-group">
          <label for="username">Benutzername *</label>
          <input type="text" name="username" class="form-control" id="username"
            value="<?php echo $username ?>"
            placeholder="Gross- und Kleinbuchstaben, min 6 Zeichen."
            pattern="(?=.*[a-z])(?=.*[A-Z])[a-zA-Z]{6,}"
            title="Gross- und Keinbuchstaben, min 6 Zeichen."
            maxlength="30" 
            required="true">
        </div>
        <!-- password -->
        <div class="form-group">
          <label for="password">Password *</label>
          <input type="password" name="password" class="form-control" id="password"
            placeholder="Gross- und Kleinbuchstaben, Zahlen, Sonderzeichen, min. 8 Zeichen, keine Umlaute"
            pattern="(?=^.{8,}$)((?=.*\d+)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"
            title="mindestens einen Gross-, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen, mindestens 8 Zeichen lang,keine Umlaute."
            maxlength="255"
            required="true">
        </div>
        <button type="submit" name="button" value="submit" class="btn btn-info">Senden</button>
        <button type="reset" name="button" value="reset" class="btn btn-warning">Löschen</button>
      </form>

    <!-- Login -->
        <form action="" method="post">
            <!-- benutzername -->
            <div class="form-group">
                <label for="username">Benutzername</label>
                <input type="text" name="username" class="form-control" id="username"
                value="<?php echo $username ?>"
                placeholder="Benutzername"
                title="Benutzername"
                maxlength="30" 
                required="true">
            </div>
            <!-- password -->
            <div class="form-group">
                <label for="password">Passwort</label>
                <input type="password" name="password" class="form-control" id="password"
                placeholder="Passwort"
                title="Passwort"
                maxlength="255"
                required="true">
            </div>
        </form>

        </div>

    </footer>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
</body>

</html>