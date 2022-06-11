<?php

session_start();

unset($_SESSION["Name"]);
unset($_SESSION["ID"]);
unset($_SESSION["Klasse"]);
unset($_SESSION["ID"]);
unset($_SESSION["Perm"]);

session_unset();
session_destroy();

require "../Login/oauth.php";
header("Location: " . $logout_url);