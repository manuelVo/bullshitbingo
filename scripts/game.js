bullshitbingoInitialized = false;

function switchState(event)
{
	id = event.target.id.substring(4);
	solved = $(event.target).hasClass("btn-success") ? 0 : 1;
	$.ajax("switchstate.php",{type:"POST",data:{id:id, solved:solved}});
}

function init()
{
	updateGameData();
}

function updateGameData()
{
	$.ajax("fetchgamedata.php",{dataType:"json"}).done(function(result) {
		if (bullshitbingoInitialized)
		{
			updateGrid(result);
		}
		else
		{
			createGrid(result);
			bullshitbingoInitialized = true;
		}
	});
	window.setTimeout(updateGameData, 1000);
}

function createGrid(gamedata)
{
	grid = $("#grid");
	for (y = 0;y < gamedata.grid.length;y++)
	{
		row = $("<tr>");
		for (x = 0;x < gamedata.grid[y].length;x++)
		{
			cell = $("<td>").css("width", (100 / gamedata.size) + "%");
			cell.append($("<button>").addClass("btn btn-lg btn-default").attr("id","word" + gamedata.grid[y][x].id).text(gamedata.grid[y][x].word).click(switchState));
			row.append(cell);
		}
		grid.append(row);
	}
}

function updateGrid(gamedata)
{
	grid = $("#grid");
	for (y = 0;y < gamedata.grid.length;y++)
	{
		for (x = 0;x < gamedata.grid[y].length;x++)
		{
			word = $("#word" + gamedata.grid[y][x].id);
			word.removeClass("btn-default btn-success btn-warning btn-danger");
			switch (gamedata.grid[y][x].solved)
			{
				case 0:
					word.addClass("btn-default");
					break;
				case 1:
					word.addClass("btn-success");
					break;
				case 2:
					word.addClass("btn-warning");
					break;
				default:
					word.addClass("btn-danger");
					break;
			}
		}
	}
}

$(window).load(init);
