<?php
require_once "init.php";

require_once "config.php";

if (!isset($_SESSION["gameid"]))
	exit;

$mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);

$stmt = $mysqli->prepare("SELECT size FROM games WHERE id = ?;");
$stmt->bind_param("i", $_SESSION["gameid"]);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();

$stmt = $mysqli->prepare("SELECT wordid, solved FROM positioned_words WHERE playerid = ?;");
$stmt->bind_param("i", $_SESSION["id"]);
$stmt->execute();
$result = $stmt->get_result();

$solved = array();
while ($row = $result->fetch_assoc())
{
	$solved[$row["wordid"]] = $row["solved"];
}

$stmt = $mysqli->prepare("SELECT wordid FROM positioned_words WHERE playerid IN (SELECT id FROM players WHERE gameid = ?) AND wordid NOT IN (SELECT wordid FROM positioned_words WHERE playerid = ? AND solved = 1) AND solved = 1 GROUP BY wordid;");
$stmt->bind_param("ii", $_SESSION["gameid"], $_SESSION["id"]);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc())
{
	$solved[$row["wordid"]] = 2;
}

$stmt = $mysqli->prepare("SELECT p.wordid AS wordid, w.word AS word FROM positioned_words p, words w WHERE p.wordid = w.id AND playerid = ? ORDER BY position;");
$stmt->bind_param("i", $_SESSION["id"]);
$stmt->execute();
$result = $stmt->get_result();

$json = '{"size":'.$game["size"].',"grid":[';
$count = 0;
while ($row = $result->fetch_assoc())
{
	if ($count % $game["size"] == 0)
	{
		if ($count > 0)
			$json .= '],';
		$json .= '[';
	}
	else
	{
		$json .= ',';
	}
	$json .= '{"id":'.$row["wordid"].',"word":"'.$row["word"].'","solved":'.$solved[$row["wordid"]].'}';
	$count++;
}
$json .= ']';

$json .= ']}';

echo $json;

?>