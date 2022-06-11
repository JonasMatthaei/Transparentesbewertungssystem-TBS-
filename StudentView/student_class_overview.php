<?php

session_start();

if(isset($_SESSION["Perm"])){
    if($_SESSION["Perm"] != "schueler"){
        header("Location: ../index.php");
    }
}
else{
    header("Location: ../index.php");
    exit();
}

//import section
require "../includes/Encrypt_Decrypt.php";

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
        .demo-list-control {
            width: 300px;
        }
        
        .page-content {
            margin: 5% auto;
            width: auto;
            max-width: 50%;
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
            padding-bottom: 16px;
            margin-bottom: 15px;
        }
        
        .demo-card-wide0>.mdl-card__title {
            color: #fff;
            height: 150px;
            background: rgb(21, 87, 5);
            margin-bottom: 25px;
        }
        
        .mdl-card__title-text {
            line-height: 100px;
        }
        
        .demo-card-wide1>.mdl-card__title {
            color: #fff;
            height: 150px;
            background: rgb(161, 25, 25);
            margin-top: 25px;
        }
        
        .demo-card-wide>.mdl-card__menu {
            color: rgb(255, 255, 255);
        }
        
        .mdl-button {
            margin-top: 25px;
        }
        
        .mdl-card__supporting-text {
            width: 90%;
            height: 40;
            padding-right: 5%;
            padding-left: 5%;
            padding-top: 6px;
            padding-bottom: 6px;
        }
        
        .menu_button {
            margin-top: 20px;
        }
        
        .menu_list {
            overflow: visible;
        }

        .dropbtn {


border: none;
cursor: pointer;
}


.dropdown {
  margin-left: 10px;
  background: transparent;
 border: none !important;
position: relative;
display: inline-block;
position: relative;
}

.dropdown-content {
display: none;
position: absolute;
background-color: #f9f9f9;
min-width: 160px;
box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
z-index: 1;
}

.dropdown-content a {
color: red;
padding: 12px 16px;
text-decoration: none;
display: block;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
display: block;
}


.dropdown:hover .dropbtn {
background-color: #3e8e41;
}
    </style>

    <script>
    </script>



</head>

<body>
    <!-- Always shows a header, even in smaller screens. -->
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
                </nav>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">TBS</span>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="index.php">Home</a>
                <a class="mdl-navigation__link" href="../includes/logout.php">Logout</a>
            </nav>
        </div>
        <main class="mdl-layout__content">
            <div class="page-content">
                <?php 
                
                $data = showAllKurse();
                echo $data;
                
                ?>
            </div>
        </main>
    </div>
</body>

</html>


<?php



function getAllKursIDs(){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = "select Kurs_ID from teilnehmende where Schueler_ID=" . $_SESSION["ID"];

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

    $list = array();

	while ($row = mysqli_fetch_array($result)){
        array_push($list, $row["Kurs_ID"]);
    }

    return $list;
}


function showAllKurse(){
    $ID_list = getAllKursIDs();

    $tmp_data = "";

    foreach($ID_list as $kurs_id){
        $kurs_data = getKursInfos($kurs_id);
        list($Name, $Farbe) = explode('-', $kurs_data);

        $avg_last = getLastAverage($kurs_id, $_SESSION["ID"]);
        $avg_my = getMyAverage($kurs_id, $_SESSION["ID"]);
        $avg_class = getAverage($kurs_id, $_SESSION["ID"]);
        $missed = getAnwesend($kurs_id, $_SESSION["ID"]);

        $tmp_data = $tmp_data . '<div class="demo-card-wide0 mdl-card mdl-shadow--2dp">
        <div class="mdl-card__title" style="background-color: ' . $Farbe . ';">
            <h2 class="mdl-card__title-text">' . $Name . '</h2>
            <div class="dropdown">
            <button>
                <i class="material-icons">more_vert</i>
            </button>
            <div class="dropdown-content">
            <a href="../StudentView/line.php?kurs=' . $kurs_id . '"> Visualisierung</a></li>
            <a href="attendance_table.php?kurs=' . $kurs_id . '">Anwesenheit</a> </li>   
            </div>
    </div>
        </div>
        <div class="mdl-card__supporting-text">
            Dein Durchschnitt: ' . $avg_my . '
        </div>
        <div class="mdl-card__supporting-text">
            Letzte Stunde: ' . $avg_last . '
        </div>
        <div class="mdl-card__supporting-text">
            Klassen Durchschnitt: ' . $avg_class . '
        </div>

        <div class="mdl-card__supporting-text">
            <a href="attendance_table.php?kurs=' . $kurs_id . '">Unentschuldigte Stunden: ' . $missed . '</a>
        </div>

        <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="../StudentView/line.php?kurs=' . $kurs_id . '">Hier zur Visualisierung</a>
    </div></br>';
    
    }

    return $tmp_data;


}

function getMyAverage($tmp_KursID, $tmp_SchuelerID){
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


function getAverage($tmp_KursID){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'SELECT Bewertung FROM bewertungen where Kurs_ID=' . $tmp_KursID . ' and Bewertung is not null;';

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

function getAnwesend($tmp_kursID, $tmp_SchuelerID){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'SELECT count(Schueler_ID) as anw, Status FROM anwesenheit WHERE Schueler_ID=' . $tmp_SchuelerID . ' and Kurs_ID=' . $tmp_kursID . ';';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	while ($row = mysqli_fetch_array($result)){
        if(!empty($row["Status"])){
            $wert = decryptAnwesenheit($row["Status"]);
            if($wert != "/"){
                return $row["anw"];
            }
        }
    }

    return "/";
}


function getKursInfos($tmp_kursID){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = "select Name, Farbe from kurs where Kurs_ID=" . $tmp_kursID;

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)){
        return $row["Name"] . "-" . $row["Farbe"];
    }
}


function getLastAverage($tmp_KursID, $tmp_SchuelerID){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'select * from bewertungen where Schueler_ID=' . $tmp_SchuelerID . ' and Kurs_ID=' . $tmp_KursID . ' ORDER BY Datum DESC;';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
    

    while ($row = mysqli_fetch_array($result)){
        if (!(empty($row["Bewertung"]))){
            return number_format(decryptValue($row["Bewertung"]), 2);
        }
    }

    return "/";
}




?>