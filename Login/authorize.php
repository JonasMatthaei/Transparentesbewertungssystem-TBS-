<script>
url = window.location.href;
i=url.indexOf("#");
if(i>0) {
	url=url.replace("#","?");
	window.location.href=url;
}
</script>

<?php

session_start();

if(isset($_GET["login"])){
	require "oauth.php";
	
	if($_GET["login"] == "token"){
		header('Location: ' . $login_url . '?client_id=' . $clientId . '&response_type=token&redirect_uri=' . $redirect . '&scope=user.read&response_mode=fragment');
	}
	else if($_GET["login"] == "logout"){
		unset($_SESSION["email"]);
		require "oauth.php";
		header('Location: ' . $logout_url . "?post_logout_redirect_uri=https://www.google.com/");
	}
}
else if(isset($_GET["access_token"])){
	require "oauth.php";
	
	$opts = array(
	  'http'=>array(
		'method'=>"GET",
		'header'=>"Authorization: Bearer " . $_GET["access_token"] . "\r\n" .
				  "Host: graph.microsoft.com\r\n"
	  )
	);
	
	$context = stream_context_create($opts);
	$data = file_get_contents("https://graph.microsoft.com/v1.0/me", False, $context);
	$obj = json_decode($data);
	$email = $obj->{"userPrincipalName"};
	
	require "../includes/db.php";
	echo $email;

	if(checkUser($conn, $email)){
		echo "Schüler";
		echo $_SESSION["Name"] . " " . $_SESSION["Klasse"];
	}
	else{
		if(checkLehrer($conn, $email)){
			echo "Lehrer";
			echo $_SESSION["Name"];
		}
		else{
			header("Location: ../index.php?error=notfound");
			exit();
		}
	}

	header("Location: ../index.php");
	exit();
}

function checkLehrer($conn, $email){
	$stmt = mysqli_stmt_init($conn);
	$sql = "select * from lehrer where Email=?";
	
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "s", $email);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)){
		$_SESSION["Name"] = $row["Name"];
		$_SESSION["ID"] = $row["Lehrer_ID"];

		$_SESSION["Perm"] = "lehrer";

		return true;
	}
	else{
		return false;
	}
}

function checkUser($conn, $email){
	$stmt = mysqli_stmt_init($conn);
	$sql = "select * from schueler where Email=?";
	
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "s", $email);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)){
		$_SESSION["Name"] = $row["Name"];
		$_SESSION["Klasse"] = $row["Klasse"];
		$_SESSION["ID"] = $row["Schueler_ID"];

		$_SESSION["Perm"] = "schueler";

		return true;
	}
	else{
		return false;
	}

	//while ($row = mysqli_fetch_array($result)){}
}


?>