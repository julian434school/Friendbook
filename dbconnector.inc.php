<?php 
    $host = 'localhost'; // host
    $username = 'techuser'; // username
    $password = 'techpass'; // password
    $database = 'friendbook_schema'; // database

    // mit Datenbank verbinden
    $mysqli = new mysqli($host, $username, $password, $database);

    // fehlermeldung, falls verbindung fehl schlägt.
    if ($mysqli->connect_error) {

        die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);

    }
?>