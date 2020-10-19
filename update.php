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

$updateStatement = "UPDATE friend SET name=?, fname=?, sex=?, street=?, city=?, plz=?, tel=?, email=?, profilepic=? WHERE f_id=?";
$stmt = $mysqli->prepare($updateStatement);
$stmt->bind_param("sssssisssbi", $fname,  $fname, $sex, $street, $city, $plz, $tel, $email, $profilepic, $f_id);
$stmt->execute();
$stmt->close();
header("Location: main.php");
?>
