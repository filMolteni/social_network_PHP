<?php
session_start();
include "connection.php";

// Controlla se il form è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Percorso della cartella dove salvare le immagini
    $cartellaUpload = "uploads/";

    // Percorso completo del file
    $percorsoFile = $cartellaUpload . basename($_FILES["file"]["name"]);

    // Ottieni l'estensione del file
    $estensione = strtolower(pathinfo($percorsoFile, PATHINFO_EXTENSION));

    // Controlla se il file è un'immagine
    $tipiFileSupportati = array("jpg", "jpeg", "png", "gif");
    if (!in_array($estensione, $tipiFileSupportati)) {
        echo "Errore: Accettiamo solo file di tipo JPG, JPEG, PNG, GIF.";
        exit();
    }

    // Verifica se il file esiste già
    if (file_exists($percorsoFile)) {
        echo "Errore: Il file esiste già.";
        exit();
    }

    // Verifica la dimensione del file (esempio: 5 MB)
    if ($_FILES["file"]["size"] > 5 * 1024 * 1024) {
        echo "Errore: Il file è troppo grande. Massimo consentito: 5 MB.";
        exit();
    }

    // Spostare il file dalla cartella temporanea alla cartella di destinazione
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $percorsoFile)) {

        // Ottieni l'ID dell'utente loggato
        $utente = getUtenteByUsername($_SESSION["username"]);

        // Usa il nome del file come Fname
        $imageName = basename($_FILES["file"]["name"]);

        // Altri valori
        $descrizione = isset($_POST['descrizione']) ? $_POST['descrizione'] : '';
        $tag = isset($_POST['tag']) ? 1 : 0;
        // Chiamata alla funzione per inserire l'immagine
        insertImmagineByFile($utente['id'], $imageName, $descrizione, $tag);

        header("location: profile.php?username=" . $_SESSION["username"]);

    } else {
        echo "Errore durante il caricamento del file.";
    }
} else {
    echo "Accesso non consentito.";
}
?>
