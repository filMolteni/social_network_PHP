<?php 
    session_start();
    include "connection.php";

    if(isset($_POST["login"])){
        $psw = $_POST["password"];
        $usr = $_POST["username"]; 

        if(checkUtenteCorretto($usr, $psw)){
            
            

            // Redirect alla pagina dell'utente loggato
            header("location: profile.php?username=".$usr);
        }
        else{
            header("location: login.php?msg=login errato");
        }
    }
?>
