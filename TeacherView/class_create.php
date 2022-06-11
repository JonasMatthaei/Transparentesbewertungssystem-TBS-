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

if(isset($_GET["jahrgang"])){
    if(!(empty($_GET["jahrgang"]))){
        setcookie("jahrgang", $_GET["jahrgang"]);
        setcookie("klasse", "", time() - 1);
        setcookie("schueler", "", time() - 1);
        header("Location: class_create.php");
        exit();
    }
}

if(isset($_GET["klasse"])){
    if(!(empty($_GET["klasse"]))){
        setcookie("klasse", $_GET["klasse"]);
        setcookie("schueler", "", time() - 1);
        header("Location: class_create.php");
        exit();
    }
}

if(isset($_GET["schueler"])){
    if(!(empty($_GET["schueler"]))){
        setcookie("schueler", $_GET["schueler"]);
        header("Location: class_create.php");
        exit();
    }
}

if(isset($_GET["add"])){
    if(!(empty($_COOKIE["schueler"])) && $_GET["add"] == "schueler"){
        setcookie("add_schueler", $_COOKIE["add_schueler"] . "-" . $_COOKIE["schueler"]);
        setcookie("schueler", "", time() - 1);
        header("Location: class_create.php");
        exit();
    }

    if(!(empty($_COOKIE["klasse"])) && $_GET["add"] == "klasse"){
        $all_names = getSchuelerFromKlasse();
        setcookie("add_schueler", $_COOKIE["add_schueler"] . $all_names);
        header("Location: class_create.php");
        exit();
    }
}

if(isset($_GET["del"])){
    if(!(empty($_COOKIE["add_schueler"]))){
        setcookie("add_schueler", str_replace("-" . $_GET["del"], "", $_COOKIE["add_schueler"]));
        header("Location: class_create.php");
        exit();
    }
}

$error = "";

if(isset($_COOKIE["name"])){
    if(checkName()){
        $error = "double";
    }
}

