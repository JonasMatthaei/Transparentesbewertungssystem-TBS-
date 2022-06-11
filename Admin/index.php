<?php

session_start();

if(isset($_SESSION["Perm"])){
    if($_SESSION["Perm"] != "admin"){
        header("Location: ../index.php");
    }
    if(isset($_GET["pwd"])){
        header("Location: index.php");
    }
}
else if(isset($_GET["pwd"])){
    $o_pwd = getPwd();

    if($o_pwd == "/"){
        header("Location: ../index.php?error=admin_pwd_fail");
    }

    if($o_pwd != $_GET["pwd"]){
        header("Location: ../index.php");
    }

    $_SESSION["Perm"] = "admin";
    header("Location: index.php");
}
else{
    header("Location: ../index.php");
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
                    <a class="mdl-navigation__link" href="show_student.php">Schülerverwaltung</a>
                    <a class="mdl-navigation__link" href="show_teacher.php">Lehrerverwaltung</a>
                </nav>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">TBS</span>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="show_student.php">Schülerverwaltung</a>
                <a class="mdl-navigation__link" href="show_teacher.php">Lehrerverwaltung</a>
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
                        <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="show_student.php">
                        Schülerverwaltung
                      </a>
                      <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="show_teacher.php">
                        Lehrerverwaltung
                      </a>
                    </div>
                    <div class="mdl-card__menu">
                    
                        
                      <a href = "mailto: transparentesbewertungssystem@gmail.com">

                      <i class="material-icons" >contact_support</i>
                      </a>
                    </div>

                    </div>
                </div>
        </main>
        </div>
</body>

</html>

<?php

function getPwd(){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
	$sql = 'select * from passwoerter where Name="Admin_Passwort";';

	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_execute($stmt);

	$result = mysqli_stmt_get_result($stmt);

	if ($row = mysqli_fetch_assoc($result)){
		return $row["Inhalt"];
	}
	else {
		return "/";
	}
}

// Admin url: http://localhost/Transparent-evaluation-system/Projekt/Admin/index.php?pwd=7pblA3mLFZPgXL4PlJHu7jdFA0zUJ1QfVw7LUK5ADuhk0zkjIIvRtMPJPUbMAE2hDAkqgE0znsFtKPpwD9mxySVTtByP0XKiBAf5q2t33Zyw3tmUwC28z0idy8oq2ncOWjfsUyogMLS0RbZsPnNMNgIZYCXYeQkX81ThUPLJrOI1mmboDKli6bpMbF4YvSzd9k2KwIMcZcJVJP6hJwgSGiJ4rAHNfnyyGUI68bPrVgiav0e8meyJLN4r137VsXnxOZ7x0e8cXIMCia3a3d2YjznnnhGvGlTCYKmm2p509jAbfaMrBjKFaoqgwJo5UbwZ8I0DBgxEf8xT0cqCGiU3fOnvfTLon8R6x6cEOGB5bCq7u7TQtfs4UXtBfw0MQNa1Q5rhomNxyorObiIwrc6HAA56CyU0zUbzXaYZ0uzc5it75xYbXC0OYwblDqjusNHNjLZj69dRN7SwsU3tgEiyLCvBxSZ5xp8WUtCUw8gd3smjpIOlFMULzMgNW6ZGjquAg3WpDmpBymizRuWGMVNzNjpYX1jwdxbnMiHfQrh0wyBlaEqKh2WgeiRSUty7KLTIQhwKGpW6Qj9VfUbBvXHzszkCx5KIXF1wN3gvek7xc8HAsNhmQ2LyV06gKjOcHQOLrS1fkjtxrWNdBjPn5sAWAaS5zRInBBdP5P8GRQJFPDju9Urb8azGxns6nqVorrZOgsQDN9DezJ7YG9ZYnkcR8KsXe6vRif55A3nTnSZexYaihNq2zR7NXj0K5u6TYQibNA12NUcrMPMiHvM4c4s0nT07Wf2wYHQOeyP56UwN7DeS1BFm8upcoK0g3AA7Hs3R5eaVSEY7sCOd9P5aVnF8piM8tIWu2ZHI3eTmJgw8rzD24S5PXWxQBSaJcGNAAnZLplVsHbvrwKfnBxP9yN9JCwRvg1biBEf4FIeBYmKKQNyk0MMelb5cQ72qPAu4G4qGnq6Qggk0FUGrJqcawwB7qO2fkEQJdCBRHIa2rPWwdf7dRLR82Zco2EJ4VonFXTRoXKYdo0d8lbKdRt3hivwZy2yrnlFNmDlnnxNlScf03Y7ScIwxQQpwJhv55aXzJj1D6M8JEIr9x79oB2Vyjs1v7RbRyCKPIlQ59JnOLSHBQULZk3HkarnkyYbUyWzHEeqrkKACyMOeenVBLY2JnDAJStltQD44LDbvBfxG23VJFYFJHkUUWX9E5VDsKI6JdjWKzlUue84fKesFMZpEzbFFLzpDYlkx7iaERwIHBNDYb6oRSRDQsTp3FASYmbliyKA2Anwn8Bhu33dWtkH0uAoIZAPxzhHQ2ugejTAVBhvAs5sf1EcRqEjVHzVHidv39UCkWPVHQ32rOR6mn4tw71jqpw5A4vCYFcNpq5OSBA3LdCkG9os6fP9wsRlnYbWWmZazwr0isvy2uFE5WuEfjn1d3wQV9AhtsXrRS2iT7JH3TrqCRkprQMgyT2g1uTBF7GeW4c7mb8ThgC3LdlKUxjqI06aAy8ys

?>