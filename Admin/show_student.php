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

$data = "/";

$jahrgang = "/";
$klasse = "/";

$del_mode = "/";
$del_content = "/";
$del_accept = "/";

if(isset($_GET["jahrgang"])) $jahrgang = $_GET["jahrgang"];
if(isset($_GET["klasse"])) $klasse = $_GET["klasse"];

if(isset($_GET["del"])) $del_mode = $_GET["del"];
if(isset($_GET["del_id"])) $del_content = $_GET["del_id"];
if(isset($_GET["del_accept"])) $del_accept = $_GET["del_accept"];

if($del_mode == "schueler" && $del_accept == "true"){
    delSchueler($del_content);
}

if($del_mode == "klasse" && $del_accept == "true"){
    delKlasse($del_content);
}

if($del_mode == "jahrgang" && $del_accept == "true"){
    delJahrgang($del_content);
}


$error = "";
$count = 0;

if (isset($_POST['schueler'])) {
    $schueler_list = $_POST['schueler'];
    $schueler_arr = explode( "\r\n", $schueler_list);
    $schueler_arr = array_filter($schueler_arr, 'trim');

    foreach ($schueler_arr as $add_schueler){
        $add_data = explode(" ", $add_schueler);
        
        if (count($add_data) != 4){
            $error = $error . "\r\n" . $add_schueler;
        }
        else{

            $add_name = strval($add_data[0]);
            $add_name2 = strval($add_data[1]);
            $add_email = strval($add_data[2]);
            $add_klasse = strval($add_data[3]);
            $add_stufe = substr($add_klasse, 0, -1);

            $jahrgang = "/";
            $klasse = "/";


            if(checkUser($add_email)) $error = $error . "\r\n" . $add_name . " " . $add_name2 . " (email already in db)";
            else if(!filter_var($add_email, FILTER_VALIDATE_EMAIL)) $error = $error . "\r\n" . $add_schueler;
            else{
                addSchueler($add_name, $add_name2, $add_email, $add_klasse, $add_stufe);
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
        }

        .add_student {
            margin-bottom: 25px       
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
                    <a class="mdl-navigation__link" href="show_teacher.php">Lehrerverwaltung</a>
                </nav>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">TBS</span>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="index.php">Home</a>
                <a class="mdl-navigation__link" href="show_teacher.php">Lehrerverwaltung</a>
                <a class="mdl-navigation__link" href="../includes/logout.php">Logout</a>
            </nav>
        </div>

        <main class="mdl-layout__content">
            <div class="page-content">
                <h3 class="title">Implementierte Stufen</h3>
                <?php
                
                $data = getData($jahrgang, $klasse, $del_mode, $del_content, $del_accept);
                
                if($jahrgang != "/" && $klasse != "/"){
                    echo '<a class="mdl-button mdl-js-button mdl-button--raised"  href="show_student.php?jahrgang=' . $jahrgang . '">Zurück</a></br>';
                }
                else if($jahrgang != "/" && $klasse == "/"){
                    echo '<a class="mdl-button mdl-js-button mdl-button--raised" href="show_student.php">Zurück</a></br>';
                    
                }
                
                if($data != "/"){
                    echo $data;
                }
                else{
                    echo '<p style="color:red;">Fehler beim Abrufen der Tabelle!</p>';
                }

                ?>
                </br>
                <a href="show_student.php?end=false" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">Schuljahr beenden</a>

                <h3 class="title">Schüler Hinzufügen</h3>
                <?php 
                        
                        if(isset($_GET["add_error"])){
                            if($_GET["add_error"] == "empty") echo '<p style="color:red;">Es müssen alle Felder ausgefüllt sein!</p>';
                            if($_GET["add_error"] == "email") echo '<p style="color:red;">Die Email adresse ist nicht korrekt!</p>';
                            if($_GET["add_error"] == "double") echo '<p style="color:red;">Die email existiert bereits in der db!</p>';
                            if($_GET["add_error"] == "done") echo '<p style="color:green;">Der Schueler wurde hinzugefügt!</p>';
                        }

                ?>
                <form class="add_student" action="addSchueler.php" method="post">
                    <input type="text" placeholder="Name" name="name">
                    <input type="text" placeholder="Nachname" name="name2">
                    <input type="text" placeholder="Email" name="email">
                    <input type="text" placeholder="Klasse" name="klasse">
                    <button type="submit" name="id-submit">Hinzufügen</button>
                </form>

                <h3 class="title">Klasse/Stufe Hinzufügen</h3>

                <?php
                
                if($count != 0){
                    echo '<p style="color:green;">' . $count . ' Schüler hinzugefügt!</p>';
                }

                ?>
                <form method="post" action="show_student.php">
                    <textarea style="resize:both;" name="schueler" rows="12" cols="45" placeholder="Name Nachname Email Klasse &#10;Name Nachname Email Klasse"><?php if($error != "") echo $error;?></textarea>
                    <br/>
                    <input type="submit">
                </form>
            </div>
        </main>
    </body>
</html>

<?php

if(isset($_GET["end"])){
    if(!empty($_GET["end"])){
        if($_GET["end"] == "false"){
            echo '<script> var check = confirm("Mit dem Beenden des Schuljahres werden alle daten aus dem Jahr:' . ((int)date("Y") - 2) . ' und davor gelöscht. Zudem werden alle Schueler eine Klasse weiter gesetzt! Wollen sie das Jahr beenden?"); 
                    if (check == true) {
                        window.location = "show_student.php?end=true";
                    }</script>';
        }
        else{
            endJahr();
            echo '<script>window.location = "show_student.php";</script>';
            exit();
        }
        
    }   
}

function endJahr(){
    $t_jahr = (int)date("Y") - 2;
    $jahr = $t_jahr . "-01-01";
    
    delAnwesend($jahr);
    delBewertungen($jahr);
    levelSchueler();
}

function delAnwesend($datum){
    require "../includes/db.php";
    $stmt = mysqli_stmt_init($conn);

    $sql = 'delete FROM anwesenheit WHERE datum < "' . $datum . '";';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
}

function delBewertungen($datum){
    require "../includes/db.php";
    $stmt = mysqli_stmt_init($conn);

    $sql = 'delete FROM bewertungen WHERE datum < "' . $datum . '";';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
}

function levelSchueler(){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);

    $sql = "select * from schueler";

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	while ($row = mysqli_fetch_array($result)){
        $new_j = "";
        $new_k = "";

        if(strtolower($row["Jahrgang"]) != "s1"){
            if(is_numeric($row["Jahrgang"])){
                $new_j = strval((int)$row["Jahrgang"] + 1);
                $new_k = str_replace($row["Jahrgang"], $new_j, $row["Klasse"]);
            }
        }

        if($new_j != "" && $new_k != ""){
            $stmt = mysqli_stmt_init($conn);

            $sql = 'update schueler set Klasse="' . $new_k . '", Jahrgang="' . $new_j . '" WHERE Schueler_ID=' . $row["Schueler_ID"] . ';';

            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_execute($stmt);
        }
    }
}



function delSchueler($schueler_id){
    require "../includes/db.php";
    $stmt = mysqli_stmt_init($conn);

    $sql = 'delete from schueler where Schueler_ID="' . $schueler_id . '";';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
}

function delKlasse($t_jahrgang){
    require "../includes/db.php";
    $stmt = mysqli_stmt_init($conn);

    $sql = 'delete from schueler where Klasse="' . $t_jahrgang . '";';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
}

function delJahrgang($t_jahrgang){
    require "../includes/db.php";
    $stmt = mysqli_stmt_init($conn);

    $sql = 'delete from schueler where Jahrgang="' . $t_jahrgang . '";';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
}


function getData($tmp_j, $tmp_k, $tmp_del, $tmp_delID, $tmp_del_accept){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);

    $sql = "/";

    if($tmp_k != "/") $sql = 'select * from schueler where Klasse="' . $tmp_k . '" order by Nachname asc;';
    else if($tmp_j != "/") $sql = 'select distinct(Klasse) from schueler where jahrgang="' . $tmp_j . '" order by 1 asc;';
    else $sql = "select distinct(Jahrgang) from schueler order by 1 asc";

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

    $tmp_data = "";

	while ($row = mysqli_fetch_array($result)){
        if($tmp_k == "/"){
            if($tmp_j == "/"){
                if($tmp_del == "jahrgang" && $tmp_delID == $row[0] && $tmp_del_accept == "false"){
                    $tmp_data = $tmp_data . '<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="show_student.php?jahrgang=' . $row[0] . '">' . $row[0] . '</a><a class="delete_student" href="show_student.php?del=jahrgang&del_id=' . $row[0] . '&del_accept=true">Löschen</a><a class="delete_student" href="show_student.php">Abrechen</a></br>';
                }
                else{
                    $tmp_data = $tmp_data . '<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="show_student.php?jahrgang=' . $row[0] . '">' . $row[0] . '</a><a class="student_jahrgang" href="show_student.php?del=jahrgang&del_id=' . $row[0] . '&del_accept=false"><img src="Bilder/delete.png"></a></br>';
                }
                
            }
            else{
                if($tmp_del == "klasse" && $tmp_delID == $row[0] && $tmp_del_accept == "false"){
                    $tmp_data = $tmp_data . '<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="show_student.php?jahrgang=' . $tmp_j . '&klasse=' . $row[0] . '">' . $row[0] . '</a><a class="delete_student" href="show_student.php?jahrgang=' . $tmp_j . '&del=klasse&del_id=' . $row[0] . '&del_accept=true">Löschen</a><a class="delete_student" href="show_student.php?jahrgang=' . $tmp_j . '">Abrechen</a></br>';
                }
                else{
                    $tmp_data = $tmp_data . '<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="show_student.php?jahrgang=' . $tmp_j . '&klasse=' . $row[0] . '">' . $row[0] . '</a><a class="student_klasse" href="show_student.php?jahrgang=' . $tmp_j . '&del=klasse&del_id=' . $row[0] . '&del_accept=false"><img src="Bilder/delete.png"></a></br>';
                }
                
            }
        }
        else{
            if($tmp_del == "schueler" && $tmp_delID == $row[0] && $tmp_del_accept == "false"){
                $tmp_data = $tmp_data . '<p class="student_name">' . $row[2] . ' ' . $row[1] . '<p><a class="delete_student" href="show_student.php?klasse=' . $tmp_k . '&jahrgang=' . $tmp_j . '&del=schueler&del_id=' . $row[0] . '&del_accept=true">Löschen</a><a class="delete_student" href="show_student.php?klasse=' . $tmp_k . '&jahrgang=' . $tmp_j . '">Abrechen</a></br>';
            }
            else{
                $tmp_data = $tmp_data . '<p class="student_name">' . $row[2] . ' ' . $row[1] . '<p><a class="student_name" href="show_student.php?klasse=' . $tmp_k . '&jahrgang=' . $tmp_j . '&del=schueler&del_id=' . $row[0] . '&del_accept=false"><img src="Bilder/delete.png"></a></br>';
            }
            
        }
	}

    if($tmp_data == "") return "/";

	return $tmp_data;
}

function addSchueler($t_name, $t_name2, $t_email, $t_klasse, $t_stufe){
    require "../includes/db.php";
    
    $stmt = mysqli_stmt_init($conn);
    
    $sql = "INSERT INTO schueler (Name, Nachname, Email, Klasse, Jahrgang) VALUES (?, ?, ?, ?, ?);";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $t_name, $t_name2, $t_email, $t_klasse, $t_stufe);
    mysqli_stmt_execute($stmt);
}

function checkUser($email){
    require "../includes/db.php";

	$stmt = mysqli_stmt_init($conn);
	$sql = "select * from schueler where Email=?";
	
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


// 10
//  a
//   S1
//   S2
//  b
// 11
// 12


?>
