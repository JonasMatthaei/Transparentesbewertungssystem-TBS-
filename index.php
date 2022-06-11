<?php

session_start();

if(isset($_SESSION["Perm"])){
    if($_SESSION["Perm"] == "lehrer"){
        header("Location: TeacherView/index.php");
    }
    else if($_SESSION["Perm"] == "schueler"){
        header("Location: StudentView/index.php");
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
            box-sizing: border-box;
            position: fixed;
            left: 15%;
            right: 15%;
            width: 70%;
            max-width: 800px;
            top: 5%;
            height: auto; 
            max-height: 900px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .demo-card-wide.mdl-card {
            width: 100%;
        }
        
        .demo-card-wide>.mdl-card__title {
            color: #fff;
        }
    </style>

    <script>
    </script>



</head>

<body>

    <main class="mdl-layout__content">
        <div class="page-content">
            <!-- Your content goes here -->
            <div class="demo-card-wide mdl-card mdl-shadow--2dp">
                <img src="../tbs_pitch_foto_fertig.png" alt="A black, brown, and white dog wearing a kerchief">
                <div class="mdl-card__supporting-text">
                    Melden Sie sich für mehr Transparenz in der mündlichen Note mit ihrem WDG-Microsoft-Account hier an!
                
                    <?php

                    if(isset($_GET["error"])){
                        if($_GET["error"] == "notfound"){
                            require "Login/oauth.php";
                            echo '<p style="color:red;">Du bist kein Schueler dieser Schule!</p>';
                            echo '<a href="' . $logout_url . '">Jetzt ausloggen!</a>';
                        }
                        else if($_GET["error"] == "admin_pwd_fail"){
                            echo '<p style="color:red;">Das Admin Passwort konnte nicht abgerufen werden!</p>';
                        }
                    }

                    ?>
                </div>
                <div class="mdl-card__actions mdl-card--border">
                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" class="reset_button" href="Login/authorize.php?login=token">Login with Office 365</a>
                </div>

            </div>
        </div>
    </main>
</body>

</html>
