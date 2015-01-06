<?php
require_once "init.php";

require_once "config.php";

$mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
$result = $mysqli->query("SELECT g.id as id, g.name as name, p.name as admin FROM games g, players p WHERE g.admin = p.id AND g.started = 0;");

$json = '{"games" : [';

$first = true;

while ($row = $result->fetch_assoc())
{
	if ($first)
		$first = false;
	else
		$json .= ",";
	$json .= '{"id":"'.$row["id"].'","name":"'.$row["name"].'","admin":"'.$row["admin"].'"}';
}

$json .= "]}";

echo $json;
?>