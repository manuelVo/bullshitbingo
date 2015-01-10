<?php
require_once "init.php";

require_once "config.php";

if (!isset($_SESSION["gameid"]) || !isset($_POST["id"]))
	exit;

$mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);

$stmt = $mysqli->prepare("DELETE FROM assigned_words WHERE gameid = ? AND wordid = ?");
$stmt->bind_param("ii", $_SESSION["gameid"], $_POST["id"]);
$stmt->execute();

$stmt = $mysqli->prepare("DELETE FROM words WHERE id = ?;");
$stmt->bind_param("i", $_POST["id"]);
$stmt->execute();
?>