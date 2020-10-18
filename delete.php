<?php

//Datenbankverbindung
include('dbconnector.inc.php');

// Sessionhandling
session_start();
session_regenerate_id();

// Have this block to verify if the user is logged in the session in every file, where the user must be logged in
if (isset($_SESSION['loggedin']) && isset($_SESSION['email'])) {
} else {
    header("Location: index.php");
    die();
}
$f_id = $_GET['f_id'];

$deleteStatement = "DELETE FROM friend WHERE f_id=?";
$stmt = $mysqli->prepare($deleteStatement);
$stmt->bind_param("i", $f_id);
$stmt->execute();
$stmt->close();
// header("Location: main.php");
?>
