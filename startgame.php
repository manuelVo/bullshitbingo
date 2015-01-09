<?php 
require_once "init.php";

require_once "config.php";

if (!isset($_SESSION["gameid"]))
	exit;

$mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);

$stmt = $mysqli->prepare("SELECT COUNT(wordid) AS count FROM assigned_words WHERE gameid = ?");
$stmt->bind_param("i", $_SESSION["gameid"]);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt = $mysqli->prepare("UPDATE games SET started = 1 WHERE id = ? AND admin = ? AND size * size <= ?;");
$stmt->bind_param("iii", $_SESSION["gameid"], $_SESSION["id"], $row["count"]);
$stmt->execute();

?>