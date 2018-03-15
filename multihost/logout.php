<?php
	session_start();
	session_unset();
	session_destroy();
//	$_SESSION['logged_in'] = '';


	//unset $_SESSION['logged_in'];
	echo 'ok';
