<?php
	ini_set('session.gc_maxlifetime', 36000);
	session_set_cookie_params(36000);
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	// TODO: Add mechanism to remove games which are no logner used
?>