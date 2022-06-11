<?php

session_start();

if(isset($_SESSION["Perm"])){
    if($_SESSION["Perm"] != "lehrer"){
        header("Location: ../index.php");
    }
}
else{
    header("Location: ../index.php");
    exit();
}

//import section
require "../includes/Encrypt_Decrypt.php";

if(isset($_GET["kurs"])){
    if(empty($_GET["kurs"])){
        header("Location: class_inspection_overview.php");
        exit();
    }
    else{
        if(!(checkKurs($_GET["kurs"]))){
            header("Location: class_inspection_overview.php");
            exit();
        }
    }
}
else{
    header("Location: class_inspection_overview.php");
    exit();
}

if(empty($_COOKIE["wertung"]) && empty($_COOKIE["anwesend"])){
    preloadWertung();
    preloadAnwesend();
    header("Location: Student_rating.php?kurs=" . $_GET["kurs"]);
    exit();
}

if(isset($_GET["f"])){
    if(!(empty($_GET["f"]))){
        if(empty($_COOKIE["anwesend"])) setcookie("anwesend", "--" . $_GET["f"] . ".f");
        else if(stripos($_COOKIE["anwesend"], "-" . $_GET["f"] . ".f", 0) == TRUE){
            setcookie("anwesend",  str_replace("-" . $_GET["f"] . ".f", "", $_COOKIE["anwesend"]));
        }
        else{
            setcookie("anwesend", str_replace("-" . $_GET["f"] . ".e", "", $_COOKIE["anwesend"]) . "-" . $_GET["f"] . ".f");

            if(stripos($_COOKIE["wertung"], "-" . $_GET["f"] . ".1", 0) == TRUE) setcookie("wertung",  str_replace("-" . $_GET["f"] . ".1", "", $_COOKIE["wertung"]));
            if(stripos($_COOKIE["wertung"], "-" . $_GET["f"] . ".2", 0) == TRUE) setcookie("wertung",  str_replace("-" . $_GET["f"] . ".2", "", $_COOKIE["wertung"]));
            if(stripos($_COOKIE["wertung"], "-" . $_GET["f"] . ".3", 0) == TRUE) setcookie("wertung",  str_replace("-" . $_GET["f"] . ".3", "", $_COOKIE["wertung"]));
            if(stripos($_COOKIE["wertung"], "-" . $_GET["f"] . ".4", 0) == TRUE) setcookie("wertung",  str_replace("-" . $_GET["f"] . ".4", "", $_COOKIE["wertung"]));
            if(stripos($_COOKIE["wertung"], "-" . $_GET["f"] . ".5", 0) == TRUE) setcookie("wertung",  str_replace("-" . $_GET["f"] . ".5", "", $_COOKIE["wertung"]));
        }
        
        header('Location: Student_rating.php?kurs=' . $_GET["kurs"]);
        exit();
    }
}

if(isset($_GET["e"])){
    if(!(empty($_GET["e"]))){
        if(empty($_COOKIE["anwesend"])) setcookie("anwesend", "--" . $_GET["e"] . ".e");
        else if(stripos($_COOKIE["anwesend"], "-" . $_GET["e"] . ".e", 0) == TRUE){
            setcookie("anwesend",  str_replace("-" . $_GET["e"] . ".e", "", $_COOKIE["anwesend"]));
        }
        else{
            setcookie("anwesend", str_replace("-" . $_GET["e"] . ".f", "", $_COOKIE["anwesend"]) . "-" . $_GET["e"] . ".e");
            
            if(stripos($_COOKIE["wertung"], "-" . $_GET["e"] . ".1", 0) == TRUE) setcookie("wertung",  str_replace("-" . $_GET["e"] . ".1", "", $_COOKIE["wertung"]));
            if(stripos($_COOKIE["wertung"], "-" . $_GET["e"] . ".2", 0) == TRUE) setcookie("wertung",  str_replace("-" . $_GET["e"] . ".2", "", $_COOKIE["wertung"]));
            if(stripos($_COOKIE["wertung"], "-" . $_GET["e"] . ".3", 0) == TRUE) setcookie("wertung",  str_replace("-" . $_GET["e"] . ".3", "", $_COOKIE["wertung"]));
            if(stripos($_COOKIE["wertung"], "-" . $_GET["e"] . ".4", 0) == TRUE) setcookie("wertung",  str_replace("-" . $_GET["e"] . ".4", "", $_COOKIE["wertung"]));
            if(stripos($_COOKIE["wertung"], "-" . $_GET["e"] . ".5", 0) == TRUE) setcookie("wertung",  str_replace("-" . $_GET["e"] . ".5", "", $_COOKIE["wertung"]));
        }
        
        header('Location: Student_rating.php?kurs=' . $_GET["kurs"]);
        exit();
    }
}

