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
        .page-content {
            width: auto;
            min-width: 252px;
            max-width: 50%;
            margin: auto auto;
            margin-top: 10% ;
        }

        .header{
            background-color: black;
            padding-bottom: 20px;
        }
        .header>.mdl-card__title {
            color: #fff;
            height: 70px;
            background: black;
            padding: 5px 16px;

        }
        .mdl-card__title-text{
            text-align: center !important;
            line-height: 50px;
            width: calc(100% - 32px);
        }

        .mdl-data-table{
            width: 100%;
        }
        .menu_button{
            float: right;
            height: 20px;
        }

        .menu_list{
            overflow: visible;
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
                <div class=" header" style=<?php echo '"background-color: ' . getFarbe($_GET["kurs"]) . '"'; ?>>
                    <div class="mdl-card__title" style=<?php echo '"background-color: ' . getFarbe($_GET["kurs"]) . '"'; ?>>
                        <h1 class="mdl-card__title-text" id="intro" style="font-size: 35px;">Übersicht</h1>
                        <!-- Right aligned menu below button -->
                        <button id="demo-menu-lower-right" 
                            class="mdl-button mdl-js-button mdl-button--icon menu_button">
                            <i class="material-icons">more_vert</i>
                        </button>
                        <ul class="mdl-menu mdl-menu--lower-right mdl-js-menu mdl-js-ripple-effect"
                        for="demo-menu-lower-right">
                        <?php
                        echo '<li disabled class="mdl-menu__item"><a href="attendance_table.php?kurs=' . $_GET["kurs"] . '">Anwesenheit</a></li>';
                        echo '<li disabled class="mdl-menu__item"><a href="edit_class.php?kurs=' . $_GET["kurs"] . '&preload=true"> Bearbeitung der Klasse</a></li>';
                        ?>
                    </ul>
                </div>
                <div class="mdl-card__title" style=<?php echo '"background-color: ' . getFarbe($_GET["kurs"]) . '"'; ?>>
                    <h3 class="mdl-card__title-text">Kurs: <?php echo getName($_GET["kurs"]); ?></h3>
                </div>
            </div>
            <table class="mdl-data-table  mdl-shadow--2dp">
                <thead>
                  <tr>
                    <th  >Schüler</th>
                    <th>Klasse  </th>
                    <th>Average </th>
                  </tr>
                </thead>
                <tbody>
                    <?php echo AllSchueler($_GET["kurs"]); ?>
                </tbody>
            </table>
            <?php
                echo '<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href= "edit_class.php?kurs=' . $_GET["kurs"] . '&preload=true">Klasse Bearbeiten</a>';
            ?>    
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
        list($name, $klasse) = explode("-", $schueler_data);
        
        $avg = getAverage($tmp_KursID, $row["Schueler_ID"]);


        $tmp_data = $tmp_data . '<tr>
            <td>' . $name . '</td>
            <td>' . $klasse . '</td>
            <td>' . $avg . '</td>
            </tr>';
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
        return $row["Nachname"] . "." . $row["Name"] . "-" . $row["Klasse"];
    }
    return $tmp_data;
}


?>
