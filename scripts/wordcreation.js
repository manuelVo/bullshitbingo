function addWord()
{
	word = $("#word").val();
	if (word == "")
		return;
	$("#word").val("");
	$.ajax("addword.php",{type:"POST",data:{word:word}});
}

function deleteWord(event)
{
	delid = $(event.target.parentNode).data("id");
	$.ajax("deleteword.php",{type:"POST",data:{id:delid}});
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
			element = $("<li>").addClass("list-group-item").data("id", result.words[key].id).append(result.words[key].word);
			element.append($("<span>").addClass("glyphicon glyphicon-remove removeicon").attr("aria-hidden", "true").click(deleteWord));
			words.append(element);
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
