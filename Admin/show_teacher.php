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

$error = "";
$count = 0;

if (isset($_POST['lehrer'])) {
    $lehrer_list = $_POST['lehrer'];
    $lehrer_arr = explode( "\r\n", $lehrer_list);
    $lehrer_arr = array_filter($lehrer_arr, 'trim');

    foreach ($lehrer_arr as $add_lehrer){
        $add_data = explode(" ", $add_lehrer);
        
        if (count($add_data) != 3){
            $error = $error . "\r\n" . $add_lehrer;
        }
        else{

            $add_name = strval($add_data[0]);
            $add_name2 = strval($add_data[1]);
            $add_email = strval($add_data[2]);


            if(checkUser($add_email)) $error = $error . "\r\n" . $add_name . " " . $add_name2 . " (email already in db)";
            else if(!filter_var($add_email, FILTER_VALIDATE_EMAIL)) $error = $error . "\r\n" . $add_lehrer;
            else{
                addLehrer($add_name, $add_name2, $add_email);
                $count += 1;
            }
            
        }
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

    <style>
        .demo-list-control {
            width: 300px;
        }
        
        .page-content {
            width: auto;
            max-width: 50%;
            margin: auto auto;
            margin-left: 40%;


        }
        
        .demo-card-wide.mdl-card {
            margin: auto auto;
            width: 500px;
            width: auto;
            margin-top: auto;
        }
        
        .mdl-card {
            width: auto;
            margin: auto;
        }
        
        .demo-card-wide>.mdl-card__title {
            color: #fff;
            height: 176px;
            background: #202B40;
        }
        
        .demo-card-wide>.mdl-card__menu {
            color: #fff;
        }

        .mdl-button {
            margin-bottom: 10px;
            font-size: 22px;


            
        }

        .student_name {
            font-size: 22px;
            display: inline-block;
            float: left;
            margin-right: 10px;

        }


        .delete_student {
            color: red;
            font-size: 16px;
            margin-right: 10px;
            
        }


        .title {
            color: black;
            font-size: 25px;
            text-decoration: underline black; 
            margin-top: 70px;
        }

        .link {
            color: red;
            font-size: 25px;
            text-decoration: underline black; 
        }

        .add_student {
            margin-bottom: 25px       
        }


        .mdl-button{
            margin-left: 60px;
            margin-top: 30px;
        }

        


    </style>

    </head>

    <body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <!-- Title -->
                <span class="mdl-layout-title">Transparentes Bewertungssystem</span>
                <!-- Add spacer, to align navigation to the right -->
                <div class="mdl-layout-spacer"></div>
                <!-- Navigation. We hide it in small screens. -->
                <nav class="mdl-navigation mdl-layout--large-screen-only">
                    <a class="mdl-navigation__link" href="index.php">Home</a>
                    <a class="mdl-navigation__link" href="show_student.php">Schülerverwaltung</a>
                </nav>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">TBS</span>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="index.php">Home</a>
                <a class="mdl-navigation__link" href="show_student.php">Schülerverwaltung</a>
                <a class="mdl-navigation__link" href="../includes/logout.php">Logout</a>
            </nav>
        </div>

        <main class="mdl-layout__content">
            <div class="page-content">
                <a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href="teacher_table.php">Implementierte Lehrer</a>
       

                <h3 class="title">Lehrer Hinzufügen</h3>
                <?php 
                        
                        if(isset($_GET["add_error"])){
                            if($_GET["add_error"] == "empty") echo '<p style="color:red;">Es müssen alle Felder ausgefüllt sein!</p>';
                            if($_GET["add_error"] == "email") echo '<p style="color:red;">Die Email adresse ist nicht korrekt!</p>';
                            if($_GET["add_error"] == "double") echo '<p style="color:red;">Die email existiert bereits in der db!</p>';
                            if($_GET["add_error"] == "done") echo '<p style="color:green;">Der Lehrer wurde hinzugefügt!</p>';
                        }

                ?>
                <form class="add_student" action="addTeacher.php" method="post">
                    <input type="text" placeholder="Name" name="name">
                    <input type="text" placeholder="Nachname" name="name2">
                    <input type="text" placeholder="Email" name="email">
                    <button type="submit" name="id-submit">Hinzufügen</button>
                </form>

                <h3 class="title">Mehrere Lehrer Hinzufügen</h3>

                <?php
                
                if($count != 0){
                    echo '<p style="color:green;">' . $count . ' Lehrer hinzugefügt!</p>';
                }

                ?>

                <form method="post" action="">
                    <textarea style="resize:both;" name="lehrer" rows="12" cols="45" placeholder="Name Nachname Email &#10;Name Nachname Email"><?php if($error != "") echo $error;?></textarea>
                    <br/>
                    <input type="submit">
                </form>
            </div>
        </main>
    </body>

</html>

<?php

function addLehrer($t_name, $t_name2, $t_email){
    require "../includes/db.php";
    
    $stmt = mysqli_stmt_init($conn);

    $endname = $t_name2 . "." . $t_name;
    
    $sql = "INSERT INTO lehrer (Name, Email) VALUES (?, ?);";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $endname, $t_email);
    mysqli_stmt_execute($stmt);
}

function checkUser($email){
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