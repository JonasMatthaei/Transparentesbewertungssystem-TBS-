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


<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <style>
        body {
            height: 100%;
        }
        
        .page-content {
            max-width: 800px;
            width: 75%;
            margin: 5% auto;
            height: 70%;
        }
        
        .mdl-card__title {
            color: #fff;
            height: 150px;
            background: rgb(21, 87, 5);
            max-width: 800px;
            margin-bottom: 5px;
        }
        
        .mdl-card__supporting-text {
            width: 90%;
            height: 40;
            padding-right: 5%;
            padding-left: 5%;
            padding-top: 6px;
            padding-bottom: 6px;
        }
        
        .header>.mdl-card__title {
            color: #fff;
            height: 90px;
            background: black;
        }
        
        .mdl-card__title-text {
            line-height: 50px;
            width: calc(100% - 32px);
        }
        
        .chart_performance {
            background-color: rgb(238, 238, 238);
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
        <main>
            <div class="page-content">
                <div class="header">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text">S1-Biologie</h2>
                        <button id="demo-menu-lower-left" class="mdl-button mdl-js-button mdl-button--icon menu_button">
                            <i class="material-icons">more_vert</i>
                        </button>

                        <ul class="menu_list mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="demo-menu-lower-left">
                            <li class="mdl-menu__item"><a href="students_overview.html"> Schüler overview</a></li>
                            <li class="mdl-menu__item"><a href="student_performance_chart.html">Visualisierung</a> </li>
                            <li class="mdl-menu__item"><a href="anwesenheitstabelle">Anwesenheitstabelle</a> </li>
                            <li class="mdl-menu__item"><a href="edit_class.html"> Bearbeitung der Klasse</a></li>

                        </ul>
                    </div>
                </div>
                <div class="chart_performance">
                    <canvas id="myChart" style="width:100%;max-width:800px"></canvas>

                    <script>
                        var xValues = [50, 60, 70, 80, 90, 100, 110, 120, 130, 140, 150];
                        var yValues = [7, 8, 8, 9, 9, 9, 10, 11, 14, 14, 15];

                        new Chart("myChart", {
                            type: "line",
                            data: {
                                labels: xValues,
                                datasets: [{
                                    fill: false,
                                    lineTension: 0,
                                    backgroundColor: "rgba(0,0,255,1.0)",
                                    borderColor: "rgba(0,0,255,0.1)",
                                    data: yValues
                                }]
                            },
                            options: {
                                legend: {
                                    display: false
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            min: 6,
                                            max: 16
                                        }
                                    }],
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </main>
</body>

</html>