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

$mysqli->autocommit(false);

$stmt = $mysqli->prepare("UPDATE games SET started = 1 WHERE id = ? AND admin = ? AND size * size = ?;");
$stmt->bind_param("iii", $_SESSION["gameid"], $_SESSION["id"], $row["count"]);
$stmt->execute();

if ($stmt->affected_rows == 0)
{
	exit;
}

$stmt = $mysqli->prepare("SELECT size FROM games WHERE id = ?;");
$stmt->bind_param("i", $_SESSION["gameid"]);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

$stmt = $mysqli->prepare("SELECT wordid FROM assigned_words WHERE gameid = ?;");
$stmt->bind_param("i", $_SESSION["gameid"]);
$stmt->execute();
$result = $stmt->get_result();

$words = array();
while ($row = $result->fetch_assoc())
{
	$words[] = $row["wordid"];
}

$stmt = $mysqli->prepare("SELECT id FROM players WHERE gameid = ?;");
$stmt->bind_param("i", $_SESSION["gameid"]);
$stmt->execute();
$result = $stmt->get_result();

$stmt = $mysqli->prepare("INSERT INTO positioned_words (playerid, wordid, position) VALUES (?,?,?);");
while ($row = $result->fetch_assoc())
{
	$playerwords = $words;
	shuffle($playerwords);
	for ($i = 0;$i < $game["size"] * $game["size"];$i++)
	{
		$stmt->bind_param("iii", $row["id"], $playerwords[$i], $i);
		$stmt->execute();
	}
}

$mysqli->commit();

?>