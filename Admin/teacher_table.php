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


if(isset($_GET["delTrue"])){
    if(!(empty($_GET["delTrue"]))){
        delLehrer($_GET["delTrue"]);
    }
}

if(isset($_GET["del"])){
    if(!empty($_GET["del"])){
        echo '<script> var check = confirm("Wollen Sie diesen Lehrer Löschen?"); 
                    if (check == true) {
                        window.location = "teacher_table.php?delTrue=' . $_GET["del"] . '";
                    }</script>';
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
            min-width: 252px;
            max-width: 50%;
            margin: auto auto;
            margin-top: 10% ;
        }

        .header{
            background-color: black;
            padding: 16px;
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

        .mdl-card__title-text {
            font-size: 35px;
            margin-top: 50px;


        }

        .mdl-card__title {
            margin: auto auto;
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
                    <a class="mdl-navigation__link" href="show_student.php">Schülerverwaltung</a>
                    <a class="mdl-navigation__link" href="show_teacher.php">Lehrerverwaltung</a>
                    <a class="mdl-navigation__link" href="../includes/logout.php">Logout</a>

            </nav>
        </div>
        <main class="mdl-layout__content">

            <div class="page-content">
                <!--Fach1-->
                <div class=" header" >
                    <div class="mdl-card__title">
                        <h1 class="mdl-card__title-text" id="intro">Lehrer Übersicht</h1>
                        <!-- Right aligned menu below button -->
                        <button id="demo-menu-lower-right" 
                            class="mdl-button mdl-js-button mdl-button--icon menu_button">
                            <i class="material-icons">more_vert</i>
                        </button>
                        <ul class="mdl-menu mdl-menu--lower-right mdl-js-menu mdl-js-ripple-effect"
                        for="demo-menu-lower-right">
                        <li disabled class="mdl-menu__item"><a href="show_student.php">Schülerverwaltung</a></li>
                        <li disabled class="mdl-menu__item"><a href="show_teacher.php">Lehrerverwaltung</a></li>
                    </ul>
                </div>

            </div>
            <table class="mdl-data-table  mdl-shadow--2dp">
                <thead>
                  <?php
                  
                  $data = getLehrer();
                  echo $data;

                  ?>
                </thead>
              </table>
            </div>
        </main>
    </div>
</body>

</html>

<?php 

function getLehrer(){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'select * from lehrer order by Name asc;';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

    $tmp_data = "";

	while ($row = mysqli_fetch_array($result)){
        $tmp_data = $tmp_data . '<tr>
        <th style="text-align: start;">' . $row["Name"] . '</th>
        <th style="text-align: start;">' . $row["Email"] . '</th>
        <th style="text-align: end;color:red;"><a href="teacher_table.php?del=' . $row["Lehrer_ID"] . '">Löschen</a></th>
      </tr>';
    }

    return $tmp_data;
}


function delLehrer($lehrer_id){
    require "../includes/db.php";
    $stmt = mysqli_stmt_init($conn);

    $sql = 'delete from lehrer where Lehrer_ID="' . $lehrer_id . '";';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
}

?>


