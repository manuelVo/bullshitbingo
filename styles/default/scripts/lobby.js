function createGame()
{
	gameSelectionForm = $("#gameselection");
	gameSelectionForm.attr("action", "joingame.php?mode=create");
	gameSelectionForm.submit();
}
