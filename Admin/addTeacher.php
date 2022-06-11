<?php

session_start();

if(isset($_SESSION["Perm"])){
   if($_SESSION["Perm"] != "admin"){
    header("Location: ../index.php");
   } 
}
else{
    header("Location: ../index.php");
}


$name = "/";
$name2 = "/";
$email = "/";

if(isset($_POST["name"]) && isset($_POST["name2"]) && isset($_POST["email"])){
    if(empty($_POST["name"]) || empty($_POST["name2"]) || empty($_POST["email"])){
        header("Location: show_teacher.php?add_error=empty");
        exit();
    }
    else{
        
        if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            header("Location: show_teacher.php?add_error=email");
            exit();
        }

        if(ExistUser($_POST["email"])){
            header("Location: show_teacher.php?add_error=double");
            exit();
        }

        $name = $_POST["name2"] . "." . $_POST["name"];
        $email = $_POST["email"];
        $stufe = substr($klasse, 0, -1);

        addSchueler($name, $email);
        header("Location: show_teacher.php?add_error=done");
        exit();
    }
}
else{
    header("Location: show_teacher.php?add_error=empty");
    exit();
}




function addSchueler($t_name, $t_email){
    require "../includes/db.php";
    
    $stmt = mysqli_stmt_init($conn);
    
    $sql = "INSERT INTO lehrer (Name, Email) VALUES (?, ?);";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $t_name, $t_email);
    mysqli_stmt_execute($stmt);
}

function ExistUser($email){
    require "../includes/db.php";

	$stmt = mysqli_stmt_init($conn);
	$sql = "select * from lehrer where Email=?";
	
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "s", $email);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)){
		return true;
	}
	else{
		return false;
    }
}
?>