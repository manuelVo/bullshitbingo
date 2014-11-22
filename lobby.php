<?php
require_once "init.php";

require_once "config.php";

require_once "template.php";

$template = new Template("lobby", "default", "default");

$template->setVariable("PAGE_TITLE", "Bullshitbingo - Lobby");

include "overall.php";

$mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
$result = $mysqli->query("SELECT g.id as id, g.name as name, p.name as admin FROM games g, players p WHERE g.admin = p.id AND g.started = 0;");

while ($row = $result->fetch_assoc())
{
	$template->addLineVars("GAMES", array("ID" => $row["id"], "NAME" => $row["name"], "ADMIN" => $row["admin"]));
}

$template->sendPage();
?>