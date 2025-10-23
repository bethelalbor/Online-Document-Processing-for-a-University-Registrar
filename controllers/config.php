<?php
$mysqli = new mysqli("localhost", "root", "", "document_request_system");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