// --
if(isset($_GET["b1"])){
    if(!(empty($_GET["b1"]))){
        if(empty($_COOKIE["wertung"])) setcookie("wertung", "--" . $_GET["b1"] . ".1");
        else if(stripos($_COOKIE["wertung"], "-" . $_GET["b1"] . ".1", 0) == TRUE){
            setcookie("wertung",  str_replace("-" . $_GET["b1"] . ".1", "", $_COOKIE["wertung"]));
        }
        else{
            $wert = str_replace("-" . $_GET["b1"] . ".2", "", $_COOKIE["wertung"]);
            $wert = str_replace("-" . $_GET["b1"] . ".3", "", $wert);
            $wert = str_replace("-" . $_GET["b1"] . ".4", "", $wert);
            $wert = str_replace("-" . $_GET["b1"] . ".5", "", $wert);

            setcookie("wertung",  $wert . "-" . $_GET["b1"] . ".1");
        }
        header('Location: Student_rating.php?kurs=' . $_GET["kurs"]);
        exit();
    }
}

// -
if(isset($_GET["b2"])){
    if(!(empty($_GET["b2"]))){
        if(empty($_COOKIE["wertung"])) setcookie("wertung", "--" . $_GET["b2"] . ".2");
        else if(stripos($_COOKIE["wertung"], "-" . $_GET["b2"] . ".2", 0) == TRUE){
            setcookie("wertung",  str_replace("-" . $_GET["b2"] . ".2", "", $_COOKIE["wertung"]));
        }
        else{
            $wert = str_replace("-" . $_GET["b2"] . ".1", "", $_COOKIE["wertung"]);
            $wert = str_replace("-" . $_GET["b2"] . ".3", "", $wert);
            $wert = str_replace("-" . $_GET["b2"] . ".4", "", $wert);
            $wert = str_replace("-" . $_GET["b2"] . ".5", "", $wert);

            setcookie("wertung",  $wert . "-" . $_GET["b2"] . ".2");
        }
        header('Location: Student_rating.php?kurs=' . $_GET["kurs"]);
        exit();
    }
}

// 0
if(isset($_GET["b3"])){
    if(!(empty($_GET["b3"]))){
        if(empty($_COOKIE["wertung"])) setcookie("wertung", "--" . $_GET["b3"] . ".3");
        else if(stripos($_COOKIE["wertung"], "-" . $_GET["b3"] . ".3", 0) == TRUE){
            setcookie("wertung",  str_replace("-" . $_GET["b3"] . ".3", "", $_COOKIE["wertung"]));
        }
        else{
            $wert = str_replace("-" . $_GET["b3"] . ".1", "", $_COOKIE["wertung"]);
            $wert = str_replace("-" . $_GET["b3"] . ".2", "", $wert);
            $wert = str_replace("-" . $_GET["b3"] . ".4", "", $wert);
            $wert = str_replace("-" . $_GET["b3"] . ".5", "", $wert);

            setcookie("wertung",  $wert . "-" . $_GET["b3"] . ".3");
        }
        header('Location: Student_rating.php?kurs=' . $_GET["kurs"]);
        exit();
    }
}

// +
if(isset($_GET["b4"])){
    if(!(empty($_GET["b4"]))){
        if(empty($_COOKIE["wertung"])) setcookie("wertung", "--" . $_GET["b4"] . ".4");
        else if(stripos($_COOKIE["wertung"], "-" . $_GET["b4"] . ".4", 0) == TRUE){
            setcookie("wertung",  str_replace("-" . $_GET["b4"] . ".4", "", $_COOKIE["wertung"]));
        }
        else{
            $wert = str_replace("-" . $_GET["b4"] . ".1", "", $_COOKIE["wertung"]);
            $wert = str_replace("-" . $_GET["b4"] . ".2", "", $wert);
            $wert = str_replace("-" . $_GET["b4"] . ".3", "", $wert);
            $wert = str_replace("-" . $_GET["b4"] . ".5", "", $wert);

            setcookie("wertung",  $wert . "-" . $_GET["b4"] . ".4");
        }
        header('Location: Student_rating.php?kurs=' . $_GET["kurs"]);
        exit();
    }
}

