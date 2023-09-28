<?php

function show($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

function error_message()
{
	if (isset($_SESSION['error']) && $_SESSION['error'] != "") {
		echo $_SESSION['error'];
		unset($_SESSION['error']);
	}
}

function esc($data)
{
	return addslashes($data);
}

