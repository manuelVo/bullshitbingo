<?php
	$template->addStyleFile("style");
	if ($developmode)
		$template->addScriptFile("jquery-2.1.1");
	else
		$template->addScriptFile("jquery-2.1.1.min");
	$template->setDevelopMode($developmode);
?>