// ++
if(isset($_GET["b5"])){
    if(!(empty($_GET["b5"]))){
        if(empty($_COOKIE["wertung"])) setcookie("wertung", "--" . $_GET["b5"] . ".5");
        else if(stripos($_COOKIE["wertung"], "-" . $_GET["b5"] . ".5", 0) == TRUE){
            setcookie("wertung",  str_replace("-" . $_GET["b5"] . ".5", "", $_COOKIE["wertung"]));
        }
        else{
            $wert = str_replace("-" . $_GET["b5"] . ".1", "", $_COOKIE["wertung"]);
            $wert = str_replace("-" . $_GET["b5"] . ".2", "", $wert);
            $wert = str_replace("-" . $_GET["b5"] . ".3", "", $wert);
            $wert = str_replace("-" . $_GET["b5"] . ".4", "", $wert);

            setcookie("wertung",  $wert . "-" . $_GET["b5"] . ".5");
        }
        
        header('Location: Student_rating.php?kurs=' . $_GET["kurs"]);
        exit();
    }
}

if(isset($_GET["save"])){
    if(!(empty($_GET["save"]))){
        if($_GET["save"] == "true"){
            safeData($_GET["kurs"]);
        }
    }
    setcookie("wertung", "", time() - 1);
    setcookie("anwesend", "", time() - 1);
    header("Location: class_inspection_overview.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

    <style>
        .page-content {
            width: 90%;
            max-width: 800px;
            margin: auto auto;
            margin-top: 15% ;
        }

        
        .header>.mdl-card__title {
            color: #fff;
            height: 90px;
            background: black;
        }
        .mdl-card__title-text{
            text-align: center !important;
            width: calc(100% - 32px);
            line-height: 50px;
        }

        .mdl-data-table{
            width: 100%;
        }

        .date{
            font-size: 20px;
        }

        .mdl-card__title-text {
            margin-right: 10px;
            size: 20px 100px;
        }

        .btn-wertung {
            background: 0 0;
border: solid grey;
border-radius: 2px;
color: #000;
position: relative;
height: 36px;
margin: 0;
min-width: 0px;
padding: 0 10px;
display: inline-block;
font-family: "Roboto","Helvetica","Arial",sans-serif;
font-size: 16px;
font-weight: 600;
text-transform: uppercase;
letter-spacing: 0;
overflow: hidden;
will-change: box-shadow;
transition: box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1),color .2s cubic-bezier(.4,0,.2,1);
outline: none;
cursor: pointer;
text-decoration: none;
text-align: center;
line-height: 36px;
vertical-align: middle;
        }

        .btn-wertung-active {
            background: 0 0;
border: solid red;
border-radius: 2px;
color: #000;
position: relative;
height: 36px;
margin: 0;
min-width: 0px;
padding: 0 10px;
display: inline-block;
font-family: "Roboto","Helvetica","Arial",sans-serif;
font-size: 16px;
font-weight: 600;
text-transform: uppercase;
letter-spacing: 0;
overflow: hidden;
will-change: box-shadow;
transition: box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1),color .2s cubic-bezier(.4,0,.2,1);
outline: none;
cursor: pointer;
text-decoration: none;
text-align: center;
line-height: 36px;
vertical-align: middle;
        }

     

        .btn-anwesend{
            background: 0 0;
border: solid grey;
border-radius: 2px;
color: #000;
position: relative;
height: 36px;
margin: 0;
min-width: 0px;
padding: 0 10px;
display: inline-block;
font-family: "Roboto","Helvetica","Arial",sans-serif;
font-size: 16px;
font-weight: 600;
text-transform: uppercase;
letter-spacing: 0;
overflow: hidden;
will-change: box-shadow;
transition: box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1),color .2s cubic-bezier(.4,0,.2,1);
outline: none;
cursor: pointer;
text-decoration: none;
text-align: center;
line-height: 36px;
vertical-align: middle;
        }

        .btn-anwesend-active{
            background: 0 0;
border: solid red;
border-radius: 2px;
color: #000;
position: relative;
height: 36px;
margin: 0;
min-width: 0px;
padding: 0 10px;
display: inline-block;
font-family: "Roboto","Helvetica","Arial",sans-serif;
font-size: 16px;
font-weight: 600;
text-transform: uppercase;
letter-spacing: 0;
overflow: hidden;
will-change: box-shadow;
transition: box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1),color .2s cubic-bezier(.4,0,.2,1);
outline: none;
cursor: pointer;
text-decoration: none;
text-align: center;
line-height: 36px;
vertical-align: middle;
            
        }

        .btn-sp-ab {
  border: none;
  background-color: inherit;
  padding: 10px 12px;
  font-size: 16px;
  cursor: pointer;
  display: inline-block;
  color: black;
}

