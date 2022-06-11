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
            width: auto;
            max-width: 50%;
            margin: auto auto;
            margin-top: 20%;
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
                    <a class="mdl-navigation__link" href="student_class_overview.php">Kurse</a>
                </nav>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">TBS</span>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="student_class_overview.php">Kurse</a>
                <a class="mdl-navigation__link" href="../includes/logout.php">Logout</a>
            </nav>
        </div>
        <main class="mdl-layout__content">
            <div class="page-content">
                <!-- Your content goes here -->
                <div class="demo-card-wide mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text">Willkommen zu TBS</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                    TBS ist die digitale Schnittstelle zwischen Schüler und Lehrer. Es bietet 100% Transparenz innerhalb der mündlichen Mitarbeit und Benotung.
                    </div>
                    <div class="mdl-card__actions mdl-card--border">
                        <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="student_class_overview.php">
                        Hier zu deinen Kursen
                      </a>
                    </div>
                    <div class="mdl-card__menu">
                    <a href = "mailto: transparentesbewertungssystem@gmail.com">
                      <i class="material-icons" >contact_support</i>
                      </a>
                    </div>
                </div>
        </main>
        </div>
</body>

</html>