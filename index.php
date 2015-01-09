<?php
require_once "init.php";

if (isset($_SESSION["gameid"]))
	header("Location: wordcreation.php");
else
	header("Location: lobby.php");
?>
