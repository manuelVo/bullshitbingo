<?php
require_once "init.php";

require_once "config.php";

if (!isset($_POST["name"]))
{
	header("Location: lobby.php");
	exit;
}
$name = $_POST["name"];
$mode = isset($_GET["mode"]) ? $_GET["mode"] : "join";

$mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
$mysqli->autocommit(false);
$mysqli->query("SET foreign_key_checks = 0;");

$gameid;

if ($mode == "create")
{
	if (!isset($_POST["gamename"]) || !isset($_POST["size"]))
	{
		header("Location: lobby.php");
		exit;
	}
	$stmt = $mysqli->prepare("INSERT INTO games (name, size) VALUES (?, ?);");
	$stmt->bind_param("si", trim(strip_tags($_POST["gamename"])), $_POST["size"]);
	$stmt->execute();
	$gameid = $stmt->insert_id;
}
else
{
	if (!isset($_POST["gameid"]))
	{
		header("Location: lobby.php");
		exit;
	}
	$gameid = $_POST["gameid"];
}

$stmt = $mysqli->prepare("INSERT INTO players (gameid, name) VALUES (?, ?);");
$stmt->bind_param("is", $gameid, trim(strip_tags($name)));
$stmt->execute();

$userid = $stmt->last_id;

if ($mode == "create")
{
	$stmt = $mysqli->prepare("UPDATE games SET admin = ? WHERE id = ?;");
	$stmt->bind_param("ii", $userid, $gameid);
	$stmt->execute();
}

$mysqli->query("SET foreign_key_checks = 1;");

if ($mysqli->commit())
{
	$_SESSION["id"] = $userid;
	$_SESSION["gameid"] = $gameid;
	header("Location: game.php");
}
else
{
	header("Location: lobby.php");
}
