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

if(isset($_GET["kurs"])){
    if(empty($_GET["kurs"])){
        header("Location: student_class_overview.php");
        exit();
    }
    else{
        if(!(checkKurs($_GET["kurs"]))){
            header("Location: student_class_overview.php");
            exit();
        }
    }
}
else{
    header("Location: student_class_overview.php");
    exit();
}

?>
<?php include "../includes/db.php";?>


<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Datum', 'Bewertung'],
          <?php
              $query='select * from bewertungen where Schueler_ID=' .  $_SESSION["ID"] . ' and Kurs_ID=' . $_GET["kurs"] . ";";
              $res=mysqli_query($conn, $query);
              while($data=mysqli_fetch_array($res)){
                $date=$data['Datum'];
                $bewertung=decryptValue($data['Bewertung']);
                
          ?>
          ['<?php echo $date;?>', <?php echo $bewertung;?>],
           <?php
              } 
              ?>
           ]);

        var options = {
          title: <?php echo '"' . getKursName($_GET["kurs"]) . '"'; ?>,
          curveType: 'function',
          legend: { position: 'bottom' }


        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        <?php 
        
        if(checkData($_GET["kurs"])){
            echo 'chart.draw(data, options);';
        }
        
        ?>
      }
    </script>

        <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

    <style>
        body{
            height: 100%;
        }
        .page-content{
            max-width: 800px;
            width: 75%;
            margin-bottom: 10% auto;
            margin-left: 18%;
            height: 70%;
            p
        }
        .mdl-card__title{
            color: #fff;
            height: 150px;
            background: rgb(21, 87, 5);
            max-width: 800px;
            margin-bottom: 5px;
        }
        .mdl-card__supporting-text{
            width: 90%;
            height: 40;
            padding-right:  5%;
            padding-left: 5%;
            padding-top: 6px;
            padding-bottom: 6px;
        }
        .header>.mdl-card__title {
            color: #fff;
            height: 90px;
            background: black;
        }
        .mdl-card__title-text{
            line-height: 50px;
            width: calc(100% - 32px);
        }
        .chart_performance{
            background-color: rgb(238, 238, 238);
        }
    </style>

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
        <main>
            <?php 
                    
            if(!checkData($_GET["kurs"])){
                echo '<p style="color:red;text-align:center;">Es wurde noch keine bewertung gemacht!</p>';
            }

            ?>
            <div class="page-content">
                <div id="curve_chart" style="width: 1400px; height: 800px"></div>
                    </div>
                    
                    </div>
                </div>
            </div>
        </main>
</body>
</html>

<?php

function getKursName($tmp_kursID){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = "select Name from kurs where Kurs_ID=" . $tmp_kursID;

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)){
        return $row["Name"];
    }

    return "/";
}

function checkKurs($tmp_KursID){
    require "../includes/db.php";

	$stmt = mysqli_stmt_init($conn);
	$sql = "select * from teilnehmende where Schueler_ID=? and Kurs_ID=?";
	
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

function checkData($tmp_KursID){
    require "../includes/db.php";

	$stmt = mysqli_stmt_init($conn);
	$sql = 'select * from bewertungen where Schueler_ID=' .  $_SESSION["ID"] . ' and Kurs_ID=' . $tmp_KursID . ";";
	
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)){
		return true;
	}
	else{
		return false;
	}
}

?>