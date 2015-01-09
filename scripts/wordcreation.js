function addWord()
{
	word = $("#word").val();
	$("#word").val("");
	$.ajax("addword.php",{type:"POST",data:{word:word}});
}

function start()
{
	$.ajax("startgame.php");
}

function init()
{
	$("#word").keypress(function(e) {
		if (e.which == 13) // Enter button
			addWord();
	});
	$("#addWord").click(addWord);
	$("#start").click(start);
	updateWordList();
}

function updateWordList()
{
	$.ajax("fetchwords.php",{dataType:"json"}).done(function(result) {
		words = $("#words");
		words.empty();
		for (key in result.words)
		{
			words.append($("<li>").addClass("list-group-item").data("id", result.words[key].id).append(result.words[key].word));
		}
		if (result.isAdmin)
		{
			$("#start").removeClass("hidden");
		}
		if (result.started)
		{
			window.location = "game.php";
		}
		$("#nowords").text(result.noWords + " von " + result.maxWords);
	});
	window.setTimeout(updateWordList, 1000);
}

$(window).load(init);
