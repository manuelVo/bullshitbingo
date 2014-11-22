<?php
require_once "init.php";

if (isset($_SESSION["gameid"]))
	header("Location: game.php");
else
	header("Location: lobby.php");
?>
