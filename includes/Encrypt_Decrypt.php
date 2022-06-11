<?php

//Verschlüsselungs Token 2
function returnTokenEnd(){
    return "Token";
}



function decryptAnwesenheit($end_hash){

    $token = getSecretToken();
    if(password_verify("f" . $token, $end_hash)) return "f";
    else if(password_verify("e" . $token, $end_hash)) return "e";
    else return "/";

}

function decryptValue($end_hash){

    $token = getSecretToken();
    if(password_verify("1" . $token, $end_hash)) return "1";
    else if(password_verify("2" . $token, $end_hash)) return "2";
    else if(password_verify("3" . $token, $end_hash)) return "3";
    else if(password_verify("4" . $token, $end_hash)) return "4";
    else if(password_verify("5" . $token, $end_hash)) return "5";
    else return "/";
    
}

function encrypt($wert){
    $token = getSecretToken();
    return password_hash($wert . $token, PASSWORD_DEFAULT);
}


function getSecretToken(){
    require "../includes/db.php";

    $stmt = mysqli_stmt_init($conn);
    $sql = 'SELECT Inhalt FROM passwoerter WHERE Name="VerschluesselungsToken";';
    
	mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
	
    if ($row = mysqli_fetch_assoc($result)) {
        return $row["Inhalt"] . returnTokenEnd();
        
    }
	else{
		return "secretWord" . returnTokenEnd();
	}
}



?>