<?php

require_once "init.php";

require_once "config.php";

if (!isset($_SESSION["gameid"]))
{
	header("Location: lobby.php");
	exit;
}

$mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $dbname);
$stmt = $mysqli->prepare("SELECT started FROM games WHERE id = ?;");
$stmt->bind_param("i", $_SESSION["gameid"]);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if ($row === false)
{
	unset($_SESSION["gameid"]);
	header("Location: lobby.php");
	exit;
}

if ($row["started"])
{
	header("Location: game.php");
	exit;
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Bullshitbingo - Wordcreation</title>

		<!-- Bootstrap core CSS -->
		<link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="styles/theme.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body role="document">

		<!-- Fixed navbar -->
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
			<div class="navbar-header">
				<!--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>-->
				<a class="navbar-brand" href="#">Bullshitbingo</a>
			</div>
		</div>
	</nav>

	<div class="container theme-showcase" role="main">
		<div class="row">
			<ul class="list-group" id="words">
			</ul>
		</div>
		<div class="row" id="nowords">
			
		</div>
		<div class="row">
			<input type="text" name="word" placeholder="Wort" id="word" />
			<button type="button" class="btn btn-default" id="addWord">Hinzufügen</button>
		</div>
		<div class="row">
			<button type="button" class="btn btn-default hidden" id="start">Spiel starten</button>
		</div>
	</div>
   
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="scripts/jquery-2.1.3.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="bootstrap/assets/js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="bootstrap/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script type="text/javascript" src="scripts/wordcreation.js"></script>
  </body>
</html>