.btn-sp-ab:hover {
            background: #eee;
            color: red;
        
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
                    <a class="mdl-navigation__link" href="class_inspection_overview.php">Klassen</a>

                </nav>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">TBS</span>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="index.php">Home</a>
                <a class="mdl-navigation__link" href="class_inspection_overview.php">Klassen</a>
                <a class="mdl-navigation__link" href="../includes/logout.php">Logout</a>

            </nav>
        </div>
        <main class="mdl-layout__content">

            <div class="page-content">
                <!--Fach1-->
                <div class=" header">
                    <div class="mdl-card__title" style=<?php echo '"background-color: ' . getFarbe($_GET["kurs"]) . '"'; ?>>
                        <div class="mdl-card__title-text">
                        <h2 class="mdl-card__title-text" id="intro">Kurs: <?php echo getName($_GET["kurs"]); ?> </h2>
                        <h3 class="date" ><?php echo date("d.m.Y"); ?></h3>
                    
                    </div>
                        <button id="demo-menu-lower-right"
                            class="mdl-button mdl-js-button mdl-button--icon">
                            <i class="material-icons">more_vert</i>
                            </button>
    
                            <ul class="mdl-menu mdl-menu--lower-right mdl-js-menu mdl-js-ripple-effect"
                            for="demo-menu-lower-right">
                            <li class="mdl-menu__item"><a href="class_inspection_overview.php">Kurs Überblick</a> </li>
                            <li class="mdl-menu__item"><a href="Students_overview.php">Schüler Überblick</a> </li>
                            <li class="mdl-menu__item"><a href="attendance_table.php">Anwesenheitstabelle</a> </li>
                            <li class="mdl-menu__item"><a href="edit_class.php"> Bearbeitung der Klasse</a></li>
                        </ul>

                    </div>
                </div>
                <table class="mdl-data-table  mdl-shadow--2dp">
                    <thead>
                      <tr>
                        <th>Schüler</th>
                        <th>Anwesenheit</th>
                        <th style="text-align: center;">Evaluation </th>
                        <th>Durchschnitt</th>
                        <th>Letzten 3 Durchschnitte</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      
                      $data = AllSchueler($_GET["kurs"]);
                      echo $data;

                      ?>
                    </tbody>
                </table>

                <?php echo '<a class="btn-sp-ab" href="Student_rating.php?kurs=' . $_GET["kurs"] . '&save=true">Speichern</a><a class="btn-sp-ab" href="Student_rating.php?kurs=' . $_GET["kurs"] . '&save=false">Abbrechen</a>'; ?>

            </div>
        </main>
    </div>
</body>

</html>


<?php

function checkKurs($tmp_KursID){
    require "../includes/db.php";

	$stmt = mysqli_stmt_init($conn);
	$sql = "select * from kurs where Lehrer_ID=? and Kurs_ID=?";
	
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "ss", $_SESSION["ID"], $tmp_KursID);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)){
		return true;
	}
	else{
		return false;
	}
}

function getName($tmp_kursID){
    require "../includes/db.php";

	$stmt = mysqli_stmt_init($conn);
	$sql = "select * from kurs where Lehrer_ID=? and Kurs_ID=?";
	
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "ss", $_SESSION["ID"], $tmp_kursID);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)){
		return $row["Name"];
	}
	else{
		return "/";
	}
}

function getFarbe($tmp_kursID){
    require "../includes/db.php";

	$stmt = mysqli_stmt_init($conn);
	$sql = "select * from kurs where Lehrer_ID=? and Kurs_ID=?";
	
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "ss", $_SESSION["ID"], $tmp_kursID);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)){
		return $row["Farbe"];
	}
}

