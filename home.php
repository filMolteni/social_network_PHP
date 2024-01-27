<?php 
    include "connection.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_button"])) {
        $imageId = $_POST["delete_image"];
        
        // Chiamata alla tua funzione di eliminazione
        if (deleteImage($imageId)) {
            echo "Immagine eliminata con successo.";
        } else {
            echo "Errore durante l'eliminazione dell'immagine.";
        }
    } else {
        echo "Richiesta non valida.";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elenco Utenti</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        h1 {
            text-align: center;
            padding: 20px;
            background-color: #3498db;
            color: white;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        img {
            display: block;
            margin: auto;
        }

        button {
            padding: 8px 12px;
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }
        #filtro {
            margin-top: 10px;
            text-align: center;
        }

        #filtro form {
            display: inline-block;
        }

        #filtro input[type="text"] {
            padding: 8px;
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        #filtro input[type="submit"] {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #filtro input[type="submit"]:hover {
            background-color: #45a049;
        }

        
        #bottoneLogin {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        #bottoneLogin a {
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #bottoneLogin a:hover {
            background-color: #2980b9;
        }
        #bottoneRegistrati {
            position: absolute;
            top: 50px;
            right: 10px;
        }

        #bottoneRegistrati a {
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #bottoneRegistrati a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <!-- Bottone Login -->
    <div id="bottoneLogin">
        <a href="login.php">Login</a>
    </div>
    <div id="bottoneRegistrati">
        <a href="registrati.php">registrati</a>
    </div>
    <h1>Elenco Utenti</h1>

    <div id="filtro">
    <form action="home.php" method="get">
        <input type="text" id="filtro" name="filtro" placeholder="Inserisci il nome...">
        <input type="submit" value="Cerca">
    </form>
</div>


    <table>
        <tr>
        <th>Immagine</th>
            <th>Username</th>
            <th>Descrizione</th>
            <th>Followers</th>
           
            <th>Azioni</th>
        </tr>   
        <?php      
            if(isset($_GET["filtro"]) && $_GET["filtro"]!="null"){
                $arr_obj = cercaUtentiPerNome($_GET["filtro"]);
            }
            else{
                $arr_obj = getElencoUtenti();
            }

            foreach($arr_obj as $utente) {
                echo "<tr>
                <td><img src='./uploads/{$utente['Fname']}' alt='Immagine Utente' style='width: 50px; height: 50px;'></td>
                        <td>{$utente['username']}</td>
                        <td>{$utente['descrizione']}</td>
                        <td>{$utente['followers']}</td>
                        <td>
                            <button onclick='visualizzaProfilo(\"{$utente['username']}\")'>Visualizza Profilo</button>
                        </td>
                    </tr>";
            }
        ?>
    </table>

    <script>
        function visualizzaProfilo(username) {
            window.location.href = "profile.php?username=" + username;
        }
    </script>
</body>
</html>