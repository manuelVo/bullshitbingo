<?php
require_once "init.php";

require_once "config.php";

if (!isset($_SESSION["gameid"]))
	exit;

$mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
$stmt = $mysqli->prepare("SELECT id, word FROM words WHERE id IN (SELECT wordid FROM assigned_words WHERE gameid = ?);");

$stmt->bind_param("i", $_SESSION["gameid"]);
$stmt->execute();
$result = $stmt->get_result();

$json = '{"words":[';
$first = true;

$wordcount = $result->num_rows;
while ($row = $result->fetch_assoc())
{
	if ($first)
		$first = false;
	else
		$json .= ',';
	$json .= '{"id":'.$row["id"].',"word":"'.$row["word"].'"}';
}

$json .= ']';

$stmt = $mysqli->prepare("SELECT started, admin, size FROM games WHERE id = ?;");
$stmt->bind_param("i", $_SESSION["gameid"]);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$json .= ',"started":'.($row["started"] ? "true" : "false");
$json .= ',"isAdmin":'.($row["admin"] == $_SESSION["id"] ? "true" : "false");
$json .= ',"noWords":'.$wordcount;
$json .= ',"maxWords":'.($row["size"] * $row["size"]);

$json .= '}';

echo $json;

?>