function getAverage($tmp_KursID, $tmp_SchuelerID){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'SELECT Bewertung FROM bewertungen where Kurs_ID=' . $tmp_KursID . ' and Schueler_ID=' . $tmp_SchuelerID . ';';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	$number = 0;
    $count = 0;
	while ($row = mysqli_fetch_array($result)){
        if(!empty($row["Bewertung"])){
            $wert = decryptValue($row["Bewertung"]);
            if($wert != "/" && is_numeric($wert)){
                $number = $number + (int)$wert;
                $count++;
            }
        }
    }

    if($count == 0) return "/";

    return number_format(($number / $count), 2);
}

function getLastAverage($tmp_KursID, $tmp_SchuelerID){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'select * from bewertungen where Schueler_ID=' . $tmp_SchuelerID . ' and Kurs_ID=' . $tmp_KursID . ' ORDER BY Datum DESC LIMIT 3;';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	$number = 0;
    $count = 0;

    while ($row = mysqli_fetch_array($result)){
        $count++;
        
        if (!(empty($row["Bewertung"]))){
            $wert = decryptValue($row["Bewertung"]);
            if($wert != "/" && is_numeric($wert)){
                $number += (int)$wert;
            }
        }
    }

    if($number != 0){
        return number_format($number / $count, 2);
    }
    else{
        return "/";
    }
}

function AllSchueler($tmp_KursID){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'select * from teilnehmende where Kurs_ID="' . $tmp_KursID . '";';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

    $tmp_data = "";

	while ($row = mysqli_fetch_array($result)){

        $schueler_data = getSchuelerInfos($row["Schueler_ID"]);
        list($name, $ID) = explode("-", $schueler_data);

        $avg = getAverage($tmp_KursID, $row["Schueler_ID"]);
        $avg_last = getLastAverage($tmp_KursID, $row["Schueler_ID"]);

        $tmp_data = $tmp_data . '<tr><td>' . $name . '</td><td>';
        $add = "";

        if(GetAnwesend(true, "-" . $ID . ".f")) $add = "-active";
        else $add = "";
        $tmp_data = $tmp_data . '<a class="btn-anwesend' . $add . '" href="Student_rating.php?kurs=' . $_GET["kurs"] . '&f=' . $ID . '">F</a>';

        if(GetAnwesend(true, "-" . $ID . ".e")) $add = "-active";
        else $add = "";
        $tmp_data = $tmp_data . '<a class="btn-anwesend' . $add . '" href="Student_rating.php?kurs=' . $_GET["kurs"] . '&e=' . $ID . '">E</a>';
            
        $tmp_data = $tmp_data . '</td><td>';


        if(GetAnwesend(true, "-" . $ID . ".f") || GetAnwesend(true, "-" . $ID . ".e")){
            $tmp_data = $tmp_data . '<p class="btn-wertung">--</p>';
            $tmp_data = $tmp_data . '<p class="btn-wertung">-</p>';
            $tmp_data = $tmp_data . '<p class="btn-wertung">0</p>';
            $tmp_data = $tmp_data . '<p class="btn-wertung">+</p>';
            $tmp_data = $tmp_data . '<p class="btn-wertung">++</p>';
        }
        else{
            if(GetAnwesend(false, "-" . $ID . ".1"))  $add = "-active";
            else $add = "";
            $tmp_data = $tmp_data . '<a class="btn-wertung' . $add . '" href="Student_rating.php?kurs=' . $_GET["kurs"] . '&b1=' . $ID . '">--</a>';
            
            if(GetAnwesend(false, "-" . $ID . ".2"))  $add = "-active";
            else $add = "";
            $tmp_data = $tmp_data . '<a class="btn-wertung' . $add . '" href="Student_rating.php?kurs=' . $_GET["kurs"] . '&b2=' . $ID . '">-</a>';
            
            if(GetAnwesend(false, "-" . $ID . ".3"))  $add = "-active";
            else $add = "";
            $tmp_data = $tmp_data . '<a class="btn-wertung' . $add . '" href="Student_rating.php?kurs=' . $_GET["kurs"] . '&b3=' . $ID . '">0</a>';
            
            if(GetAnwesend(false, "-" . $ID . ".4"))  $add = "-active";
            else $add = "";
            $tmp_data = $tmp_data . '<a class="btn-wertung' . $add . '" href="Student_rating.php?kurs=' . $_GET["kurs"] . '&b4=' . $ID . '">+</a>';
            
            if(GetAnwesend(false, "-" . $ID . ".5"))  $add = "-active";
            else $add = "";
            $tmp_data = $tmp_data . '<a class="btn-wertung' . $add . '" href="Student_rating.php?kurs=' . $_GET["kurs"] . '&b5=' . $ID . '">++</a>';
        }   

        $tmp_data = $tmp_data . '
        </td>
        <td>' . $avg . '</td>
        <td>' . $avg_last . '</td></tr>';
    }

    return $tmp_data;
}

