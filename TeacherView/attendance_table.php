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


if(isset($_GET["change_date"]) && isset($_GET["change"]) && isset($_GET["change_id"])){
    if(!empty($_GET["change_date"]) && !empty($_GET["change"]) && !empty($_GET["change_id"])){
        $tmp_change = "del";
        
        if($_GET["change"] == "present") $tmp_change = "f";
        else if($_GET["change"] == "missing") $tmp_change = "e";
        else if($_GET["change"] == "excused") $tmp_change = "del";
    
        set_activity($_GET["change_date"], $tmp_change, $_GET["change_id"]);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>attendance-table</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <script src='main.js'></script>

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
            width: 834px;
        }
        .mdl-card__title-text{
            text-align: center !important;
            width: calc(100% - 32px);
            line-height: 50px;
        }

        .mdl-data-table{
            width: 100%;
        }
        .attendance_tabel{
            width: 800px;

        }

        .missing{
            background-color: red;
            border: solid;
            color: black;
            padding: 2%;
            padding-left: 8.5%;
            padding-right: 8.5%;
            text-decoration: none;

        }
        .excused{
            background-color: yellow;
            border: solid;
            color: black;
            padding: 2%;
            padding-left: 5%;
            padding-right: 5%;
            text-decoration: none;

        }
        .present{
            background-color: green;
            border: solid;
            color: black;
            padding: 2%;
            padding-left: 6.8%;
            padding-right: 6.8%;
            text-decoration: none;

        }
        .attendance-table tr, td{
            width: 100px;
        }

        .thead-text{
            text-align: center;
            width:100px;
        }

        .mdl-data-table th{
            text-align: center;
        }

        .mdl-data-table{
            min-width: 834px;
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
                        <?php
                        
                        echo '<h2 class="mdl-card__title-text" id="intro">Anwesenheitstabelle Klasse: ' . getName($_GET["kurs"]) . '</h2>';
                        
                        ?>
                        <button id="demo-menu-lower-left" 
                        class="mdl-button mdl-js-button mdl-button--icon menu_button">
                        <i class="material-icons">more_vert</i>
                        </button>
                       
                        <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect"
                        for="demo-menu-lower-left">      
                        <li class="mdl-menu__item"><a href="class_inspection_overview.php">Kurse</a> </li>
                        <li class="mdl-menu__item"><a href="Students.overview.php">Schüler Überblick</a> </li>
                        <li class="mdl-menu__item"><a href="edit_class.php">Kurs Bearbeiten</a> </li>
                        <li class="mdl-menu__item">  <a href="../StudentView/line.php?kurs=' . $kurs_id . '"> Visualisierung</a></li> 
                        </ul>
                        
                    </div>
                </div>
                <table class="mdl-data-table  mdl-shadow--2dp attendance-table">
                    <thead>
                      <tr>
                        <th class="thead-text" >Schüler</th>
                        
                        <?php

                        $data = showLastDates();
                        echo $data;

                        ?>
                        
                      </tr>
                    </thead>
                    <?php

                    $data = showSchueler();
                    echo $data;

                    ?>
                  </table>

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


function showLastDates(){
    $dates = getLastDates(false);

    $tmp_data = "";

    $count = 0;
    foreach($dates as $date){
        list($year, $month, $day) = explode("-", $date);
        $new_date = $day . "." . $month . "." . $year;
        $tmp_data = $tmp_data . '<th class="thead-text" >' . $new_date . '</th>';
        $count++;
    }
    return $tmp_data;
}

function getLastDates($status){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = "";
    if($status) $sql = 'SELECT Datum FROM anwesenheit WHERE Kurs_ID=' . $_GET["kurs"] . ' group by Datum order by Datum DESC LIMIT 5';
    else $sql = 'SELECT Datum FROM bewertungen WHERE Kurs_ID=' . $_GET["kurs"] . ' group by Datum order by Datum DESC LIMIT 5';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

    $list = array();

    while ($row = mysqli_fetch_array($result)){
        array_push($list, $row[0]);
    }

    if(count($list) <= 0 && !$status) return getLastDates(true);

    return array_reverse($list);
}




function showSchueler(){
    $ids = getSchueler();
    $datums = getLastDates(false);

    $tmp_data = "";
    $count = 0;

    foreach($ids as $schueler){
        $name = getSchuelerName($schueler);
        $tmp_data = $tmp_data . '<tbody><tr><td style="width:100px;text-align:center !important;" >' . $name . '</td>';

        foreach($datums as $date){
            $anwesend = getAnwesend($schueler, $date);
            $status = "/";
            $class = "present";

            if($anwesend == "f"){
                $status = "Fehlend";
                $class = "missing";
            }
            else if($anwesend == "e"){
                $status = "Entschuldigt";
                $class = "excused";
            }
            else if($anwesend == "/"){
                $status = "Anwesend";
            }
            
            $tmp_path = 'attendance_table.php?kurs=' . $_GET["kurs"] . '&change_date=' . $date . '&change=' . $class . '&change_id=' . $schueler;
            $tmp_data = $tmp_data . '<td style="text-align:center !important;"><a href="' . $tmp_path . '" style="width:100px" class="' . $class . '">' . $status . '</a></td>';
        }

        $count++;
    }
    if($count == 0) return '</table><p style="color:red;text-align:center;">Es sind keine Schueler in diesem Kurs</p><table>';
    else if(count($datums) == 0) return $tmp_data . '</tr></tbody></table><p style="color:red;text-align:center;">Es wurde noch kein Schüler Bewertet</p><table>';
    
    $tmp_data = $tmp_data . '</tr></tbody>';

    return $tmp_data;
}

function getSchueler(){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'SELECT DISTINCT(Schueler_ID) FROM teilnehmende WHERE Kurs_ID="' . $_GET["kurs"] . '";';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

    $list = array();

    while ($row = mysqli_fetch_array($result)){
        array_push($list, $row["Schueler_ID"]);
    }

    return $list;
}

function getSchuelerName($tmp_SchuelerID){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'select * from schueler where Schueler_ID="' . $tmp_SchuelerID . '";';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

    $tmp_data = "";

	if ($row = mysqli_fetch_assoc($result)){
        return $row["Nachname"] . "." . $row["Name"];
    }
    return $tmp_data;
}

function getAnwesend($tmp_SchuelerID, $datum){
    require "../includes/db.php";

    
    $stmt = mysqli_stmt_init($conn);
    $sql = 'select Status from anwesenheit where Kurs_ID="' . $_GET["kurs"] . '" and Datum="' . $datum . '" and Schueler_ID="' . $tmp_SchuelerID . '";';

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);


    if ($row = mysqli_fetch_assoc($result)){
        return decryptAnwesenheit($row["Status"]);
    }

    return "/";
}

function set_activity($tmp_date, $tmp_change, $tmp_change_id){
    delActivity($tmp_date, $tmp_change_id);

    if($tmp_change == "del") return;

    $tmp_change = encrypt($tmp_change);

    require "../includes/db.php";
    $stmt = mysqli_stmt_init($conn);
    
    $sql = "INSERT INTO anwesenheit (Kurs_ID, Schueler_ID, Datum, Status) VALUES (?, ?, ?, ?);";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $_GET["kurs"], $tmp_change_id, $tmp_date, $tmp_change);
    mysqli_stmt_execute($stmt);
}

function delActivity($tmp_date, $tmp_change_id){
    require "../includes/db.php";
    $stmt = mysqli_stmt_init($conn);

    $sql = 'delete from anwesenheit where Schueler_ID="' . $tmp_change_id . '" and Datum="' . $tmp_date . '";';

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
}

?>