if(isset($_GET["safe"]) && $error != "double"){
    if($_GET["safe"] == "true"){
        if(!(empty($_COOKIE["name"]))){
            safeKurs();
        }
        else{
            $error = "empty";
        }
    }

    if ($error != "empty"){
        setcookie("jahrgang", "", time() - 1);
        setcookie("klasse", "", time() - 1);
        setcookie("schueler", "", time() - 1);
        setcookie("color", "", time() - 1);
        setcookie("name", "", time() - 1);
        setcookie("add_schueler", "", time() - 1);
        header("Location: class_inspection_overview.php");
        exit();
    }
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
            width: auto;
            max-width: 90%;
            
            margin: auto auto;
            margin-top: 10% ;
            
        }

        
        .header>.mdl-card__title {
            color: #fff;
            height: 90px;
            <?php
            
            if(isset($_COOKIE["color"])){
                echo 'background: ' . $_COOKIE["color"] . ";";
            }
            else{
                echo 'background: black;';
            }
            
            ?>

            margin-right: auto;

        }
        .mdl-card__title-text{
            text-align: center !important;
            width: calc(100% - 32px);
            line-height: 50px;

        }

        .mdl-data-table{
            width: 100%;
        }

        .header{
            width: 100%;
            margin: auto;
            border-radius: 5px;
            height: 90px;

        }

        .main-section {
            background: transparent;
            max-width: 800px;
            width: 90%;
            height: 500px;
            margin: 30px auto;
            border-radius: 10px;
        }

        .add-section {
            width: 100%;
            background: #fff;
            margin: 0px auto;
            padding: 0px;
            border-radius: 5px;
        }
    
        .add-section input {
            display: inline-block !important;
            outline: none;
            width: calc(83% - 4px);
            height: 40px;
            margin: 10px auto;
            border: 2px solid #ccc;
            font-size: 16px;
            border-radius: 5px;
            padding: 0px 5px;
            padding-right: 0;
            margin-right: 0;
        }
        
        .add-section div button {
            display: inline-block !important;
            float: right;
            width: 15%;
            height: 44px;
            margin: 10px auto;
            border: none;
            background: #0088FF;
            color: #fff;
            font-family: sans-serif;
            font-size: 16px;
            border-radius: 5px;
            padding: 0px 5px;
            cursor: pointer;
            padding-left: 0;
            margin-left: 0;
        }

        .add-section button {
            display: inline-block !important;
            float: right;
            width: 15%;
            height: 50px;
            margin: 3px auto;
            border: none;
            background: #0088FF;
            color: #fff;
            font-family: sans-serif;
            font-size: 16px;
            border-radius: 5px;
            padding: 0px 0px;
            cursor: pointer;
            padding-left: 0;
            margin-left: 0;
        }
        
        .add-section button:hover {
            box-shadow: 0 2px 2px 0 #ccc, 0 2px 3px 0 #ccc;
            opacity: 0.7;
        }
        
        .add-section button span {
            border: 1px solid #fff;
            border-radius: 50%;
            display: inline-block;
            width: 18px;
            height: 18px;
        }

        .show-students {
            width: 100%;
            background: #fff;
            margin: auto auto;
            padding: auto;
            border-radius: 5px;
            background-color: rgb(175, 171, 171);
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .title-students{
            width: 95%;
            margin: 5px 2.5%;
            box-shadow: 0 4px 8px 0 #ccc, 0 6px 20px 0 #ccc;
            border-radius: 5px;
            background-color: white;
            display: inline-block;
            padding: 5px auto;
            font-size: 18px;
            font-family: sans-serif;
            color: #555;
            height: 20px;
            text-align: center;
        }

        .show-student{
            width: 90%;
            margin: 5px auto;
            padding: auto 10px;
            box-shadow: 0 4px 8px 0 #ccc, 0 6px 20px 0 #ccc;
            border-radius: 5px;
            background-color: white;
        }
        
        .show-student p {
            display: inline-block;
            margin: 1px auto;
            font-size: 14px;
            font-family: sans-serif;
            color: #555;
            background-color: white;
            text-indent: 5px;
        }
        .remove_button{
            float: right;
            font-size: 14px;
            height: 26px;
            text-align: center;
        }
        
        .textfield{
            width: 70%;
            height: 80%;
            font-size: 20px;
            <?php 
            
            if(empty($_COOKIE["name"]) && $error == "empty"){
                echo 'color: red;';
            }
            else if($error == "double"){
                echo 'color: red;';
            }

            ?>
        
        }

        .dropbtn {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            margin-bottom: auto;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 120px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
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

        .mdl-button {
            
            margin-right: 80px;
        }

        .dropdown-menu {
            width: 100%;
            display: inline-block;
            margin-bottom: 5%;
        }

        .Hinzufügen-Button {
            display: inline-block;
            position: absolute;
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
                <div class="main-section">
                    <div class=" header">
                        <div class="mdl-card__title">
                            <?php

                            if(isset($_COOKIE["name"])){
                                echo '<input class="textfield" id="textfield" type="text" name="title" placeholder="Klassen Name" value="' . $_COOKIE["name"] . '"></input>';
                            }
                            else{
                                echo '<input class="textfield" id="textfield" type="text" name="title" placeholder="Klassen Name"></input>';
                            }

                            if(isset($_COOKIE["color"])){
                                echo '<input type="color" value="' . $_COOKIE["color"] . '" id="cp">';
                            }
                            else{
                                echo '<input type="color" value="#000000" id="cp">';
                            }
                            
                            ?>
                        
    
                        </div>
                        
                        <?php 
                        
                        if($error == "double"){
                            echo '<p style="color:red;">Name bereits vergeben!</p>';
                        }

                        ?>
                    </div>

                    <h4 style="color:grey;text-decoration:underline;"><i>Schueler hinzufügen</i></h4>
                    <div class="dropdown-menu">
                        <div class="dropdown">
                            <?php

                            if(empty($_COOKIE["jahrgang"])){
                                echo '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href= "">Jahrgang</a>';
                            }
                            else{
                                echo '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href= "">' . $_COOKIE["jahrgang"] . '</a>';
                            }
                            
                            ?>
                            
                            <div class="dropdown-content">
                                <?php 
                                
                                $jahrgaenge = getJahrgang();
                                echo $jahrgaenge;

                                ?>
                            </div>
                       </div>
                    
                 
                        <div class="dropdown">
                            <?php 
                            
                            if(empty($_COOKIE["klasse"])){
                                echo '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href="">Klasse</a>';
                            }
                            else{
                                echo '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href="">' . $_COOKIE["klasse"] . '</a>';
                            }

                            ?>
                            
                            <div class="dropdown-content">
                                <?php 
                                
                                if(isset($_COOKIE["jahrgang"])){
                                    $jahrgaenge = getKlassen();
                                    echo $jahrgaenge;
                                }
                                else{
                                    echo '<a href="">/</a>';
                                }
                                

                                ?>
                            </div>
                        </div>
                


                
                        <div class="dropdown">
                            <?php 
                            
                            if(empty($_COOKIE["schueler"])){
                                echo '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href="">Schüler</a>';
                            }
                            else{
                                echo '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href="">' . explode("_", $_COOKIE["schueler"])[0] . '</a>';
                            }

                            ?>
                            <div class="dropdown-content">
                                <?php 
                                
                                if(isset($_COOKIE["klasse"])){
                                    $jahrgaenge = getSchueler();
                                    if(empty($jahrgaenge) || $jahrgaenge == ""){
                                        echo '<a href="">/</a>';
                                    }
                                    else{
                                        echo $jahrgaenge;
                                    }
                                    
                                }
                                else{
                                    echo '<a href="">/</a>';
                                }

                                ?>
                            </div>
                        </div>
                     
                        <div class="Hinzufügen-Button">
                            <a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href= "class_create.php?add=schueler">Add</a>
                        </div>
                    
                    </div>


                    <h4 style="color:grey;text-decoration:underline;"><i>Klasse hinzufügen</i></h4>
                    <div class="dropdown-menu">
                        <div class="dropdown">
                            <?php

                            if(empty($_COOKIE["jahrgang"])){
                                echo '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href= "">Jahrgang</a>';
                            }
                            else{
                                echo '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href= "">' . $_COOKIE["jahrgang"] . '</a>';
                            }
                            
                            ?>
                            
                            <div class="dropdown-content">
                                <?php 
                                
                                $jahrgaenge = getJahrgang();
                                echo $jahrgaenge;

                                ?>
                            </div>
                       </div>
                    
                 
                        <div class="dropdown">
                            <?php 
                            
                            if(empty($_COOKIE["klasse"])){
                                echo '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href="">Klasse</a>';
                            }
                            else{
                                echo '<a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href="">' . $_COOKIE["klasse"] . '</a>';
                            }

                            ?>
                            
                            <div class="dropdown-content">
                                <?php 
                                
                                if(isset($_COOKIE["jahrgang"])){
                                    $jahrgaenge = getKlassen();
                                    echo $jahrgaenge;
                                }
                                else{
                                    echo '<a href="">/</a>';
                                }
                                

                                ?>
                            </div>
                        </div>
                     
                        <div class="Hinzufügen-Button">
                            <a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href= "class_create.php?add=klasse">Add</a>
                        </div>
                    
                    </div>

                    <div class="show-students">
                        <div class="title-students">
                            <p>Hinzugefügte Schüler</p>
                        </div>
                        
                        <?php 
                        
                        $allSchueler = showSchueler();
                        echo $allSchueler;
                        
                        ?>
                    </div>

                    <p><a class="btn-sp-ab" href="class_create.php?safe=true">Speichern</a>  <a class="btn-sp-ab" href="class_create.php?safe=false">Abbrechen</a></p>
                </div>
            </div>


            <script>
                document.getElementById("cp").addEventListener("change", watchColorPicker, false);
                document.getElementById("textfield").addEventListener("change", safeName, false);

                function watchColorPicker(event) {
                    document.cookie = "color=" + event.target.value;
                    location.reload();
                }

                function safeName(event) {
                    document.cookie = "name=" + event.target.value;
                    location.reload();
                }

            </script>
        </main>
    </div>
</body>
    
</html>


<?php

function checkName(){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
	$sql = 'select * from kurs where Lehrer_ID=? and Name=?;';

	mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $_SESSION["ID"], $_COOKIE["name"]);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)){
		return true;
	}
    else{
        return false;
    }
}