function getSchuelerInfos($tmp_SchuelerID){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'select * from schueler where Schueler_ID="' . $tmp_SchuelerID . '";';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

    $tmp_data = "";

	if ($row = mysqli_fetch_assoc($result)){
        return $row["Nachname"] . "." . $row["Name"] . "-" . $row["Schueler_ID"];
    }
    return $tmp_data;
}

function GetAnwesend($status, $string){
    if($status){
        if(isset($_COOKIE["anwesend"])){
            if(!(empty($_COOKIE["anwesend"]))){
                if(stripos($_COOKIE["anwesend"], $string, 0) == TRUE){
                    return true;
                }
            }
        }
    }
    else{
        if(isset($_COOKIE["wertung"])){
            if(!(empty($_COOKIE["wertung"]))){
                if(stripos($_COOKIE["wertung"], $string, 0) == TRUE){
                    return true;
                }
            }
        }
    }

    return false;
}


function safeData($tmp_KursID){
    require "../includes/db.php";

    $allSchueler = $_COOKIE["anwesend"];
    $an_list = explode("-", $allSchueler);

    delSchuelerData(true);
    delSchuelerData(false);

    foreach ($an_list as $schueler){
        if(strlen($schueler) > 2){
            $split = explode(".", $schueler);

            if(count($split) == 2){
                $id = $split[0];
                $datum = date("Y-m-d");
                $status = encrypt($split[1]);

                $sql = "INSERT INTO anwesenheit (Kurs_ID, Schueler_ID, Datum, Status) VALUES (?, ?, ?, ?);";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "ssss", $tmp_KursID, $id, $datum, $status);
                mysqli_stmt_execute($stmt);
            }
        }
    }

    $allSchueler = $_COOKIE["wertung"];
    $be_list = explode("-", $allSchueler);

    foreach ($be_list as $schueler){
        if(strlen($schueler) > 2){
            $split = explode(".", $schueler);

            if(count($split) == 2){
                $id = $split[0];
                $datum = date("Y-m-d");
                $status = encrypt($split[1]);

                $sql = "INSERT INTO bewertungen (Kurs_ID, Schueler_ID, Datum, Bewertung) VALUES (?, ?, ?, ?);";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "ssss", $tmp_KursID, $id, $datum, $status);
                mysqli_stmt_execute($stmt);
            }
        }
    }
}

function delSchuelerData($bool){
    require "../includes/db.php";
    $stmt = mysqli_stmt_init($conn);
    $datum = date("Y-m-d");

    if($bool == TRUE) $sql = 'delete from bewertungen Datum="' . $datum . '";';
    else $sql = 'delete from anwesenheit where Datum="' . $datum . '";';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
}

function preloadWertung(){
    require "../includes/db.php";

    $datum = date("Y-m-d");

    
    $stmt = mysqli_stmt_init($conn);
    $sql = 'select * from bewertungen where Kurs_ID="' . $_GET["kurs"] . '" and Datum="' . $datum . '";';

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $tmp_data = "-";
    while ($row = mysqli_fetch_array($result)){
        $wert = decryptValue($row["Bewertung"]);
        if($wert != "/" && is_numeric($wert)){
            $tmp_data = $tmp_data . "-" . $row["Schueler_ID"] . "." . $wert;
        }   
    }

    setcookie("wertung", $tmp_data);
}

function preloadAnwesend(){
    require "../includes/db.php";

    $datum = date("Y-m-d");

    
    $stmt = mysqli_stmt_init($conn);
    $sql = 'select * from anwesenheit where Kurs_ID="' . $_GET["kurs"] . '" and Datum="' . $datum . '";';

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $tmp_data = "-";
    while ($row = mysqli_fetch_array($result)){
        $wert = decryptAnwesenheit($row["Status"]);
        if($wert != "/"){
            $tmp_data = $tmp_data . "-" . $row["Schueler_ID"] . "." . $wert;
        }
        
    }

    setcookie("anwesend", $tmp_data);
}
?>