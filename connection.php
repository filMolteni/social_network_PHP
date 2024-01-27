<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "instagram_molteni";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    function checkUtenteCorretto($user, $password) {
        global $conn;
        
        $usr = trim($user);
        $psw = md5($password);

        // Utilizzo di un prepared statement
        $sql = "SELECT id, username FROM `users` WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usr, $psw);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
           
            return true;
        } else {
            return false;
        }
    }

   

    function chiudi_connessione() {
        global $conn;
        $conn->close();
    }
    function getElencoUtenti() {
        global $conn;
    
        $sql = "SELECT u.id, u.username, u.descrizione, u.followers, f.Fname
                FROM `users` u
                LEFT JOIN `foto` f ON u.id = f.user_id AND f.tag = 1";
        $result = $conn->query($sql);
    
        $arr = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($arr, $row);
            }
        }
    
        return $arr;
    }
    
    

    function getUtenteByUsername($username) {
        global $conn;
    
        // Utilizzo di un prepared statement
        $sql = "SELECT id, username, descrizione, followers FROM `users` WHERE username = ?";
        $stmt = $conn->prepare($sql);
    
        if (!$stmt) {
            die("Errore nella preparazione dello statement: " . $conn->error);
        }
    
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if (!$result) {
            die("Errore nell'esecuzione della query: " . $conn->error);
        }
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }
    


        
    function getImmaginiByUserId($userId) {
        global $conn;
    
        // Utilizzo di un prepared statement con una JOIN
        $sql = "SELECT f.id, f.Fname, f.descrizione, f.tag 
        FROM `foto` f
        LEFT JOIN `users` u ON f.user_id = u.id
        WHERE u.id = ?";

        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $arr = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($arr, $row);
            }
        }
    
        return $arr;
    }
    
    function cercaUtentiPerNome($filtro) {
        global $conn;
    
        // Utilizzo di un prepared statement
        $sql = "SELECT u.id, u.username, u.descrizione, u.followers, f.Fname
                FROM `users` u
                LEFT JOIN `foto` f ON u.id = f.user_id AND f.tag = 1
                WHERE u.username LIKE ?
                LIMIT 0, 25";
        $stmt = $conn->prepare($sql);
        $filtro = "%" . $filtro . "%"; 
        $stmt->bind_param("s", $filtro);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $arr = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($arr, $row);
            }
        }
    
        return $arr;
    }
    function insertImmagineByFile($userId, $imageName, $descrizione, $tag) {
        global $conn;
    
        // Utilizzo di un prepared statement
        $sql = "INSERT INTO `foto` (user_id, Fname, descrizione, tag) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
    
        if (!$stmt) {
            die("Errore nella preparazione dello statement: " . $conn->error);
        }
    
        $stmt->bind_param("isss", $userId, $imageName, $descrizione, $tag);
    
        if (!$stmt->execute()) {
            die("Errore nell'esecuzione della query: " . $stmt->error);
        }
    
        $stmt->close();
        return true; // Restituisci un valore di successo
    }
    
    
    
    
    

    // File: connection.php

    function deleteImage($imageId) {
        global $conn;
    
        // Ottieni il nome del file prima di eliminarlo
        $fileName = getFileNameById($imageId);
    
        if (!$fileName) {
            echo "Nome del file non trovato.";
            return false;
        }
    
        // Elimina l'immagine dal database
        $sqlDelete = "DELETE FROM `foto` WHERE id = ?";
        $stmt = $conn->prepare($sqlDelete);
        $stmt->bind_param("i", $imageId);
    
        if (!$stmt->execute()) {
            // Errore nell'esecuzione della query
            echo "Errore durante l'eliminazione dal database.";
            return false;
        }
    
        // Elimina il file dalla cartella uploads
        $percorsoCompleto = "C:/xampp/htdocs/insta/uploads/" . $fileName;
    
        if (file_exists($percorsoCompleto)) {
            unlink($percorsoCompleto);
            echo "Il file è stato eliminato con successo.";
        } else {
            echo "Il file non esiste.";
        }
    
        return true;
    }
    
    
    // Funzione ausiliaria per ottenere il nome del file da un ID
    function getFileNameById($imageId) {
        global $conn;
    
        $sql = "SELECT Fname FROM `foto` WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $imageId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['Fname'];
        }
    
        return null;
    }
    
    




    function insertUtente($username, $password) {
        global $conn;

        $pass=md5($password);

        $sql = "INSERT INTO `users` (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Errore nella preparazione dello statement: " . $conn->error);
        }

        $stmt->bind_param("ss", $username, $pass);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return true;  
        } else {
            return false; 
        }
    }

    // Funzione per inserire/aggiornare la descrizione del profilo
    function insertOrUpdateDescrizione($userId, $nuovaDescrizione) {
    global $conn;

    // Verifica se esiste già una descrizione per l'utente
    $checkSql = "SELECT * FROM `users` WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $userId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // L'utente ha già una descrizione, esegui l'aggiornamento
        $updateSql = "UPDATE `users` SET descrizione = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $nuovaDescrizione, $userId);

        if (!$updateStmt->execute()) {
            return false;
        }
    } else {
        // L'utente non ha ancora una descrizione, esegui l'inserimento
        $insertSql = "INSERT INTO `users` (id, descrizione) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("is", $userId, $nuovaDescrizione);

        if (!$insertStmt->execute()) {
            return false;
        }
    }

    return true;
}

?>