function safeKurs(){
    require "../includes/db.php";
    
    $farbe = "#000000";
    if(!(empty($_COOKIE["color"]))){
        $farbe = $_COOKIE["color"];
    }

    $stmt = mysqli_stmt_init($conn);
    
    $sql = "INSERT INTO kurs (Lehrer_ID, Name, Farbe) VALUES (?, ?, ?);";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $_SESSION["ID"], $_COOKIE["name"], $farbe);
    mysqli_stmt_execute($stmt);

    $allSchueler = $_COOKIE["add_schueler"];
    $list = explode("-", $allSchueler);
    $kurs_id = getKursID();

    if($kurs_id == "/") return;

    foreach ($list as $schueler){
        $split = explode("_", $schueler);

        if(count($split) == 2){
            $id = $split[1];

            $sql = "INSERT INTO teilnehmende (Schueler_ID, Kurs_ID) VALUES (?, ?);";
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $id, $kurs_id);
            mysqli_stmt_execute($stmt);
        }
    }
}

function getKursID(){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
	$sql = 'select Kurs_ID from kurs where Lehrer_ID=? and Name=?;';

	mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $_SESSION["ID"], $_COOKIE["name"]);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)){
		return $row["Kurs_ID"];
	}
    else{
        return "/";
    }
}


