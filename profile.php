<?php
    include "connection.php";



    // Recupera l'utente dal nome utente passato come parametro GET
    $username = $_GET['username'];
    $utente = getUtenteByUsername($username);

    if (!$utente) {
        echo "Utente non trovato";
        exit();
    }

    // Recupera le immagini dell'utente
    $immagini = getImmaginiByUserId($utente['id']);

    if (isset($_SESSION["username"])) {
        $utente = getUtenteByUsername($_SESSION["username"]);
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nuovaDescrizione = $_POST["nuovaDescrizione"];
    
            if (insertOrUpdateDescrizione($utente['id'], $nuovaDescrizione)) {
                echo "Descrizione aggiornata con successo.";
            } else {
                echo "Errore durante l'aggiornamento della descrizione.";
            }
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_button"])) {
        $imageId = $_POST["delete_image"];
        
        // Chiamata alla tua funzione di eliminazione
        if (deleteImage($imageId)) {
            echo "Immagine eliminata con successo.";
            // Aggiorna la pagina o esegui altre azioni necessarie
        } else {
            echo "Errore durante l'eliminazione dell'immagine.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilo Utente - <?php echo $utente['username']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f0f0f0;
        }

        h1 {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        td img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        td .image-details {
            font-size: 14px;
            color: #555;
        }

        #bottoneCaricaFoto {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div id="bottoneLogin">
        <a href="login.php">Login</a>
    </div>
    <div id="bottoneHome">
        <a href="home.php">Home</a>
    </div>
    <h1>Profilo Utente - <?php echo $utente['username']; ?></h1><br>
    <label for="descrizione">Descrizione:<?php echo $utente['descrizione']?></label>

    <div id="modificaDescrizioneModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form id="modificaDescrizioneForm" method="post" action="modifica_descrizione.php">
                <label for="nuovaDescrizione">Nuova Descrizione:</label>
                <input type="text" id="nuovaDescrizione" name="nuovaDescrizione" required>
                <input type="submit" value="Salva">
            </form>
        </div>
    </div>

    
    <button onclick="openModal()">Modifica Descrizione</button>

    <script>
        // Funzione per aprire la finestra modale
        function openModal() {
            document.getElementById('modificaDescrizioneModal').style.display = 'block';
        }

        // Funzione per chiudere la finestra modale
        function closeModal() {
            document.getElementById('modificaDescrizioneModal').style.display = 'none';
        }
    </script>

    <?php

        if (isset($_SESSION["username"]) && $_SESSION["username"] === $utente['username']) {
                echo '<div id="bottoneCaricaFoto">
                        <form action="carica_immagine.php" method="post" enctype="multipart/form-data">
                            <input type="file" name="file" accept="image/*">
                            <label for="descrizione">Descrizione:</label>
                            <input type="text" name="descrizione">
                            <label for="tag">Tag:</label>
                            <input type="checkbox" name="tag" value="1"> <!-- Selezionato: 1, Non selezionato: 0 -->
                            <input type="submit" value="Carica Foto">
                        </form>
                    </div>';
            }
    ?>





    <table>
        <tr>
            <th>Immagine</th>
            <th>Descrizione Immagine</th>
        </tr>
        <?php foreach ($immagini as $immagine) : ?>
            <tr>
                <td><img src='./uploads/<?php echo $immagine['Fname']; ?>' alt='Immagine Utente'></td>
                <td class="image-details"><?php echo $immagine['descrizione']; ?></td>
                <td>
                    <?php if ($_SESSION["username"] === $utente['username']) : ?>
                        <form id="formDelete_<?php echo $immagine['id']; ?>" method="post" onsubmit="deleteImage(<?php echo $immagine['id']; ?>); return false;">
                            <input type="hidden" name="delete_image" value="<?php echo $immagine['id']; ?>">
                            <button type="submit" name="delete_button">Elimina</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

</body>
</html>
