<?php
require_once "init.php";

require_once "config.php";

if (!isset($_SESSION["gameid"]) || !isset($_POST["word"]))
	die("no word or game");

$mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);

$mysqli->autocommit(false);

$stmt = $mysqli->prepare("INSERT INTO words (word) VALUES (?);");
$stmt->bind_param("s", $_POST["word"]);
$stmt->execute();
$wordid = $stmt->insert_id;

$stmt = $mysqli->prepare("INSERT INTO assigned_words (wordid, gameid) VALUES (?,?);");
$stmt->bind_param("ii", $wordid, $_SESSION["gameid"]);
$stmt->execute();

$mysqli->commit();

?>