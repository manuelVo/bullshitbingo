<?php
require_once "init.php";

require_once "config.php";

if (!isset($_SESSION["gameid"]) || !isset($_POST["id"]) || !isset($_POST["solved"]))
	exit;
if ($_POST["solved"] != 1 && $_POST["solved"] != 0)
	exit;

$mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);

$stmt = $mysqli->prepare("UPDATE positioned_words SET solved = ? WHERE wordid = ? AND playerid = ?");
$stmt->bind_param("iii", $_POST["solved"], $_POST["id"], $_SESSION["id"]);
$stmt->execute();

?>