function showSchueler(){
    if(empty($_COOKIE["add_schueler"])){
        return '<p style="text-align: center; color: grey;"><i>Keine Schüler vorhanden!</i></p>';
    }
    $allSchueler = $_COOKIE["add_schueler"];

    $list = explode("-", $allSchueler);
    $tmp_data = "";

    foreach ($list as $schueler){
        if(!(empty($schueler))){
            try{
                $split = explode("_", $schueler);

                if(count($split) == 2){
                    $name = $split[0];
                    $id = $split[1];

                    $tmp_data = $tmp_data . '<div class="show-student">
                            <p>' . $name . '</p>
                            <a class="remove_button" href="class_create.php?del=' . $name . '_' . $id . '">
                                Entfernen
                            </a>
                        </div>';
                }
                
            }
            catch (Exception $e) {
                ;
            }

            
        }
        
    }

    return $tmp_data;
    
}

function getJahrgang(){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = "select distinct(Jahrgang) from schueler order by 1 asc";

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

    $tmp_data = "";

	while ($row = mysqli_fetch_array($result)){
        $tmp_data = $tmp_data . '<a href="class_create.php?jahrgang=' . $row[0] . '">' . $row[0] . '</a>';
    }

    return $tmp_data;
}

function getKlassen(){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'select distinct(Klasse) from schueler where jahrgang="' . $_COOKIE["jahrgang"] . '" order by 1 asc;';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

    $tmp_data = "";

	while ($row = mysqli_fetch_array($result)){
        $tmp_data = $tmp_data . '<a href="class_create.php?klasse=' . $row[0] . '">' . $row[0] . '</a>';
    }

    return $tmp_data;
}

function getSchueler(){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'select * from schueler where Klasse="' . $_COOKIE["klasse"] . '" order by Nachname asc;';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

    $tmp_data = "";

	while ($row = mysqli_fetch_array($result)){
        if(!(empty($_COOKIE["add_schueler"]))){
            if(stripos($_COOKIE["add_schueler"], $row[2] . "." . $row[1] . "_" . $row[0], 0) == FALSE){
                $tmp_data = $tmp_data . '<a href="class_create.php?schueler=' . $row[2] . '.' . $row[1] . '_' . $row[0] . '">' . $row[2] . '.' . $row[1] . '</a>';
            }
        }
        else{
            $tmp_data = $tmp_data . '<a href="class_create.php?schueler=' . $row[2] . '.' . $row[1] . '_' . $row[0] . '">' . $row[2] . '.' . $row[1] . '</a>';
        }
    }

    return $tmp_data;
}


function getSchuelerFromKlasse(){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'select * from schueler where Klasse="' . $_COOKIE["klasse"] . '" order by Nachname asc;';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

    $tmp_data = "";

	while ($row = mysqli_fetch_array($result)){
        $name_data = $row["Nachname"] . "." . $row["Name"] . "_" . $row["Schueler_ID"];
        if(stripos($_COOKIE["add_schueler"], $name_data, 0) == FALSE){
            $tmp_data = $tmp_data . "-" . $name_data;
        }
    }

    return $tmp_data;
}

?>