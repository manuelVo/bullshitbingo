function createGame()
{
	gameSelectionForm = $("#gameselection");
	gameSelectionForm.attr("action", "joingame.php?mode=create");
	gameSelectionForm.submit();
}

function joinGame()
{
	selectedGameId = $("#games > li.active").data("id");
	if (selectedGameId == undefined)
		return;
	$("#gameselection > input[name=gameid]").attr("value", selectedGameId);
	$("#gameselection").submit();
}

function selectGame()
{
	$("#games > li").each(function() {
		$(this).removeClass("active");
	});
	$(this).addClass("active");
}

function init()
{
	$("#create").click(createGame);
	$("#join").click(joinGame);
	updateGameList();
}

function updateGameList()
{
	$.ajax("fetchgames.php",{dataType:"json"}).done(function(result) {
		selectedGame = $("#games > li.active").data("id");
		games = $("#games");
		games.empty();
		for (key in result.games)
		{
			game = $("<li>").addClass("list-group-item").data("id", result.games[key].id).click(selectGame).append(result.games[key].name + " von " + result.games[key].admin);
			if (result.games[key].id == selectedGame)
				game.addClass("active");
			games.append(game);
		}
	});
	window.setTimeout(updateGameList, 1000);
}

$(window).load(init);
