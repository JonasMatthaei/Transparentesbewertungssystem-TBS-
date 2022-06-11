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
            left: auto;
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
                    <a class="mdl-navigation__link" href="../index/index.html">Home</a>
                    <a class="mdl-navigation__link" href="../Klassen_Overview/Klasse.html">Klassen</a>

                </nav>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">TBS</span>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="../index/index.html">Home</a>
                <a class="mdl-navigation__link" href="../Klassen_Overview/Klasse.html">Klassen</a>
                <a class="mdl-navigation__link" href="../includes/logout.php">Logout</a>

            </nav>
        </div>
        <main class="mdl-layout__content">
            <div class="page-content">
                <!--Fach1-->
                <div class="demo-card-wide0 mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text"> Performance</h2>
                        <button id="demo-menu-lower-left" class="mdl-button mdl-js-button mdl-button--icon menu_button">
                            <i class="material-icons">more_vert</i>
                        </button>

                        <ul class="menu_list mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="demo-menu-lower-left">
                            <li class="mdl-menu__item"><a href="student_class_inspection.html"> Klassen Ansicht</a></li>
                            <li class="mdl-menu__item"><a href="student_performance_chart.html">student_performance_chart</a> </li>

                        </ul>
                    </div>
                    <div class="mdl-card__supporting-text">
                        Lehrer: Christian Buzuk, Jürgen Solf, Markus Kneißler
                    </div>
                    <div class="mdl-card__supporting-text">
                        Dein Avg: 3.7
                    </div>
                    <div class="mdl-card__supporting-text">
                        Letzte Bewertung: 4
                    </div>
                    <div class="mdl-card__supporting-text">
                        last 3 Avg: 4.1
                    </div>
                    <div class="mdl-card__supporting-text">
                        Beste Bewertung: 5
                    </div>
                    <div class="mdl-card__supporting-text">
                        Schlechteste Bewertung: 2
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>

</html>