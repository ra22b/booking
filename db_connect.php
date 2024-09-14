<?php

$err_message = "";
$message = "";

define("DBHOST", "localhost");
define("DBUSER", "root");
define("DBPASSWORD", "");
define("DBNAME", "doctor");
define("DSN", "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=UTF8");

try {
    $pdo = new PDO(DSN, DBUSER, DBPASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
    echo "Connection Error :" . $ex->getMessage();
}
?>